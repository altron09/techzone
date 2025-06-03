<?php
session_start();
require_once 'config/database.php';

// Fetch 4 sample products for the featured section
$featured_products = [];
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $featured_products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .btn-black {
            background-color: #ff8533;
            color: #fff;
            border: 1px solid #ff8533;
        }
        .btn-black:hover {
            background-color: #ff751a;
            color: #fff;
            border: 1px solid #ff751a;
        }
        .btn-white {
            background-color: #fff;
            color: #ff8533;
            border: 1px solid #ff8533;
        }
        .btn-white:hover {
            background-color: #f8f9fa;
            color: #ff8533;
            border: 1px solid #ff8533;
        }
        .btn-black-white {
            background: #ff8533;
            color: #fff;
            border: 2px solid #ff8533;
            transition: all 0.2s;
        }
        .btn-black-white:hover, .btn-black-white:focus {
            background: #fff;
            color: #ff8533;
            border: 2px solid #ff8533;
        }
        .text-primary {
            color: #ff8533 !important;
        }
        .btn-outline-light:hover {
            background-color: #ff8533;
            border-color: #ff8533;
        }
        .btn-primary {
            background-color: #ff8533;
            border-color: #ff8533;
        }
        .btn-primary:hover {
            background-color: #ff751a;
            border-color: #ff751a;
        }
        .btn-outline-primary {
            color: #ff8533;
            border-color: #ff8533;
        }
        .btn-outline-primary:hover {
            background-color: #ff8533;
            border-color: #ff8533;
        }
        .form-control:focus {
            border-color: #ff8533;
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25);
        }
        .pagination .page-item.active .page-link {
            background-color: #ff8533;
            border-color: #ff8533;
        }
        .pagination .page-link {
            color: #ff8533;
        }
        .pagination .page-link:hover {
            color: #ff751a;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 220px;
            object-fit: contain;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold mb-4">Welcome to TechZone</h1>
                    <p class="lead mb-3">Your One-Stop Destination for Premium Technology</p>
                    <p class="mb-4">Experience the future of tech with our curated collection of cutting-edge gadgets, smart devices, and innovative solutions. From high-performance laptops to smart home essentials, we bring you the latest technology at competitive prices.</p>
                    <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
                </div>
                <div class="col-md-6">
                    <img src="assets/img/hero.png" alt="Hero Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($featured_products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             class="card-img-top product-image" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="height: 220px; object-fit: contain; padding: 1rem;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted mb-2" style="min-height: 48px;">
                                <?php 
                                    $meta_description = substr(htmlspecialchars($product['description']), 0, 100);
                                    echo $meta_description . (strlen($product['description']) > 100 ? '...' : '');
                                ?>
                            </p>
                            <div class="mt-auto">
                                <div class="mb-2 fw-bold">$<?php echo number_format($product['price'], 2); ?></div>
                                <div class="d-flex gap-2">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-black-white btn-sm flex-grow-1">View Details</a>
                                    <button class="btn btn-black-white btn-sm add-to-cart" 
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="products.php" class="btn btn-primary btn-lg px-5">View More Products</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-5" style="background-color: #f8f9fa; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div class="container py-5">
            <h2 class="text-center mb-5">Why Choose Us</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-truck fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Fast Delivery</h5>
                            <p class="card-text text-muted">Quick and reliable delivery service to your doorstep within 2-3 business days.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-lock fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Secure Payment</h5>
                            <p class="card-text text-muted">Your transactions are protected with advanced encryption and security measures.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-headset fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">24/7 Support</h5>
                            <p class="card-text text-muted">Our customer support team is always ready to assist you with any queries.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-undo fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Easy Returns</h5>
                            <p class="card-text text-muted">Hassle-free return policy with 30-day money-back guarantee.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="container my-5 py-5" id="about">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4">About Us</h2>
                <p class="lead mb-4">Welcome to our tech store, your one-stop destination for all your technology needs.</p>
                <p class="text-muted mb-4">We are committed to providing high-quality products, exceptional customer service, and a seamless shopping experience. Our mission is to make technology accessible to everyone while ensuring the best value for your money.</p>
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                                <h4>Quality First</h4>
                                <p class="text-muted">We carefully select each product to ensure the highest quality standards.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-heart fa-3x mb-3 text-primary"></i>
                                <h4>Customer Focus</h4>
                                <p class="text-muted">Your satisfaction is our priority. We're dedicated to exceptional service.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-lightbulb fa-3x mb-3 text-primary"></i>
                                <h4>Innovation</h4>
                                <p class="text-muted">We stay ahead of the curve with the latest technology and solutions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>