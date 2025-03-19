<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
    $age = $_POST['age'];
    $occupation = $_POST['occupation'];
    $donationType = $_POST['donationType'];
    $country = $_POST['country'];
    $district = $_POST['district'];
    $subDistrict = $_POST['subDistrict'];
    $villageCity = $_POST['villageCity'];

    // Insert user data into the database
    $sql = "INSERT INTO users (userType, firstName, lastName, fullName, username, email, password, age, occupation, donationType, country, district, subDistrict, villageCity)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssss", $userType, $firstName, $lastName, $fullName, $username, $email, $password, $age, $occupation, $donationType, $country, $district, $subDistrict, $villageCity);

    if ($stmt->execute()) {
        echo "Signup successful! <a href='../login.html'>Go to Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

