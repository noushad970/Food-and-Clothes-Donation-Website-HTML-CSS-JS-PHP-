<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "UPDATE users SET firstName=?, lastName=?, age=?, occupation=?, donationType=?, country=?, district=?, subDistrict=?, villageCity=? WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssissssssi", $_POST['first_name'], $_POST['last_name'], $_POST['age'], $_POST['occupation'], $_POST['donation_type'], $_POST['country'], $_POST['district'], $_POST['sub_district'], $_POST['village_city'], $user_id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
$stmt->close();
$conn->close();
?>
