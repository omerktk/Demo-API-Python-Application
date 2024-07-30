<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$from = $data['from'] ?? '';
$to = $data['to'] ?? '';
$type = $data['type'] ?? '';
$amount = $data['amount'] ?? '';
$datetime = $data['datetime'] ?? '';
$token = $data['token'] ?? '';

// Validate token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    if ($from && $to && $type && $amount && $datetime) {
        $stmt = $pdo->prepare("INSERT INTO transactions (`from`, `to`, type, amount, datetime) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$from, $to, $type, $amount, $datetime]);
        echo json_encode(['status' => 'success', 'message' => 'Transaction created successfully']);
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'All fields are required']);
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
 
