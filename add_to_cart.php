<?php
session_start();
include "config/database.php";
$id = $_GET['id'] ?? null;
if(!$id){
    header("Location: products.php");
    exit;
}
$q = $conn->prepare("SELECT * FROM products WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$product = $q->get_result()->fetch_assoc();
if(!$product){
    header("Location: products.php");
    exit;
}
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}
$found = false;
foreach($_SESSION['cart'] as &$item){
    if($item['id'] == $id){
        $item['qty']++;
        $found = true;
        break;
    }
}
if(!$found){
    $_SESSION['cart'][] = [
        'id'    => $product['id'],
        'name'  => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty'   => 1
    ];
}
header("Location: products.php");
exit;
?>