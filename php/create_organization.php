<?php
session_start();
include 'db_connect.php'; // Include database connection

if (!isset($_SESSION['email'])) {
    die("<h2 style='color:red; text-align:center;'>You must be logged in.</h2>");
}

$user_email = $_SESSION['email'];

// Check if the user is an organization and hasn't already created one
$query = "SELECT userType FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['userType'] !== 'organization') {
    die("<h2 style='color:red; text-align:center;'>You are not allowed to create an organization.</h2>");
}

// Check if the user already has an organization
$query = "SELECT email FROM organizationDetails WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    die("<h2 style='color:red; text-align:center;'>You have already created an organization.</h2>");
}

// Handle form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orgName = $_POST['orgName'];
    $orgDis = $_POST['orgDescription'];
    $donationType = $_POST['donationType'];
    $contactNumber = $_POST['contactNumber'];

    // Insert into database
    $query = "INSERT INTO organizationDetails (orgName, orgDis, donationType, contactNumber, email) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $orgName, $orgDis, $donationType, $contactNumber, $user_email);
    
    if ($stmt->execute()) {
        echo "<h2 style='color:green; text-align:center;'>Organization created successfully!</h2>";
    } else {
        echo "<h2 style='color:red; text-align:center;'>Error creating organization: " . $stmt->error . "</h2>";
    }

    $stmt->close();
}

$conn->close();
?>
