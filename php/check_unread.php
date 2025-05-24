<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];
$sql = "SELECT COUNT(*) as unread FROM messages WHERE receiver_id=? AND is_read=0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
echo json_encode(['unread' => $row['unread']]);
