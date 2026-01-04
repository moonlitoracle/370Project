<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse('error', 'Unauthorized. Please log in.');
    }
}

// GET TEST - Retrieve test questions for a skill and level
if ($method === 'GET' && $action === 'get_test') {
    requireAuth();
    
    $skillId = $_GET['skill_id'] ?? null;
    $level = $_GET['level'] ?? null;
    
    if (!$skillId || !$level) {
        sendResponse('error', 'skill_id and level are required');
    }
    
    $userId = $_SESSION['user_id'];
    
    // Check if test exists for this skill/level
    $stmt = $conn->prepare("
        SELECT test_id, test_title, passing_score, time_limit_minutes
        FROM proficiency_tests
        WHERE skill_id = ? AND required_level = ?
    ");
    $stmt->execute([$skillId, $level]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$test) {
        sendResponse('error', 'No test found for this skill and level');
    }
    
    // Check user's current level for this skill
    $stmt = $conn->prepare("
        SELECT proficiency FROM user_skills
        WHERE user_id = ? AND skill_id = ?
    ");
    $stmt->execute([$userId, $skillId]);
    $userSkill = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Determine if user is eligible to take this test
    $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
    $currentLevel = $userSkill ? $userSkill['proficiency'] : 'None';
    $currentLevelIndex = array_search($currentLevel, $levels);
    $targetLevelIndex = array_search($level, $levels);
    
    if ($currentLevel !== 'None' && $targetLevelIndex <= $currentLevelIndex) {
        sendResponse('error', 'You already have this proficiency level or higher');
    }
    
    // Get test questions (without correct answers)
    $stmt = $conn->prepare("
        SELECT question_id, question_text, option_a, option_b, option_c, option_d, points
        FROM test_questions
        WHERE test_id = ?
        ORDER BY question_id
    ");
    $stmt->execute([$test['test_id']]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $test['questions'] = $questions;
    $test['total_questions'] = count($questions);
    
    sendResponse('success', 'Test retrieved', $test);
}

// SUBMIT TEST - Process test submission and calculate score
if ($method === 'POST' && $action === 'submit_test') {
    requireAuth();
    
    $input = json_decode(file_get_contents('php://input'), true);
    $skillId = $input['skill_id'] ?? null;
    $level = $input['level'] ?? null;
    $answers = $input['answers'] ?? [];
    
    if (!$skillId || !$level || empty($answers)) {
        sendResponse('error', 'skill_id, level, and answers are required');
    }
    
    $userId = $_SESSION['user_id'];
    
    // Get test info
    $stmt = $conn->prepare("
        SELECT test_id, passing_score
        FROM proficiency_tests
        WHERE skill_id = ? AND required_level = ?
    ");
    $stmt->execute([$skillId, $level]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$test) {
        sendResponse('error', 'Test not found');
    }
    
    // Get correct answers
    $stmt = $conn->prepare("
        SELECT question_id, correct_answer, points
        FROM test_questions
        WHERE test_id = ?
    ");
    $stmt->execute([$test['test_id']]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate score
    $totalPoints = 0;
    $earnedPoints = 0;
    
    foreach ($questions as $question) {
        $totalPoints += $question['points'];
        $questionId = $question['question_id'];
        $userAnswer = $answers[$questionId] ?? null;
        
        if ($userAnswer === $question['correct_answer']) {
            $earnedPoints += $question['points'];
        }
    }
    
    $percentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;
    $passed = $percentage >= $test['passing_score'];
    
    // Store attempt
    $stmt = $conn->prepare("
        INSERT INTO test_attempts (user_id, test_id, score, total_points, passed, answers)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $userId,
        $test['test_id'],
        $earnedPoints,
        $totalPoints,
        $passed ? 1 : 0,
        json_encode($answers)
    ]);
    
    // If passed, update user skill proficiency
    if ($passed) {
        // Check if user already has this skill
        $stmt = $conn->prepare("
            SELECT skill_id FROM user_skills
            WHERE user_id = ? AND skill_id = ?
        ");
        $stmt->execute([$userId, $skillId]);
        $existingSkill = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingSkill) {
            // Update proficiency
            $stmt = $conn->prepare("
                UPDATE user_skills
                SET proficiency = ?
                WHERE user_id = ? AND skill_id = ?
            ");
            $stmt->execute([$level, $userId, $skillId]);
        } else {
            // Add skill with new proficiency
            $stmt = $conn->prepare("
                INSERT INTO user_skills (user_id, skill_id, proficiency)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$userId, $skillId, $level]);
        }

        // TRIGGER LEVEL UPDATE
        require_once 'utils.php';
        updateUserLevel($userId, $conn);
    }
    
    sendResponse('success', $passed ? 'Test passed! Proficiency updated.' : 'Test failed. Try again!', [
        'passed' => $passed,
        'score' => $earnedPoints,
        'total_points' => $totalPoints,
        'percentage' => $percentage,
        'passing_score' => $test['passing_score']
    ]);
}

// GET ATTEMPT HISTORY - View past test attempts
if ($method === 'GET' && $action === 'attempts') {
    requireAuth();
    
    $skillId = $_GET['skill_id'] ?? null;
    
    if (!$skillId) {
        sendResponse('error', 'skill_id is required');
    }
    
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("
        SELECT ta.attempt_id, ta.score, ta.total_points, ta.passed, ta.attempted_at,
               pt.test_title, pt.required_level, pt.passing_score
        FROM test_attempts ta
        JOIN proficiency_tests pt ON ta.test_id = pt.test_id
        WHERE ta.user_id = ? AND pt.skill_id = ?
        ORDER BY ta.attempted_at DESC
    ");
    $stmt->execute([$userId, $skillId]);
    $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse('success', 'Attempt history retrieved', $attempts);
}

// GET ALL USER ATTEMPTS - For dashboard test history
if ($method === 'GET' && $action === 'all_attempts') {
    requireAuth();
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("
        SELECT ta.attempt_id, ta.score, ta.total_points, ta.passed, ta.attempted_at,
               pt.test_title, pt.required_level, pt.passing_score,
               s.name as skill_name
        FROM test_attempts ta
        JOIN proficiency_tests pt ON ta.test_id = pt.test_id
        JOIN skills s ON pt.skill_id = s.skill_id
        WHERE ta.user_id = ?
        ORDER BY ta.attempted_at DESC
        LIMIT 10
    ");
    $stmt->execute([$userId]);
    $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse('success', 'All test attempts retrieved', $attempts);
}

sendResponse('error', 'Invalid request');
?>
