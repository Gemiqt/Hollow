<?php
session_start();
require "config/database.php";
$email = $_POST["email"];
$password = $_POST["password"];
$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($result);

if ($user) {
    if (password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: homepage.php");
        exit;
    } else {
        echo "<script>alert('Wrong Password'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Email doesn't exist'); window.location='login.php';</script>";
}
?>