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

// LIST all careers
if ($method === 'GET' && $action === 'list') {
    requireAuth();
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT 
            c.career_id, c.name, c.overview,
            CASE WHEN uc.user_id IS NOT NULL THEN 1 ELSE 0 END as is_selected
        FROM careers c
        LEFT JOIN user_careers uc ON c.career_id = uc.career_id AND uc.user_id = ?
    ");
    $stmt->execute([$userId]);
    $careers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    sendResponse('success', 'Careers retrieved', $careers);
}

// GET career details including required skills
if ($method === 'GET' && $action === 'details') {
    $careerId = $_GET['id'] ?? null;
    if (!$careerId) {
        sendResponse('error', 'Career ID required');
    }
    // Career info
    $stmt = $conn->prepare("SELECT career_id, name, overview FROM careers WHERE career_id = ?");
    $stmt->execute([$careerId]);
    $career = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$career) {
        sendResponse('error', 'Career not found');
    }
    // Check if selected
    $userId = $_SESSION['user_id'];
    $check = $conn->prepare("SELECT * FROM user_careers WHERE user_id = ? AND career_id = ?");
    $check->execute([$userId, $careerId]);
    $career['is_selected'] = $check->fetch() ? true : false;

    // Required skills for this career
    $stmt = $conn->prepare("SELECT s.skill_id, s.name, s.description, cs.level FROM career_skills cs JOIN skills s ON cs.skill_id = s.skill_id WHERE cs.career_id = ?");
    $stmt->execute([$careerId]);
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $career['required_skills'] = $skills;
    sendResponse('success', 'Career details retrieved', $career);
}

// USER selects a career (adds to user_careers)
if ($method === 'POST' && $action === 'select') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $careerId = $input['career_id'] ?? null;
    if (!$careerId) {
        sendResponse('error', 'career_id is required');
    }
    $userId = $_SESSION['user_id'];
    // Check if already selected
    $stmt = $conn->prepare("SELECT * FROM user_careers WHERE user_id = ? AND career_id = ?");
    $stmt->execute([$userId, $careerId]);
    if ($stmt->fetch()) {
        sendResponse('error', 'Career already selected');
    }
    // Insert selection
    $stmt = $conn->prepare("INSERT INTO user_careers (user_id, career_id) VALUES (?, ?)");
    try {
        $stmt->execute([$userId, $careerId]);
        sendResponse('success', 'Career selected', ['user_id' => $userId, 'career_id' => $careerId]);
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to select career');
    }
}

sendResponse('error', 'Invalid request');
?>
