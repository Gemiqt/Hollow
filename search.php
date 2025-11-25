<?php
include 'config/database.php';

$q = $_GET['q'] ?? '';
$q = trim($q);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Result</title>
<link rel="stylesheet" href="search.css">
</head>
<body>
<?php include 'header.php' ?>
<a href="products.php" class="btn-back">← Back to Products</a>
<h2 class="search-title">Search Result: "<?= htmlspecialchars($q) ?>"</h2>
<?php
if ($q === '') {
    echo "<p class='empty-text'>Please type something...</p>";
    exit;
}
$searchTerm = "%$q%";
$stmtReady = $conn->prepare("SELECT * FROM products 
                             WHERE stock > 0 AND (name LIKE ? OR description LIKE ?)");
$stmtReady->bind_param("ss", $searchTerm, $searchTerm);
$stmtReady->execute();
$ready = $stmtReady->get_result();
$stmtSold = $conn->prepare("SELECT * FROM products 
                            WHERE stock = 0 AND (name LIKE ? OR description LIKE ?)");
$stmtSold->bind_param("ss", $searchTerm, $searchTerm);
$stmtSold->execute();
$sold = $stmtSold->get_result();
?>
<?php if ($ready->num_rows == 0 && $sold->num_rows == 0): ?>

    <p class="empty-text">No Result Found.</p>
<?php else: ?>
    <?php if ($ready->num_rows > 0): ?>
    <div class="product-grid">
        <?php while ($p = $ready->fetch_assoc()) : ?>
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
    <?php endif; ?>
    <?php if ($sold->num_rows > 0): ?>
        <h2 class="section-title" style="margin-top:50px;">This Product is Out of Stock</h2>
        <div class="product-grid">
        <?php while ($p = $sold->fetch_assoc()) : ?>
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
<?php endif; ?>
<script src="search.js"></script>
</body>
</html>