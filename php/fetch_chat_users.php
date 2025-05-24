<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT users.id, users.username
        FROM users 
        JOIN messages  ON (users.id = messages.sender_id OR users.id = messages.receiver_id)
        WHERE users.id != ? AND (messages.sender_id = ? OR messages.receiver_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

$users = [];
while ($row = $res->fetch_assoc()) {
  $users[] = $row;
}
echo json_encode($users);
