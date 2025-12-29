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

/* Evaluate career readiness */
if ($method === 'GET' && $action === 'evaluate') {
    requireAuth();
    $careerId = $_GET['career_id'] ?? null;
    if (!$careerId) sendResponse('error', 'career_id required');

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT s.name,
               cs.level AS required_level,
               IFNULL(us.level, 0) AS user_level
        FROM career_skills cs
        JOIN skills s ON cs.skill_id = s.skill_id
        LEFT JOIN user_skills us
            ON us.skill_id = s.skill_id AND us.user_id = ?
        WHERE cs.career_id = ?
    ");
    $stmt->execute([$userId, $careerId]);
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = count($skills);
    $matched = 0;
    $gaps = [];

    foreach ($skills as $s) {
        if ($s['user_level'] >= $s['required_level']) {
            $matched++;
        } else {
            $gaps[] = $s;
        }
    }

    $percentage = $total > 0 ? round(($matched / $total) * 100, 2) : 0;

    sendResponse('success', 'Career readiness evaluated', [
        'match_percentage' => $percentage,
        'missing_skills' => $gaps
    ]);
}

sendResponse('error', 'Invalid request');
?>
