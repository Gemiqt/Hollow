<?php
require "config/database.php";
$name = $_POST["name"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('This email is already registered'); window.location='login.php';</script>";
    exit;
}
$query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
if (mysqli_query($conn, $query)) {
    echo "<script>alert('Your account has been created! Please login.'); window.location='login.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>