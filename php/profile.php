<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.html");
  exit();
}

$id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Display profile
echo "<p><strong>Full Name:</strong> {$user['fullname']}</p>";
echo "<p><strong>Username:</strong> {$user['username']}</p>";
echo "<p><strong>Email:</strong> {$user['gmail']}</p>";
echo "<p><strong>Address:</strong> {$user['address']}</p>";
echo "<p><strong>Date of Birth:</strong> {$user['dob']}</p>";
?>
