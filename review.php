<?php
include 'config/database.php';
$query = "
    SELECT r.*, p.product_name, p.image
    FROM reviews r
    LEFT JOIN products p ON r.product_id = p.product_id
    ORDER BY r.created_at DESC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Reviews</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Montserrat', sans-serif;
        background: #f8f9fa;
        margin: 0;
        padding: 30px;
    }
    .review-container {
        max-width: 900px;
        margin: auto;
    }
    .review-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0px 2px 10px rgba(0,0,0,0.08);
        display: flex;
        gap: 20px;
    }
    .product-img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 10px;
    }
    .review-content {
        flex-grow: 1;
    }
    .rating {
        color: #ffc107;
        font-size: 18px;
        margin-bottom: 6px;
    }
    .username {
        font-weight: bold;
        margin-bottom: 6px;
    }
    .date {
        font-size: 13px;
        color: gray;
        margin-bottom: 8px;
    }
    h2 {
        text-align: center;
        margin-bottom: 30px;
    }
</style>
</head>
<body>
<div class="review-container">
    <h2>Customer Reviews</h2>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="review-card">
            <img src="img/products/<?php echo $row['image']; ?>" class="product-img">
            <div class="review-content">
                <div class="username">
                    <?php echo htmlspecialchars($row['username']); ?>
                </div>
                <div class="product-name">
                    <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
                </div>
                <div class="rating">
                    <?php echo str_repeat("★", $row['rating']); ?>
                    <?php echo str_repeat("☆", 5 - $row['rating']); ?>
                </div>
                <p><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>
                <div class="date">
                    Reviewed on: <?php echo date('d M Y H:i', strtotime($row['created_at'])); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>