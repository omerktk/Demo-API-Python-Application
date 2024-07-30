<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
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
    if ($id && $from && $to && $type && $amount && $datetime) {
        $stmt = $pdo->prepare("UPDATE transactions SET `from` = ?, `to` = ?, type = ?, amount = ?, datetime = ? WHERE id = ?");
        $stmt->execute([$from, $to, $type, $amount, $datetime, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Transaction updated successfully']);
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'All fields are required']);
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
 
