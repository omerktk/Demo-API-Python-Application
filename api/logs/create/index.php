<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$log = $data['log'] ?? '';
$datetime = $data['datetime'] ?? '';
$token = $data['token'] ?? '';

// Validate token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    if ($log && $datetime) {
        $stmt = $pdo->prepare("INSERT INTO logs (log, datetime, token) VALUES (?, ?, ?)");
        $stmt->execute([$log, $datetime, $token]);
        echo json_encode(['status' => 'success', 'message' => 'Log created successfully']);
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'Log and datetime are required']);
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
 
