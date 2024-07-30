<?php
require '../../config/config.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$findtoken = $data['findtoken'] ?? '';
$authToken = $data['token'] ?? '';

// Validate authentication token
$stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$stmt->execute([$authToken]);
$authUser = $stmt->fetch();

if ($authUser) {
    if ($findtoken) {
        // Find the user with the given findtoken
        $stmt = $pdo->prepare("SELECT id, fullname, token FROM users WHERE token = ?");
        $stmt->execute([$findtoken]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'failure', 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'Findtoken is required']);
    }
} else {
    echo json_encode(['status' => 'failure', 'message' => 'Invalid token']);
}
?>
