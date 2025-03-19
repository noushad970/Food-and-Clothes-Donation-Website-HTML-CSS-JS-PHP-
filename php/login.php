<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists by username or email
    $query = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            echo "<script>alert('Login Successful!'); window.location.href='../index.html';</script>";
        } else {
            echo "<script>alert('Wrong password!'); window.location.href='../login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.href='../login.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
