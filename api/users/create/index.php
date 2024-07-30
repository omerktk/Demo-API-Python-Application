<?php
require '../../config/config.php';

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);

// Check for valid token
$token = $data['token'] ?? '';
$fullname = $data['fullname'] ?? '';
$newToken = $data['newtoken'] ?? '';

// Ensure all required fields are provided
if (!$token || !$fullname || !$newToken) {

    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Validate the token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$tokenExists = $stmt->fetchColumn();

if (!$tokenExists) {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
    exit;
}

// Insert new user into the database
$stmt = $pdo->prepare("INSERT INTO users (fullname, token) VALUES (?, ?)");
$result = $stmt->execute([$fullname, $newToken]);

if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
} else {
     echo json_encode(['status' => 'error', 'message' => 'Failed to create user']);
}
?>
