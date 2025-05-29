<?php
session_start();
require_once 'cart_functions.php';

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Sample products data (should be fetched from database in production)
$products = [
    1 => ["name" => "UltraBook Pro X9", "price" => 1299.99, "image" => "https://via.placeholder.com/300x300"],
    2 => ["name" => "SmartWatch Series 5", "price" => 299.99, "image" => "https://via.placeholder.com/300x300"],
    3 => ["name" => "AirPods Pro Max", "price" => 199.99, "image" => "https://via.placeholder.com/300x300"],
    4 => ["name" => "Galaxy Ultra S23", "price" => 999.99, "image" => "https://via.placeholder.com/300x300"],
];

// Calculate total
$total = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $price = $products[$product_id]['price'];
        $subtotal = $price * $quantity;
        $total += $subtotal;
        $cart_items[] = [
            'id' => $product_id,
            'name' => $products[$product_id]['name'],
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process order (in production, this would save to database)
    // For now, just clear the cart and redirect to success page
    clear_cart();
    header('Location: order_success.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechZone</title>
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
                    <span class="cart-count"><?php echo array_sum($_SESSION['cart']); ?></span>
                </a>
            </li>
            <li class="nav-btn"><a href="login.html" class="btn btn-outline">Login</a></li>
        </ul>
    </nav>

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="checkout-container">
            <div class="checkout-header">
                <h2>Checkout</h2>
                <p>Complete your purchase</p>
            </div>

            <form class="checkout-form" method="POST" action="checkout.php">
                <div class="checkout-grid">
                    <!-- Shipping Information -->
                    <div class="checkout-section">
                        <h3>Shipping Information</h3>
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" required>
                            </div>
                            <div class="form-group">
                                <label for="zip">ZIP Code</label>
                                <input type="text" id="zip" name="zip" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="checkout-section">
                        <h3>Payment Information</h3>
                        <div class="form-group">
                            <label for="card_name">Name on Card</label>
                            <input type="text" id="card_name" name="card_name" required>
                        </div>
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="checkout-summary">
                    <h3>Order Summary</h3>
                    <div class="order-items">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="order-item">
                                <span class="item-name"><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                <span class="item-price">$<?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Place Order</button>
            </form>
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