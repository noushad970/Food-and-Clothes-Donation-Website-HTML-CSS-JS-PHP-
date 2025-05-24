<?php
require 'db.php';

// user info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $user_type = $_POST['user_type'];
    
    $org_name=null;
    $org_location=null;
    $org_phone=null;
    $org_image = null;

    // Handle organization-specific data
    if ($user_type == 'organization') {
        $org_name = $_POST['org_name'];
        $org_location = $_POST['org_location'];
        $org_phone = $_POST['org_phone'];

        if (isset($_FILES['org_image']) && $_FILES['org_image']['error'] === 0) {
            $target_dir = "../uploads/";
            $org_image = time() . "_" . basename($_FILES["org_image"]["name"]);
            $target_file = $target_dir . $org_image;
            move_uploaded_file($_FILES["org_image"]["tmp_name"], $target_file);
        }}
// profile picture info
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $upload_dir = "../uploads/";
        if (!file_exists($upload_dir)) mkdir($upload_dir);
        $filename = uniqid() . "_" . basename($_FILES["profile_picture"]["name"]);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $filename;
        }
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
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password, address, dob, user_type, org_name, org_location, org_phone, profile_picture,org_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $full_name, $username, $email, $password, $address, $dob, $user_type, $org_name, $org_location, $org_phone, $profile_picture,$org_image);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Insert into organization table
        if ($user_type === 'organization') {
            $stmt2 = $conn->prepare("INSERT INTO organization (user_id, org_name, org_location, org_phone, org_image, keyword) VALUES (?, ?, ?, ?, ?, ?)");
            $keyword = strtolower($org_location); // Example keyword, can be modified
            $stmt2->bind_param("isssss", $user_id, $org_name, $org_location, $org_phone, $org_image, $keyword);
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
