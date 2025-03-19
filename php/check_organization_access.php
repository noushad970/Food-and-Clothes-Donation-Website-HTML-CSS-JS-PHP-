<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

//$email = $_SESSION['user_email'];

$email = $_SESSION['email']; 

// Retrieve the email from the 'users' table based on the session email
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$stmt->bind_result($userEmail);
$stmt->fetch();

// Use the fetched email from the 'users' table
$email = $_POST[$userEmail];  // Now $email is set with the value from the users table

$orgName = $_POST['orgName'];
$orgDescription = $_POST['orgDescription'];
$donationType = $_POST['donationType'];
$contactNumber = $_POST['contactNumber'];
$orgImage = "";

if (!empty($_FILES['orgImage']['name'])) {
    $orgImage = "uploads/organization_images/" . time() . "_" . $_FILES['orgImage']['name'];
    move_uploaded_file($_FILES['orgImage']['tmp_name'], "../" . $orgImage);
}

$query = "INSERT INTO organizationDetails (user_email, orgName, orgDescription, donationType, contactNumber, orgImage) 
          VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE orgName=?, orgDescription=?, donationType=?, contactNumber=?, orgImage=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssss", $email, $orgName, $orgDescription, $donationType, $contactNumber, $orgImage, $orgName, $orgDescription, $donationType, $contactNumber, $orgImage);

echo json_encode(["success" => $stmt->execute()]);
?>
