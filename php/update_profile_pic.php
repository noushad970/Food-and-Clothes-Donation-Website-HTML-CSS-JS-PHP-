<?php
session_start();
require 'db_connect.php';

$user_id = $_SESSION['user_id'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);

if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
    $query = "UPDATE users SET profile_pic=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $target_file, $user_id);
    $stmt->execute();
    echo json_encode(["success" => true, "profile_pic" => $target_file, "message" => "Profile picture updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to upload"]);
}
?>
