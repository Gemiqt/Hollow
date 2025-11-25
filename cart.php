<?php
session_start();
include 'config/database.php';
$cart_count = 0;
if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cart_count += $item['qty'];
    }
}
function getProduct($id, $conn){
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
if(isset($_GET['action'])){
  $action = $_GET['action'];
  $key = $_GET['key'] ?? null;
  if ($key !== null && isset($_SESSION['cart'][$key])) {
      switch($action){
          case 'remove':
              unset($_SESSION['cart'][$key]);
              break;
          case 'plus':
              $_SESSION['cart'][$key]['qty']++;
              break;
          case 'minus':
              $_SESSION['cart'][$key]['qty']--;
              if($_SESSION['cart'][$key]['qty'] <= 0){
                  unset($_SESSION['cart'][$key]);
              }
              break;
      }
  }
  header("Location: cart.php");
  exit;
}
$total = 0;
if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $total += $item['price'] * $item['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cart - Hollow Store</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="cart.css">
</head>
<body>
  <header>
    <div class="container">
      <div class="header-wrapper">
        <a href="homepage.php" class="brand">
          <div class="logo-box">
            <img src="images/hollow.jpg" class="logo">
          </div>
          <div class="brand-text">
            <div class="brand-title">Hollow</div>
            <div class="brand-subtitle">Apparel Specialist</div>
          </div>
        </a>
        <nav class="nav-menu">
          <div class="nav-item">
            <a href="products.php">Product</a>
          </div>
          <div class="nav-item dropdown-parent">
            <a href="#categories">Category</a>
            <div class="dropdown">
              <div class="sub-parent">
                <a href="men.html" class="sub-title">Men ▸</a>
                <div class="sub-dropdown">
                  <a href="#">Sweater</a>
                  <a href="#">Jacket</a>
                  <a href="#">Cardigan</a>
                  <a href="#">T-shirt</a>
                </div>
              </div>
              <div class="sub-parent">
                <a href="women.html" class="sub-title">Women ▸</a>
                <div class="sub-dropdown">
                  <a href="#">Sweater</a>
                  <a href="#">Jacket</a>
                  <a href="#">Cardigan</a>
                  <a href="#">T-shirt</a>
                </div>
              </div>
              <div class="sub-parent">
                <a href="kids.html" class="sub-title">Kids ▸</a>
                <div class="sub-dropdown">
                  <a href="#">Sweater</a>
                  <a href="#">Jacket</a>
                  <a href="#">Cardigan</a>
                  <a href="#">T-shirt</a>
                </div>
              </div>
            </div>
          </div>
          <div class="nav-item">
            <a href="reviews.html">Review</a>
          </div>
          <div class="nav-item">
            <a href="about.html">About Us</a>
          </div>
        </nav>
        </div>
  </header>
<div class="cart-container">
<a href="products.php" class="btn-back">← Continue Browsing</a>
  <h2 class="cart-title">Your Cart</h2>
  <div class="cart-items">
    <?php if(!empty($_SESSION['cart'])): ?>
    <?php foreach($_SESSION['cart'] as $key => $item): ?>
    <div class="cart-item">
      <img src="<?= $item['image']; ?>">
      <div class="item-info">
      <div class="item-title">
      <?= $item['name']; ?> 
      <?php if(isset($item['size'])): ?>
      (<?= $item['size']; ?>)
      <?php endif; ?>
      </div>
      <div class="item-price">Rp <?= number_format($item['price'],0,',','.'); ?></div>
      </div>
      <div class="qty-control">
        <a href="cart.php?action=minus&key=<?= $key; ?>"><button>-</button></a>
        <span><?= $item['qty']; ?></span>
          <a href="cart.php?action=plus&key=<?= $key; ?>"><button>+</button></a>
      </div>
        <a href="cart.php?action=remove&key=<?= $key; ?>">
        <button class="remove-btn">Remove</button>
        </a>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
      <p>Your cart is empty.</p>
    <?php endif; ?>
    </div>
    <div class="cart-summary">
        <h3>Total: Rp <?= number_format($total,0,',','.'); ?></h3>
        <form action="checkout.php" method="POST">
    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
</form>

    </div>
</div>
<div id="successModal" class="modal">
  <div class="modal-content">
    <span id="closeModal" class="close">&times;</span>
    <h2>Checkout Successful!</h2>
    <p>Thank you! Your order is being processed.</p>
    <button id="okBtn">OK</button>
  </div>
</div>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  const isSuccess = urlParams.get('success');
  if (isSuccess == 1) {
    document.getElementById("successModal").style.display = "block";
  }
  document.getElementById("closeModal").onclick = () => {
    document.getElementById("successModal").style.display = "none";
    history.replaceState({}, document.title, "cart.php"); 
  };

  document.getElementById("okBtn").onclick = () => {
    document.getElementById("successModal").style.display = "none";
    history.replaceState({}, document.title, "cart.php");
  };
</script>
</body>
</html>