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
        sendResponse('error', 'Unauthorized');
    }
}

// LIST all available skills (for dropdown)
if ($method === 'GET' && $action === 'all') {
    $stmt = $conn->query("SELECT skill_id, name, description FROM skills ORDER BY name");
    sendResponse('success', 'All skills retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

// LIST user's skills
if ($method === 'GET' && $action === 'list') {
    requireAuth();
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT s.skill_id, s.name, s.description, us.proficiency
        FROM user_skills us
        JOIN skills s ON us.skill_id = s.skill_id
        WHERE us.user_id = ?
    ");
    $stmt->execute([$userId]);
    sendResponse('success', 'User skills retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

// ADD a skill to user
if ($method === 'POST' && $action === 'add') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $skillId = $input['skill_id'] ?? null;
    $proficiency = 'Beginner'; // ALWAYS start at Beginner - upgrades require passing tests

    if (!$skillId) {
        sendResponse('error', 'skill_id required');
    }

    $userId = $_SESSION['user_id'];

    // Check if skill exists
    $stmt = $conn->prepare("SELECT skill_id FROM skills WHERE skill_id = ?");
    $stmt->execute([$skillId]);
    if (!$stmt->fetch()) {
        sendResponse('error', 'Skill not found');
    }

    // Check if already added
    $stmt = $conn->prepare("SELECT * FROM user_skills WHERE user_id = ? AND skill_id = ?");
    $stmt->execute([$userId, $skillId]);
    if ($stmt->fetch()) {
        sendResponse('error', 'Skill already added');
    }

    $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill_id, proficiency) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$userId, $skillId, $proficiency]);
        sendResponse('success', 'Skill added at Beginner level');
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to add skill');
    }
}

// UPDATE endpoint REMOVED - Proficiency levels can only be changed by passing proficiency tests
// Use /proficiency_tests.php to upgrade skill levels

// DELETE a user skill
if ($method === 'DELETE' && $action === 'delete') {
    requireAuth();
    $skillId = $_GET['skill_id'] ?? null;
    if (!$skillId) {
        sendResponse('error', 'skill_id required');
    }
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ? AND skill_id = ?");
    try {
        $stmt->execute([$userId, $skillId]);
        if ($stmt->rowCount() > 0) {
            sendResponse('success', 'Skill removed');
        } else {
            sendResponse('error', 'Skill not found');
        }
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to remove skill');
    }
}

sendResponse('error', 'Invalid request');
?>