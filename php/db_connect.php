<?php
$servername = "localhost";
$username = "root"; // Change if your database username is different
$password = ""; // Add your database password if required
$database = "donation_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>