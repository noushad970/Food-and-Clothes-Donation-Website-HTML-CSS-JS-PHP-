<?php
require 'db.php';
session_start();

$receiver = intval($_GET['receiver_id']);
$user = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT sender_id, message FROM messages 
                        WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) 
                        ORDER BY created_at");
$stmt->bind_param("iiii", $user, $receiver, $receiver, $user);
$stmt->execute();
$res = $stmt->get_result();

$messages = [];
while ($row = $res->fetch_assoc()) {
  $messages[] = [
    'message' => $row['message'],
    'is_sender' => $row['sender_id'] == $user
  ];
}
echo json_encode($messages);