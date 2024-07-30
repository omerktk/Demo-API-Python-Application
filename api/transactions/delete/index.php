<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$token = $data['token'] ?? '';

// Validate token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Transaction deleted successfully']);
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'Transaction ID missing']);
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
 
