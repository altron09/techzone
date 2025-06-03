<?php
session_start();
require_once 'config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart total
$cart_total = 0;
$cart_items = [];

if (!empty($_SESSION['cart'])) {
    try {
        $product_ids = array_keys($_SESSION['cart']);
        if (!empty($product_ids)) {
            $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
            
            // Get product details
            $query = "SELECT id, name, price, image_url, stock FROM products WHERE id IN ($placeholders)";
            $stmt = mysqli_prepare($conn, $query);
            
            if ($stmt) {
                // Bind parameters
                $types = str_repeat('i', count($product_ids));
                mysqli_stmt_bind_param($stmt, $types, ...$product_ids);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                while ($product = mysqli_fetch_assoc($result)) {
                    $quantity = isset($_SESSION['cart'][$product['id']]) ? (int)$_SESSION['cart'][$product['id']] : 0;
                    $price = isset($product['price']) ? (float)$product['price'] : 0.00;
                    
                    // Validate price and quantity
                    if ($price > 0 && $quantity > 0) {
                        $subtotal = $price * $quantity;
                        $cart_total += $subtotal;
                        
                        $cart_items[] = [
                            'id' => (int)$product['id'],
                            'name' => $product['name'],
                            'price' => $price,
                            'image_url' => $product['image_url'],
                            'quantity' => $quantity,
                            'stock' => (int)$product['stock'],
                            'subtotal' => $subtotal
                        ];
                    }
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Failed to prepare statement: " . mysqli_error($conn));
            }
        }
    } catch (Exception $e) {
        error_log("Cart Error: " . $e->getMessage());
        echo '<div class="alert alert-danger">An error occurred while loading the cart. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .cart-item img {
            max-width: 100px;
            height: auto;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        /* Cart Page Specific Button Styles */
        .btn-outline-secondary {
            border-color: #ff8533;
            color: #ff8533;
        }
        .btn-outline-secondary:hover {
            background-color: #ff8533;
            border-color: #ff8533;
            color: #fff;
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
        .btn-link.text-danger {
            color: #ff8533 !important;
        }
        .btn-link.text-danger:hover {
            color: #ff751a !important;
        }
        .alert-link {
            color: #ff8533;
        }
        .alert-link:hover {
            color: #ff751a;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Shopping Cart</h1>

        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="products.php" class="alert-link">Continue shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <?php foreach ($cart_items as $index => $item): ?>
                                <div class="row mb-4 cart-item" data-product-id="<?php echo $item['id']; ?>">
                                    <div class="col-md-2">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="text-muted mb-0">$<?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" max="<?php echo $item['stock']; ?>"
                                                   data-product-id="<?php echo $item['id']; ?>">
                                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <p class="mb-1 fw-bold">$<?php echo number_format($item['subtotal'], 2); ?></p>
                                        <button class="btn btn-link text-danger p-0 remove-from-cart" 
                                                data-product-id="<?php echo $item['id']; ?>">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <?php if ($index < count($cart_items) - 1): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold">$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            <a href="checkout.php" class="btn btn-primary w-100 mb-2">Proceed to Checkout</a>
                            <a href="products.php" class="btn btn-outline-primary w-100">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function updateQuantity(productId, action) {
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            let value = parseInt(input.value);
            
            if (action === 'increase' && value < parseInt(input.max)) {
                value++;
            } else if (action === 'decrease' && value > 1) {
                value--;
            }
            
            input.value = value;
            updateCartQuantity(productId, value);
        }

        // Add event listeners for quantity inputs
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const quantity = parseInt(this.value);
                if (quantity > 0) {
                    updateCartQuantity(productId, quantity);
                }
            });
        });

        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                removeFromCart(productId);
            });
        });
    </script>
</body>
</html>
