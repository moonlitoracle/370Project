<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Helper function to send JSON response
function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Check if user is logged in
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse('error', 'Unauthorized. Please log in.');
    }
}

// GET user profile
if ($method === 'GET') {
    requireAuth();
    
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT user_id, name, email, level, created_at FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        sendResponse('success', 'User profile retrieved', $user);
    } else {
        sendResponse('error', 'User not found');
    }
}

// PUT/POST - Update user profile
if ($method === 'PUT' || $method === 'POST') {
    requireAuth();
    
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];
    
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    
    // Validation
    if (empty($name)) {
        sendResponse('error', 'Name is required');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse('error', 'Valid email is required');
    }
    
    // Check if email is already taken by another user
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        sendResponse('error', 'Email already in use by another account');
    }
    
    // Update user
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
