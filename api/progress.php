<?php


session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

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

if ($method === 'GET') {
    requireAuth();
    $userId = $_SESSION['user_id'];

    // Skills tracked (from insights)
    $skills = $conn->prepare("SELECT COUNT(*) FROM user_skills WHERE user_id = ?");
    $skills->execute([$userId]);
    $skillsCount = $skills->fetchColumn();

    // Skill proficiency breakdown
    $profStmt = $conn->prepare("
        SELECT proficiency, COUNT(*) as count
        FROM user_skills
        WHERE user_id = ?
        GROUP BY proficiency
    ");
    $profStmt->execute([$userId]);
    $proficiencies = $profStmt->fetchAll(PDO::FETCH_ASSOC);

    // Goals (from insights)
    $goals = $conn->prepare("SELECT COUNT(*) FROM goals WHERE user_id = ?");
    $goals->execute([$userId]);
    $goalsTotal = $goals->fetchColumn();

    $completed = $conn->prepare("SELECT COUNT(*) FROM goals WHERE user_id = ? AND status = 'completed'");
    $completed->execute([$userId]);
    $goalsCompleted = $completed->fetchColumn();

    // Milestone completion rate
    $milestonesTotal = $conn->prepare("
        SELECT COUNT(*) FROM milestones m
        JOIN goals g ON m.goal_id = g.goal_id
        WHERE g.user_id = ?
    ");
    $milestonesTotal->execute([$userId]);
    $milestonesTotalCount = $milestonesTotal->fetchColumn();

    $milestonesCompleted = $conn->prepare("
        SELECT COUNT(*) FROM milestones m
        JOIN goals g ON m.goal_id = g.goal_id
        WHERE g.user_id = ? AND m.status = 'completed'
    ");
    $milestonesCompleted->execute([$userId]);
    $milestonesCompletedCount = $milestonesCompleted->fetchColumn();

    $milestoneCompletionRate = $milestonesTotalCount > 0 ? round(($milestonesCompletedCount / $milestonesTotalCount) * 100, 2) : 0;

    // User level (assuming level in users table indicates growth)
    $levelStmt = $conn->prepare("SELECT level FROM users WHERE user_id = ?");
    $levelStmt->execute([$userId]);
    $userLevel = $levelStmt->fetchColumn();

    // Note: For true "growth" tracking, we'd need timestamps on proficiency changes or logs, but schema doesn't have it.
    // Assuming current state as proxy for growth.

    sendResponse('success', 'Progress and growth tracked', [
        'skills_tracked' => $skillsCount,
        'skill_proficiencies' => $proficiencies,
        'goals_total' => $goalsTotal,
        'goals_completed' => $goalsCompleted,
        'milestones_completion_rate' => $milestoneCompletionRate,
        'user_level' => $userLevel
    ]);
}

sendResponse('error', 'Invalid request');
?>