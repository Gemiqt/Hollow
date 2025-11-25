<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Hollow — Apparel Specialist" />
  <title>Hollow - Apparel Specialist</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <?php
  include 'config/database.php';
  $best = $conn->query("SELECT * FROM products WHERE best_seller = 1 LIMIT 1");
  $bestSeller = $best->fetch_assoc();
  ?>
  <?php
$newArrivals = $conn->query("
SELECT id, name, price, image, created_at 
FROM products 
ORDER BY created_at DESC 
LIMIT 4
");
?>
  <main class="container">
    <section class="promo-slider">
      <div class="slider-container">
        <div class="slide active">  
          <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=1200&auto=format" alt="Promo 1">
          <div class="slide-caption">20% Off Oversized Hoodie</div>
        </div>
        <div class="slide">
          <img src="images/ads1.jpg" alt="Promo 2">
          <div class="slide-caption">Style on point? Let Hollow level it up</div>
        </div>
        <div class="slide">
          <img src="images/slider3.png" alt="Promo 3">
          <div class="slide-caption">Free Shipping to All of Indonesia</div>
        </div>
        <button class="slider-btn prev">❮</button>
        <button class="slider-btn next">❯</button>
      </div>
    </section>
    <section class="hero-grid">
      <div class="hero-box">
        <div class="hero-content">
          <div>
            <h1 class="title">
            Hollow — Apparel Specialist
            </h1>
            <p class="subtitle">
            Our products: Hoodies, sweaters, jackets, cardigans, and t-shirts — for men, women, and kids. Full size options, cool colorways, and fast shipping.
            </p>
            <div class="btn-group">
              <a href="products.php" class="btn-primary">View Product</a>
            </div>
            <div class="badge-group">
              <div class="badge-filled">10% Discount on New Products</div>
              <div class="badge-outline">Free Return 7 days</div>
            </div>
          </div>
          <div class="hero-image-wrapper">
            <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?q=80&w=900&auto=format&fit=crop"
            alt="banner"
            class="hero-image">
          </div>
        </div>
      </div>
      <aside class="best-seller">
        <h3 class="bs-title">Our Best Selling Product</h3>
        <?php if ($bestSeller): ?>
          <div class="bs-card">
            <img src="<?= $bestSeller['image']; ?>" alt="<?= $bestSeller['name']; ?>">
            <div class="bs-info">
              <h4 class="bs-name"><?= $bestSeller['name']; ?></h4>
              <p class="bs-price">
                Rp <?= number_format($bestSeller['price'], 0, ',', '.'); ?>
              </p>
              <a href="product_detail.php?id=<?= $bestSeller['id']; ?>" class="bs-btn">
                View Detail
              </a>
            </div>
          </div>
          <?php else: ?>
            <p style="padding:10px">No best seller products yet.</p>
          <?php endif; ?>
      </aside>
    </section>
    <section class="new-arrivals" id="newArvl">
  <h2 class="section-title">New Arrivals</h2>
  <div class="product-grid">
    <?php if ($newArrivals->num_rows > 0): ?>
      <?php while ($p = $newArrivals->fetch_assoc()): ?>

        <a href="product_detail.php?id=<?= $p['id']; ?>" class="product-card-link">
          <div class="product-card">
            <img src="<?= $p['image']; ?>" alt="<?= $p['name']; ?>" class="product-img">

            <div class="product-info">
              <h4 class="product-name"><?= $p['name']; ?></h4>

              <p class="product-price">
                Rp <?= number_format($p['price'], 0, ',', '.'); ?>
              </p>

              <?php if (!empty($p['description'])): ?>
                <p class="product-desc"><?= substr($p['description'], 0, 60); ?>...</p>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No new products available.</p>
    <?php endif; ?>
  </div>
</section>
  </main>
  <?php include 'footer.php' ?>
<script src="homepage.js"></script>
</body>
</html>