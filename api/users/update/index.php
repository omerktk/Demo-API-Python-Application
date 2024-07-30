<?php
require '../../config/config.php';

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve and validate required fields
$token = $data['token'] ?? '';
$id = $data['id'] ?? '';
$fullname = $data['fullname'] ?? '';

// Check if all required fields are provided
if (!$token || !$id || !$fullname) {
    echo json_encode(['status' => 'error', 'message' => '"User ID or new full name missing']);
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

// Update user in the database
$stmt = $pdo->prepare("UPDATE users SET fullname = ? WHERE id = ?");
$result = $stmt->execute([$fullname, $id]);

if ($result) {
    
    echo json_encode(['status' => 'success', 'message' => '"User updated successfully']);
} else {

    echo json_encode(['status' => 'error', 'message' => '"User ID or new full name missing']);
    echo json_encode(['error' => 'Failed to update user']);
}
?>
