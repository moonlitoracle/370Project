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

/* LIST all resources */
if ($method === 'GET' && $action === 'list') {
    $stmt = $conn->query("
        SELECT r.resource_id, r.title, r.url,
               s.name AS skill,
               rt.name AS type
        FROM resources r
        JOIN skills s ON r.skill_id = s.skill_id
        JOIN resource_types rt ON r.type_id = rt.type_id
    ");
    sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

/* GET resources for a specific skill */
if ($method === 'GET' && $action === 'by_skill') {
    $skillId = $_GET['skill_id'] ?? null;
    if (!$skillId) sendResponse('error', 'skill_id required');

    $stmt = $conn->prepare("
        SELECT title, url
        FROM resources
        WHERE skill_id = ?
    ");
    $stmt->execute([$skillId]);
    sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

sendResponse('error', 'Invalid request');
?>
