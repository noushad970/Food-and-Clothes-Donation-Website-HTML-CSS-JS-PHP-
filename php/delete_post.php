<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Post deleted successfully.";
    } else {
        echo "Failed to delete post.";
    }

    $stmt->close();
    $conn->close();
}
?>
