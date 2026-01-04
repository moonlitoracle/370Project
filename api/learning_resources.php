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
    requireAuth();
    $stmt = $conn->query("
        SELECT r.res_id, r.name, r.url, r.type,
               s.name AS skill
        FROM resources r
        JOIN skills s ON r.skill_id = s.skill_id
    ");
    sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

/* GET resources for a specific skill */
if ($method === 'GET' && $action === 'by_skill') {
    requireAuth();
    $skillId = $_GET['skill_id'] ?? null;
    if (!$skillId) sendResponse('error', 'skill_id required');

    $stmt = $conn->prepare("
        SELECT name, url, type
        FROM resources
        WHERE skill_id = ?
    ");
    $stmt->execute([$skillId]);
    sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

/* SEARCH resources by skill name */
if ($method === 'GET' && $action === 'search') {
    requireAuth();
    $skillName = $_GET['skill_name'] ?? '';
    
    if (empty($skillName)) {
        // If no search term, return all resources
        $stmt = $conn->query("
            SELECT r.res_id, r.name, r.url, r.type,
                   s.name AS skill
            FROM resources r
            JOIN skills s ON r.skill_id = s.skill_id
        ");
        sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // Search resources where skill name contains the search term
    $stmt = $conn->prepare("
        SELECT r.res_id, r.name, r.url, r.type,
               s.name AS skill
        FROM resources r
        JOIN skills s ON r.skill_id = s.skill_id
        WHERE s.name LIKE ?
    ");
    $stmt->execute(['%' . $skillName . '%']);
    sendResponse('success', 'Resources retrieved', $stmt->fetchAll(PDO::FETCH_ASSOC));
}

sendResponse('error', 'Invalid request');
?>
