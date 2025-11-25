<?php
session_start();
require 'config/database.php';
if (!isset($_GET['id'])) {
    die("Product not found");
}
$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$ratingStmt = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE product_id = ?");
$ratingStmt->bind_param("i", $product_id);
$ratingStmt->execute();
$ratingData = $ratingStmt->get_result()->fetch_assoc();
$avg_rating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 1) : 0;
$total_reviews = $ratingData['total_reviews'];
$reviewStmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$reviewStmt->bind_param("i", $product_id);
$reviewStmt->execute();
$reviews = $reviewStmt->get_result();
$recommendStmt = $conn->prepare("
    SELECT id, name, price, image 
    FROM products
    WHERE id != ?
    ORDER BY RAND()
    LIMIT 4
");
$recommendStmt->bind_param("i", $product_id);
$recommendStmt->execute();
$recommendedProducts = $recommendStmt->get_result();

if (!$product) {
    die("Product not found");
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $qty = intval($_POST["qty"]);
    if ($qty > $product['stock']) {
        $qty = $product['stock'];
        echo "<script>alert('Jumlah melebihi stok! Jumlah otomatis disesuaikan ke maksimum stok.');</script>";
    }
    if ($qty < 1) {
        $qty = 1;
    }
    $_SESSION["cart"][$product_id] = [
        "id" => $product["id"],
        "name" => $product["name"],
        "price" => $product["price"],
        "qty" => $qty,
        "size" => $_POST["size"],
        "image" => $product["image"]
    ];
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $product['name']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="product_detail.css">
</head>
<body>
<?php include 'header.php' ?>
<div class="detail-container">
    <div class="image-section">
        <img id="mainImage" src="<?= $product['image']; ?>" class="main-image">
    </div>
    <div class="info-section">
        <h2 class="product-name"><?= $product['name']; ?></h2>
        <div class="rating">
            ⭐ <?= $avg_rating ?> 
            (<?= $total_reviews ?> reviews)
            | Sold <?= number_format($product['sold']); ?>
        </div>
        <div class="price">Rp<?= number_format($product['price'], 0, ',', '.'); ?></div>
        <div class="stock-info">
            Stock Available: <?= $product['stock'] ?>
        </div>
        <p class="description"><?= nl2br($product['description']); ?></p>
        <form method="POST">
        <label class="label-title">Choose Size:</label>
    <div class="size-options">
        <label class="size-pill">
            <input type="radio" name="size" value="S" required>
            <span>S</span>
        </label>
        <label class="size-pill">
            <input type="radio" name="size" value="M">
            <span>M</span>
        </label>
        <label class="size-pill">
            <input type="radio" name="size" value="L">
            <span>L</span>
        </label>
        <label class="size-pill">
            <input type="radio" name="size" value="XL">
            <span>XL</span>
        </label>
        <label class="size-pill">
            <input type="radio" name="size" value="XXL">
            <span>XXL</span>
        </label>
    </div>
    <button type="button" id="open-size-chart" class="size-chart-btn-modern">
        Size Chart
    </button>
    <label class="label-title">Quantity:</label>
    <div class="qty-box">
        <button type="button" class="qty-btn" id="minusQty">−</button>
        <input type="number" id="qtyInput" name="qty" 
       value="1" min="1" max="<?= $product['stock'] ?>">
        <button type="button" class="qty-btn" id="plusQty">+</button>
    </div>
            <div class="button-group">
                <button type="submit" name="add_to_cart" class="add-cart">Add to Cart</button>
                <button type="button" class="buy-now">Buy Now</button>
            </div>
        </form>
    </div>
</div>
<div id="size-chart-popup" class="popup-overlay">
    <div class="popup-box">
        <h3>Size Chart</h3>
        <table class="size-table">
            <tr><th>Size</th><th>Chest Widht (cm)</th><th>Body Length (cm)</th></tr>
            <tr><td>S</td><td>48</td><td>68</td></tr>
            <tr><td>M</td><td>50</td><td>70</td></tr>
            <tr><td>L</td><td>52</td><td>72</td></tr>
            <tr><td>XL</td><td>54</td><td>74</td></tr>
            <tr><td>XXL</td><td>56</td><td>76</td></tr>
        </table>
        <button id="close-size-chart" class="close-popup">Close</button>
    </div>
</div>
<h3 style="margin-top:40px;">Write a Review</h3>
<form action="submit_review.php" method="POST" class="review-form">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <label>Your Name:</label>
    <input type="text" name="username" required placeholder="Enter your name">
    <label>Rating:</label>
    <select name="rating" required>
        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
        <option value="4">⭐⭐⭐⭐ (4)</option>
        <option value="3">⭐⭐⭐ (3)</option>
        <option value="2">⭐⭐ (2)</option>
        <option value="1">⭐ (1)</option>
    </select>
    <label>Your Review:</label>
    <textarea name="comment" required placeholder="Write something..." rows="4"></textarea>
    <button type="submit" class="submit-review-btn">Submit Review</button>
</form>
<h3>Customer Reviews</h3>
<?php if ($reviews->num_rows == 0): ?>
    <p>No reviews yet.</p>
<?php else: ?>
    <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review-box">
            <b><?= $r['username'] ?></b> ⭐<?= $r['rating'] ?><br>
            <p><?= nl2br($r['comment']) ?></p>
            <small><?= $r['created_at'] ?></small>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<h3 style="margin-top:40px; margin-bottom:15px;">Recommended Products</h3>
<div class="recommended-grid">
    <?php while ($p = $recommendedProducts->fetch_assoc()): ?>
        <a href="product_detail.php?id=<?= $p['id']; ?>" class="recommended-card">
            <div class="recommended-img-box">
                <img src="<?= $p['image']; ?>" alt="<?= $p['name']; ?>">
            </div>
            <div class="recommended-info">
                <div class="recommended-name"><?= $p['name']; ?></div>
                <div class="recommended-price">
                    Rp<?= number_format($p['price'], 0, ',', '.'); ?>
                </div>
            </div>
        </a>
    <?php endwhile; ?>
</div>
<script src="product_detail.js"></script>
</body>
</html>