<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];
$full_name = $_POST['full_name'];
$username = $_POST['username'];
$address = $_POST['address'];
$dob = $_POST['dob'];

$org_name = isset($_POST['org_name']) ? $_POST['org_name'] : null;
$org_location = isset($_POST['org_location']) ? $_POST['org_location'] : null;
$org_phone = isset($_POST['org_phone']) ? $_POST['org_phone'] : null;

$stmt = $conn->prepare("UPDATE users SET full_name=?, username=?, address=?, dob=?, org_name=?, org_location=?, org_phone=? WHERE id=?");
$stmt->bind_param("sssssssi", $full_name, $username, $address, $dob, $org_name, $org_location, $org_phone, $user_id);

if ($stmt->execute()) {
    header("Location: ../pages/profile.html");
} else {
    echo "Update failed.";
}

$stmt->close();
$conn->close();
