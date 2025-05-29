<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - TechZone</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <h1>Tech<span>Zone</span></h1>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="products.php" class="nav-link">Products</a></li>
            <li><a href="index.php#features" class="nav-link">Features</a></li>
            <li><a href="index.php#about" class="nav-link">About Us</a></li>
            <li><a href="index.php#contact" class="nav-link">Contact</a></li>
            <li class="nav-cart">
                <a href="cart.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </li>
            <li class="nav-btn"><a href="login.html" class="btn btn-outline">Login</a></li>
        </ul>
    </nav>

    <!-- Success Message -->
    <section class="success-section">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your purchase. Your order has been received and is being processed.</p>
            <div class="success-actions">
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                <a href="products.php" class="btn btn-outline">View Products</a>
            </div>
        </div>
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
                    <li><a href="index.php">Home</a></li>
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
            <p>&copy; 2023 TechZone. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html> 