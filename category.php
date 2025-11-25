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
  <link rel="stylesheet" href="category.css">
  <title>Category</title>
</head>
<body>
<?php include 'header.php'; ?>
<?php include "config/database.php"; ?>
<?php
$main = $_GET['main'] ?? null;
$sub  = $_GET['sub'] ?? null;
$main_name = null;
$sub_name = null;
if ($main) {
    $q_main = mysqli_query($conn, "SELECT name FROM main_categories WHERE id = $main");
    $d_main = mysqli_fetch_assoc($q_main);
    $main_name = $d_main['name'] ?? null;
}
if ($sub) {
    $q_sub = mysqli_query($conn, "SELECT name FROM sub_categories WHERE id = $sub");
    $d_sub = mysqli_fetch_assoc($q_sub);
    $sub_name = $d_sub['name'] ?? null;
}
$where = [];
if ($main) {
    $where[] = "main_category_id = $main";
}
if ($sub) {
    $where[] = "sub_category_id = $sub";
}
$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}
$q = mysqli_query($conn, "SELECT * FROM products $where_sql ORDER BY id DESC");
$where_sold = "";
if (!empty($where)) {
    $where_sold = $where_sql . " AND stock = 0";
} else {
    $where_sold = "WHERE stock = 0";
}
$q_sold = mysqli_query($conn, "SELECT * FROM products $where_sold ORDER BY id DESC");
?>
<a href="products.php" class="btn-back">← Back to Products</a>
<div class="products-container">
  <h2 class="section-title">
    <?php 
        if ($main_name) echo "Category: $main_name";
        elseif ($sub_name) echo "Category: $sub_name";
        else echo "Filtered Category";
    ?>
  </h2>
  <div class="product-grid">
    <?php while ($p = mysqli_fetch_assoc($q)) : ?>
    <?php if ($p['stock'] > 0): ?>
      <a href="product_detail.php?id=<?= $p['id']; ?>" class="card-link">
        <div class="product-card">
          <img src="<?= $p['image']; ?>" class="product-img">
          <h3 class="product-title"><?= $p['name']; ?></h3>
          <p class="product-price">Rp <?= number_format($p['price'],0,',','.'); ?></p>
          <p class="product-desc"><?= substr($p['description'], 0, 50) . '...'; ?></p>
        </div>
      </a>
    <?php endif; ?>
    <?php endwhile; ?>
  </div>
  <?php if (mysqli_num_rows($q_sold) > 0): ?>
  <h2 class="section-title" style="margin-top:50px;">Sold Out</h2>
  <div class="product-grid">
    <?php while ($p = mysqli_fetch_assoc($q_sold)) : ?>
      <div class="product-card sold-out-card">
        <img src="<?= $p['image']; ?>" class="product-img">
        <div class="sold-out-badge">SOLD OUT</div>
        <h3 class="product-title"><?= $p['name']; ?></h3>
        <p class="product-price">Rp <?= number_format($p['price'],0,',','.'); ?></p>
        <p class="product-desc"><?= substr($p['description'],0,50).'...'; ?></p>
      </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>
<script src="category.js"></script>
<?php include "footer.php"; ?>
</body>
</html>