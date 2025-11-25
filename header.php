<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['qty'];
    }
}
?>
<?php
include_once 'config/database.php';
$main_q = mysqli_query($conn, "SELECT * FROM main_categories ORDER BY id");
$sub_q = mysqli_query($conn, "SELECT * FROM sub_categories ORDER BY main_category_id");
$subcategories = [];
while ($sub = mysqli_fetch_assoc($sub_q)) {
    $subcategories[$sub['main_category_id']][] = $sub;
}
?>
<link rel="stylesheet" href="header.css">
<header>
  <div class="container-header">
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
          <a href="category.php">Category</a>
          <div class="dropdown">
    <?php while ($main = mysqli_fetch_assoc($main_q)): ?>
        <div class="sub-parent">
            <a href="category.php?main=<?= $main['id']; ?>" class="sub-title">
                <?= $main['name']; ?> ▸
            </a>
            <div class="sub-dropdown">
                <?php if (!empty($subcategories[$main['id']])): ?>
                    <?php foreach ($subcategories[$main['id']] as $sub): ?>
                        <a href="category.php?sub=<?= $sub['id']; ?>">
                            <?= $sub['name']; ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="display:block; padding:8px 16px; color:#888;">No Subcategories</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
        </div>
        <div class="nav-item">
          <a href="#newArvl">New Products</a>
        </div>
        <div class="nav-item">
          <a href="#about">About Us</a>
        </div>
      </nav>
      <div class="header-buttons">
        <div class="search-wrapper">
          <input type="checkbox" id="toggleSearch">
          <label for="toggleSearch" class="btn-search">Search</label>
          <form action="search.php" method="GET">
          <input type="text" name="q" class="search-input" placeholder="Search...">
          </form>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="user-dropdown">
              <button class="user-button">
                  <?= htmlspecialchars($_SESSION['user_name'][0]); ?>
              </button>
              <div class="user-dropdown-menu">
                  <div class="user-name-full">
                      <?= htmlspecialchars($_SESSION['user_name']); ?>
                  </div>
                  <a href="logout.php">Logout</a>
              </div>
          </div>
        <?php else: ?>
        <a href="login.php" class="btn-login">Sign In</a>
        <?php endif; ?>
        <a href="cart.php" class="btn-cart">
          Cart <span id="cart-count" class="cart-count"><?= $cart_count; ?></span>
        </a>
      </div>
    </div>
  </div>
</header>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const userBtn = document.querySelector(".user-button");
    const dropdown = document.querySelector(".user-dropdown-menu");
    if (userBtn) {
        userBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });
    }
    document.addEventListener("click", function (e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });
});
</script>