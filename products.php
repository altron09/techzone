<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - TechZone</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation (same as index.php) -->
    

    <!-- All Products Section -->
    <section class="products">
        <div class="section-header">
            <h2>All Products</h2>
            <p>Browse our complete collection of premium tech products</p>
        </div>
        <div class="products-grid">
            <!-- Product Card 1 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Laptop Pro">
                </div>
                <div class="product-info">
                    <h3>UltraBook Pro X9</h3>
                    <p class="price">$1,299.99</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="1">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>

            <!-- Product Card 2 -->
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

            <!-- Product Card 3 -->
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

            <!-- Product Card 4 -->
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

<!-- Product Card 1 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Laptop Pro">
                </div>
                <div class="product-info">
                    <h3>UltraBook Pro X9</h3>
                    <p class="price">$1,299.99</p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="1">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-add-cart">Add to Cart</button>
                    </form>
                </div>
            </div>

            <!-- Product Card 2 -->
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

            <!-- Product Card 3 -->
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

            <!-- Product Card 4 -->
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

            <!-- More products can be added similarly in groups of 4 -->
        </div>
    </section>

    <!-- Footer -->
    

    <script src="script.js"></script>
</body>
</html>
