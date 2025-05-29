<?php
session_start();
require_once 'cart_functions.php';

$cart = $_SESSION['cart'] ?? [];

// Sample products data (simulate a DB in real-world)
$products = [
    1 => ["name" => "UltraBook Pro X9", "price" => 1299.99, "image" => "https://via.placeholder.com/300x300"],
    2 => ["name" => "SmartWatch Series 5", "price" => 299.99, "image" => "https://via.placeholder.com/300x300"],
    3 => ["name" => "AirPods Pro Max", "price" => 199.99, "image" => "https://via.placeholder.com/300x300"],
    4 => ["name" => "Galaxy Ultra S23", "price" => 999.99, "image" => "https://via.placeholder.com/300x300"],
];

// Calculate total
$total = 0;
foreach ($cart as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $price = $products[$product_id]['price'];
        $total += $price * (int)$quantity;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - TechZone</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <h1>Tech<span>Zone</span></h1>
    </div>
    <ul class="nav-menu">
        <li><a href="index.php#home" class="nav-link">Home</a></li>
        <li><a href="products.php" class="nav-link">Products</a></li>
        <li><a href="index.php#features" class="nav-link">Features</a></li>
        <li><a href="index.php#about" class="nav-link">About Us</a></li>
        <li><a href="index.php#contact" class="nav-link">Contact</a></li>
        <li class="nav-cart">
            <a href="cart.php" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?php echo array_sum($cart); ?></span>
            </a>
        </li>
        <li class="nav-btn"><a href="login.html" class="btn btn-outline">Login</a></li>
    </ul>
</nav>

<!-- Cart Page -->
<section class="cart-page">
    <div class="section-header">
        <h2>Your Shopping Cart</h2>
        <p>Review the items in your cart</p>
    </div>

    <?php if (isset($_GET['removed'])): ?>
        <div class="alert-success">Item removed successfully.</div>
    <?php elseif (isset($_GET['updated'])): ?>
        <div class="alert-success">Cart updated successfully.</div>
    <?php elseif (isset($_GET['cleared'])): ?>
        <div class="alert-success">Cart cleared.</div>
    <?php endif; ?>

    <?php if (empty($cart)) : ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="products.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else : ?>
        <form method="POST" action="cart_actions.php">

            <div class="cart-container">
                <?php foreach ($cart as $product_id => $quantity): 
                    if (!isset($products[$product_id])) continue;
                    $product = $products[$product_id];
                ?>
                    <div class="cart-item">
                        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="cart-details">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2) ?></p>
                            <label>
                                Quantity:
                                <input type="number" name="quantity[<?= $product_id ?>]" value="<?= $quantity ?>" min="1">
                            </label>
                            <button type="submit" name="remove_item" value="<?= $product_id ?>" class="btn btn-outline" style="margin-top: 10px;">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Total: $<?= number_format($total, 2) ?></h3>
                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                <button type="submit" name="clear_cart" class="btn btn-outline">Clear Cart</button>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <h2>Tech<span>Zone</span></h2>
            <p>Your one-stop shop for premium tech products</p>
        </div>
        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php#home">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="index.php#features">Features</a></li>
                <li><a href="index.php#about">About Us</a></li>
                <li><a href="index.php#contact">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <h3>Connect With Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 TechZone. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
