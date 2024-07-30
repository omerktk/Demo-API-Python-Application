<?php
require '../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'] ?? '';

// Validate token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
 
