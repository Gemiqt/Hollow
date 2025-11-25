<?php
session_start();
include 'config/database.php';
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}
try {
    $conn->begin_transaction();
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $qty        = $item['qty'];
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        if (!$product || $product['stock'] < $qty) {
            throw new Exception("Stok tidak cukup untuk produk ID: $product_id");
        }
        $update = $conn->prepare("
            UPDATE products
            SET stock = stock - ?, sold = sold + ?
            WHERE id = ?
        ");
        $update->bind_param("iii", $qty, $qty, $product_id);
        $update->execute();
    }
    unset($_SESSION['cart']);
    $conn->commit();
    header("Location: cart.php?success=1");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo "Checkout gagal: " . $e->getMessage();
    exit;
}
?>