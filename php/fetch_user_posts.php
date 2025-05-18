<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT posts.id, posts.content, posts.created_at, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          WHERE posts.user_id = ? 
          ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
