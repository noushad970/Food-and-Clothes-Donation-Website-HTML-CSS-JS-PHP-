<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $user_type = $_POST['user_type'];

    $org_name = $org_location = $org_phone = null;
    if ($user_type === 'organization') {
        $org_name = $_POST['org_name'];
        $org_location = $_POST['org_location'];
        $org_phone = $_POST['org_phone'];
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email already exists.";
        exit;
    }

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password, address, dob, user_type, org_name, org_location, org_phone)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $full_name, $username, $email, $password, $address, $dob, $user_type, $org_name, $org_location, $org_phone);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Insert into organization table
        if ($user_type === 'organization') {
            $stmt2 = $conn->prepare("INSERT INTO organization (user_id, org_name, org_location, org_phone, keyword) VALUES (?, ?, ?, ?, ?)");
            $keyword = strtolower($org_location); // Example keyword, can be modified
            $stmt2->bind_param("issss", $user_id, $org_name, $org_location, $org_phone, $keyword);
            $stmt2->execute();
            $stmt2->close();
        }

        header("Location: ../pages/login.html");
    } else {
        echo "Signup failed. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
