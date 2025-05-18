<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../pages/home.html");
    exit();
}
?>
