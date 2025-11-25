<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="products.css">
  <title>Our Products</title>
</head>
<body>
<?php include 'header.php'; ?>
<?php 
include "config/database.php";
$q_ready = "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC";
$ready = mysqli_query($conn, $q_ready);
$q_sold = "SELECT * FROM products WHERE stock = 0 ORDER BY id DESC";
$sold = mysqli_query($conn, $q_sold);
?>
<a href="homepage.php" class="btn-back">← Back to Home</a>
<div class="products-container">
  <h2 class="section-title">Our Products</h2>
  <div class="product-grid">
    <?php while ($p = mysqli_fetch_assoc($ready)) : ?>
      <a href="product_detail.php?id=<?= $p['id']; ?>" class="card-link">
        <div class="product-card">
          <img src="<?= $p['image']; ?>" class="product-img">
          <h3 class="product-title"><?= $p['name']; ?></h3>
          <p class="product-price">Rp <?= number_format($p['price'],0,',','.'); ?></p>
          <p class="product-desc"><?= substr($p['description'], 0, 50) . '...'; ?></p>
        </div>
      </a>
    <?php endwhile; ?>
  </div>
  <?php if (mysqli_num_rows($sold) > 0) : ?>
    <h2 class="section-title" style="margin-top:50px;">Sold Out Products</h2>
    <div class="product-grid">
      <?php while ($p = mysqli_fetch_assoc($sold)) : ?>
        <div class="product-card sold-out-card">
          <img src="<?= $p['image']; ?>" class="product-img">
          <div class="sold-out-badge">SOLD OUT</div>
          <h3 class="product-title"><?= $p['name']; ?></h3>
          <p class="product-price">Rp <?= number_format($p['price'],0,',','.'); ?></p>
          <p class="product-desc"><?= substr($p['description'], 0, 50) . '...'; ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>
<script src="products.js"></script>
<?php include 'footer.php' ?>
</body>
</html>