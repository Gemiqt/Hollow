<?php
session_start();
include 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_id = $_POST['product_id'];
    $username   = $_POST['username'];
    $rating     = $_POST['rating'];
    $comment    = $_POST['comment'];
    $stmt = $conn->prepare("
        INSERT INTO reviews (product_id, username, rating, comment)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isis", $product_id, $username, $rating, $comment);

    if ($stmt->execute()) {
        header("Location: product_detail.php?id=" . $product_id . "&review=success");
        exit;
    } else {
        echo "Failed to submit review!";
    }
}
$avgQuery = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = ?");
$avgQuery->bind_param("i", $product_id);
$avgQuery->execute();
$avgResult = $avgQuery->get_result()->fetch_assoc();
$averageRating = $avgResult['avg_rating'];
$update = $conn->prepare("UPDATE products SET rating = ? WHERE id = ?");
$update->bind_param("di", $averageRating, $product_id);
$update->execute();
?>