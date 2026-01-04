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

// ---------- Goals ---------- //
// List all goals for current user
if ($method === 'GET' && $action === 'list') {
    requireAuth();
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT goal_id, title, deadline, status FROM goals WHERE user_id = ?");
    $stmt->execute([$userId]);
    $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    sendResponse('success', 'Goals retrieved', $goals);
}

// Get a single goal with its milestones
if ($method === 'GET' && $action === 'detail') {
    requireAuth();
    $goalId = $_GET['id'] ?? null;
    if (!$goalId) { sendResponse('error', 'Goal ID required'); }
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT goal_id, title, deadline, status FROM goals WHERE goal_id = ? AND user_id = ?");
    $stmt->execute([$goalId, $userId]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$goal) { sendResponse('error', 'Goal not found'); }
    // Milestones
    $stmt = $conn->prepare("SELECT milestone_id, title, status FROM milestones WHERE goal_id = ?");
    $stmt->execute([$goalId]);
    $milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $goal['milestones'] = $milestones;
    sendResponse('success', 'Goal details retrieved', $goal);
}

// Create a new goal
if ($method === 'POST' && $action === 'create') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? '');
    $deadline = $input['deadline'] ?? null; // expect YYYY-MM-DD
    $status = $input['status'] ?? 'pending';
    if (empty($title) || empty($deadline)) {
        sendResponse('error', 'Title and deadline are required');
    }
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO goals (user_id, title, deadline, status) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$userId, $title, $deadline, $status]);
        $goalId = $conn->lastInsertId();
        sendResponse('success', 'Goal created', ['goal_id' => $goalId]);
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to create goal');
    }
}

// Update an existing goal (PUT or POST with action=update)
if (($method === 'PUT' || $method === 'POST') && $action === 'update') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $goalId = $input['goal_id'] ?? null;
    if (!$goalId) { sendResponse('error', 'goal_id required'); }
    $fields = [];
    $params = [];
    if (isset($input['title'])) { $fields[] = 'title = ?'; $params[] = $input['title']; }
    if (isset($input['deadline'])) { $fields[] = 'deadline = ?'; $params[] = $input['deadline']; }
    if (isset($input['status'])) { $fields[] = 'status = ?'; $params[] = $input['status']; }
    if (empty($fields)) { sendResponse('error', 'No fields to update'); }
    $params[] = $_SESSION['user_id'];
    $params[] = $goalId;
    $sql = "UPDATE goals SET " . implode(', ', $fields) . " WHERE user_id = ? AND goal_id = ?";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($params);
        
        // TRIGGER LEVEL UPDATE IF COMPLETED
        if (isset($input['status']) && $input['status'] === 'completed') {
            require_once 'utils.php';
            updateUserLevel($_SESSION['user_id'], $conn);
        }

        sendResponse('success', 'Goal updated');
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to update goal');
    }
}

// Delete a goal (cascades milestones via foreign key ON DELETE CASCADE)
if ($method === 'DELETE' && $action === 'delete') {
    requireAuth();
    $goalId = $_GET['id'] ?? null;
    if (!$goalId) { sendResponse('error', 'Goal ID required'); }
    $stmt = $conn->prepare("DELETE FROM goals WHERE goal_id = ? AND user_id = ?");
    try {
        $stmt->execute([$goalId, $_SESSION['user_id']]);
        sendResponse('success', 'Goal deleted');
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to delete goal');
    }
}

// ---------- Milestones ---------- //
// Add a milestone to a goal
if ($method === 'POST' && $action === 'add_milestone') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $goalId = $input['goal_id'] ?? null;
    $title = trim($input['title'] ?? '');
    $status = $input['status'] ?? 'pending';
    if (!$goalId || empty($title)) { sendResponse('error', 'goal_id and title required'); }
    // Verify goal belongs to user
    $stmt = $conn->prepare("SELECT goal_id FROM goals WHERE goal_id = ? AND user_id = ?");
    $stmt->execute([$goalId, $_SESSION['user_id']]);
    if (!$stmt->fetch()) { sendResponse('error', 'Goal not found or unauthorized'); }
    $stmt = $conn->prepare("INSERT INTO milestones (goal_id, title, status) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$goalId, $title, $status]);
        $mid = $conn->lastInsertId();
        sendResponse('success', 'Milestone added', ['milestone_id' => $mid]);
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to add milestone');
    }
}

// Update a milestone
if (($method === 'PUT' || $method === 'POST') && $action === 'update_milestone') {
    requireAuth();
    $input = json_decode(file_get_contents('php://input'), true);
    $milestoneId = $input['milestone_id'] ?? null;
    if (!$milestoneId) { sendResponse('error', 'milestone_id required'); }
    $fields = [];
    $params = [];
    if (isset($input['title'])) { $fields[] = 'title = ?'; $params[] = $input['title']; }
    if (isset($input['status'])) { $fields[] = 'status = ?'; $params[] = $input['status']; }
    if (empty($fields)) { sendResponse('error', 'No fields to update'); }
    // Ensure milestone belongs to user's goal
    $stmt = $conn->prepare("SELECT m.milestone_id FROM milestones m JOIN goals g ON m.goal_id = g.goal_id WHERE m.milestone_id = ? AND g.user_id = ?");
    $stmt->execute([$milestoneId, $_SESSION['user_id']]);
    if (!$stmt->fetch()) { sendResponse('error', 'Milestone not found or unauthorized'); }
    $params[] = $milestoneId;
    $sql = "UPDATE milestones SET " . implode(', ', $fields) . " WHERE milestone_id = ?";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($params);
        sendResponse('success', 'Milestone updated');
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to update milestone');
    }
}

// Delete a milestone
if ($method === 'DELETE' && $action === 'delete_milestone') {
    requireAuth();
    $mid = $_GET['id'] ?? null;
    if (!$mid) { sendResponse('error', 'Milestone ID required'); }
    // Verify ownership
    $stmt = $conn->prepare("SELECT m.milestone_id FROM milestones m JOIN goals g ON m.goal_id = g.goal_id WHERE m.milestone_id = ? AND g.user_id = ?");
    $stmt->execute([$mid, $_SESSION['user_id']]);
    if (!$stmt->fetch()) { sendResponse('error', 'Milestone not found or unauthorized'); }
    $stmt = $conn->prepare("DELETE FROM milestones WHERE milestone_id = ?");
    try {
        $stmt->execute([$mid]);
        sendResponse('success', 'Milestone deleted');
    } catch (PDOException $e) {
        sendResponse('error', 'Failed to delete milestone');
    }
}

sendResponse('error', 'Invalid request');
?>
