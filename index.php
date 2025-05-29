<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechZone - Premium Tech Products</title>
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
            <li><a href="#home" class="nav-link">Home</a></li>
            <li><a href="products.php" class="nav-link">Products</a></li>

            <li><a href="#features" class="nav-link">Features</a></li>
            <li><a href="#about" class="nav-link">About Us</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
            <li class="nav-cart">
                <a href="cart.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">
                        <?php 
                        session_start();
                        echo isset($_SESSION['cart']) ? array_sum(array: array_column($_SESSION['cart'], 'quantity')) : 0; 
                        ?>
                    </span>
                </a>
            </li>
            <li class="nav-btn"><a href="login.html" class="btn btn-outline">Login</a></li>
        </ul>
    </nav>
    
     <!-- #endregion -->
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Next-Gen Tech at Your Fingertips</h1>
            <p>Discover premium tech products that elevate your digital experience</p>
            <a href="#products" class="btn btn-primary">Shop Now</a>
        </div>
        <div class="hero-image">
            <img src="https://via.placeholder.com/600x400" alt="Latest Tech Products">
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Explore our collection of premium tech products</p>
        </div>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Laptop Pro">
                </div>
                <div class="product-info">
                    <h3>UltraBook Pro X9</h3>
                    <p class="price">$1,299.99</p>
                    <form action="" method="POST">
                        <input type="hidden" name="product_id" value="1">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Smartwatch">
                </div>
                <div class="product-info">
                    <h3>SmartWatch Series 5</h3>
                    <p class="price">$299.99</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="2">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Wireless Earbuds">
                </div>
                <div class="product-info">
                    <h3>AirPods Pro Max</h3>
                    <p class="price">$199.99</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="3">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Smartphone">
                </div>
                <div class="product-info">
                    <h3>Galaxy Ultra S23</h3>
                    <p class="price">$999.99</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="4">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="view-all-container">
            <a href="products.html" class="btn btn-outline">View All Products</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="section-header">
            <h2>Why Choose Us</h2>
            <p>We offer the best shopping experience for tech enthusiasts</p>
        </div>
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>Get your products delivered within 24-48 hours of purchase</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure Payment</h3>
                <p>Multiple secure payment options for worry-free shopping</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Our customer service team is always ready to assist you</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Extended Warranty</h3>
                <p>All products come with manufacturer warranty and our guarantee</p>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about">
        <div class="section-header">
            <h2>About Us</h2>
        </div>
        <div class="about-content">
            <p>TechZone is a premier destination for tech enthusiasts looking for high-quality products at competitive prices. Founded in 2015, we've been committed to providing exceptional customer service and the latest technology to our valued customers. Our team of experts carefully selects each product to ensure it meets our high standards for quality and performance.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="section-header">
            <h2>Get In Touch</h2>
            <p>Have questions? We'd love to hear from you!</p>
        </div>
        <div class="contact-container">
            <form class="contact-form" method="POST" action="contact.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group  placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
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
                    <li><a href="#home">Home</a></li>
                    <li><a href="products.php" class="nav-link">Products</a></li>

                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
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