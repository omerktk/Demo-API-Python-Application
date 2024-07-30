<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$token = $data['token'] ?? '';

// Validate token and get the user associated with the token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

// Check if the user with the token exists
if ($user) {
    $userId = $user['id'];

    // Ensure that the token does not belong to the user being deleted
    if ($id == $userId) {
        echo json_encode(['status' => 'failure', 'message' => 'Cannot delete your own account']);
    } else {
        if ($id) {
            // Proceed with deletion if the user is not deleting themselves
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['status' => 'failure', 'message' => 'ID is required']);
        }
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
