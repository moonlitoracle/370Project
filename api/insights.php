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

    $skills = $conn->prepare("SELECT COUNT(*) FROM user_skills WHERE user_id = ?");
    $skills->execute([$userId]);

    $goals = $conn->prepare("SELECT COUNT(*) FROM goals WHERE user_id = ?");
    $goals->execute([$userId]);

    $completed = $conn->prepare("
        SELECT COUNT(*) FROM goals
        WHERE user_id = ? AND status = 'completed'
    ");
    $completed->execute([$userId]);

    sendResponse('success', 'Insights generated', [
        'skills_tracked' => $skills->fetchColumn(),
        'goals_total' => $goals->fetchColumn(),
        'goals_completed' => $completed->fetchColumn()
    ]);
}

sendResponse('error', 'Invalid request');
?>

