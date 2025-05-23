<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];

if (isset($_FILES['new_profile_picture']) && $_FILES['new_profile_picture']['error'] === 0) {
    $upload_dir = "../uploads/";
    if (!file_exists($upload_dir)) mkdir($upload_dir);
    $filename = uniqid() . "_" . basename($_FILES["new_profile_picture"]["name"]);
    $target_file = $upload_dir . $filename;
    if (move_uploaded_file($_FILES["new_profile_picture"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../pages/profile.html");
        exit;
    }
}

echo "Failed to update profile picture.";
?>
