<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    require 'db.php';

    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();

    echo json_encode([
        'loggedIn' => true,
        'username' => $username
    ]);
} else {
    echo json_encode([
        'loggedIn' => false
    ]);
}
?>
