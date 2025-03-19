<?php
session_start();
require 'db_connect.php';

$response = ["success" => false];

if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $query = "SELECT * FROM organizationDetails WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $response = array_merge(["success" => true], $row);
    }
}

echo json_encode($response);
?>
