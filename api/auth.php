<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Helper function to send JSON response
function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// REGISTER
if ($method === 'POST' && $action === 'register') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        sendResponse('error', 'All fields are required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse('error', 'Invalid email format');
    }
    
    if (strlen($password) < 6) {
        sendResponse('error', 'Password must be at least 6 characters');
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendResponse('error', 'Email already registered');
    }
    
    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    try {
        $stmt->execute([$name, $email, $hashedPassword]);
        sendResponse('success', 'Registration successful', ['user_id' => $conn->lastInsertId()]);
    } catch (PDOException $e) {
        sendResponse('error', 'Registration failed');
    }
}

// LOGIN
if ($method === 'POST' && $action === 'login') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        sendResponse('error', 'Email and password are required');
    }
    
    $stmt = $conn->prepare("SELECT user_id, name, email, password, level FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($password, $user['password'])) {
        sendResponse('error', 'Invalid email or password');
    }
    
    // Set session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_level'] = $user['level'];
    
    unset($user['password']); // Don't send password back
    sendResponse('success', 'Login successful', $user);
}

// LOGOUT
if ($method === 'POST' && $action === 'logout') {
    session_destroy();
    sendResponse('success', 'Logout successful');
}

// CHECK SESSION
if ($method === 'GET' && $action === 'check') {
    if (isset($_SESSION['user_id'])) {
        sendResponse('success', 'User is logged in', [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'level' => $_SESSION['user_level']
        ]);
    } else {
        sendResponse('error', 'Not logged in');
    }
}

sendResponse('error', 'Invalid request');
?>
