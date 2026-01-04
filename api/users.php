<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Send JSON response
function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Check auth
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse('error', 'Unauthorized. Please log in.');
    }
}

// Get profile
if ($method === 'GET') {
    requireAuth();
    
    $userId = $_SESSION['user_id'];
    
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("
        SELECT 
            u.user_id, u.name, u.email, u.level, u.created_at,
            (SELECT COUNT(*) FROM user_skills WHERE user_id = u.user_id) as skills_tracked,
            (SELECT COUNT(*) FROM goals WHERE user_id = u.user_id AND status = 'completed') as goals_completed,
            (SELECT COUNT(*) FROM goals WHERE user_id = u.user_id) as goals_total
        FROM users u 
        WHERE u.user_id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        sendResponse('success', 'User profile retrieved', $user);
    } else {
        sendResponse('error', 'User not found');
    }
}

// Update profile
if ($method === 'PUT' || $method === 'POST') {
    requireAuth();
    
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];
    
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    

    if (empty($name)) {
        sendResponse('error', 'Name is required');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse('error', 'Valid email is required');
    }
    
    // Check email availability
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        sendResponse('error', 'Email already in use by another account');
    }
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
    
    try {
        $stmt->execute([$name, $email, $userId]);
        
        // Update session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        sendResponse('success', 'Profile updated successfully', [
            'user_id' => $userId,
            'name' => $name,
            'email' => $email
        ]);
    } catch (PDOException $e) {
        sendResponse('error', 'Update failed');
    }
}

sendResponse('error', 'Invalid request method');
?>
