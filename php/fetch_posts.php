<?php
session_start();
require 'db.php';

$query = "SELECT posts.id, posts.content, posts.user_id, posts.created_at, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          ORDER BY posts.created_at DESC";

$result = $conn->query($query);

$posts = [];

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
