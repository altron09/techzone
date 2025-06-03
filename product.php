<?php
session_start();
require_once 'config/database.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found, redirect to products page
if (!$product) {
    header('Location: products.php');
    exit();
}

// Fetch related products
$query = "SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $product['category'], $product_id);
$stmt->execute();
$related_products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .product-image {
            max-height: 400px;
            object-fit: contain;
        }
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
            background-color: #fff;
            color: #ff751a;
            border: 1px solid #ff751a;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ff8533;
            padding: 0.375rem;
        }
        .quantity-input:focus {
            border-color: #ff8533;
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25);
        }
        .btn-primary {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .btn-primary:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
        .btn-outline-primary {
            color: #ff8533 !important;
            border-color: #ff8533 !important;
        }
        .btn-outline-primary:hover {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                     class="img-fluid rounded product-image" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="h3 mb-4">$<?php echo number_format($product['price'], 2); ?></p>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="mb-4">
                    <h5>Availability</h5>
                    <p class="<?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </p>
                </div>

                <?php if ($product['stock'] > 0): ?>
                    <form id="addToCartForm" class="mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="quantity" class="form-label">Quantity:</label>
                                <input type="number" class="form-control quantity-input" 
                                       id="quantity" name="quantity" value="1" min="1" 
                                       max="<?php echo $product['stock']; ?>">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary mt-4" 
                                        onclick="addToCart(<?php echo $product['id']; ?>, document.getElementById('quantity').value)">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <div class="mb-4">
                    <a href="products.php" class="btn btn-white">
                        <i class="fas fa-arrow-left me-2"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
            <div class="mt-5">
                <h3 class="mb-4">Related Products</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach ($related_products as $related): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm product-card">
                                <img src="<?php echo htmlspecialchars($related['image_url']); ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($related['name']); ?></h5>
                                    <p class="card-text text-muted mb-2" style="min-height:48px;">
                                        <?php 
                                            $meta_description = substr(htmlspecialchars($related['description']), 0, 100);
                                            echo $meta_description . (strlen($related['description']) > 100 ? '...' : '');
                                        ?>
                                    </p>
                                    <div class="mt-auto">
                                        <div class="mb-2 fw-bold">$<?php echo number_format($related['price'], 2); ?></div>
                                        <div class="d-flex gap-2">
                                            <a href="product.php?id=<?php echo $related['id']; ?>" 
                                               class="btn btn-primary btn-sm flex-grow-1">View Details</a>
                                            <button class="btn btn-primary btn-sm add-to-cart" 
                                                    data-product-id="<?php echo $related['id']; ?>">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>