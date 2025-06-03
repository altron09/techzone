<?php
session_start();
require_once 'config/database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Calculate cart total
$cart_total = 0;
$cart_items = [];

try {
    $product_ids = array_keys($_SESSION['cart']);
    if (!empty($product_ids)) {
        $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
        
        // Get product details
        $query = "SELECT id, name, price, image_url FROM products WHERE id IN ($placeholders)";
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
                
                if ($quantity > 0 && $price > 0) {
                    $subtotal = $price * $quantity;
                    $cart_total += $subtotal;
                    
                    $cart_items[] = [
                        'id' => (int)$product['id'],
                        'name' => $product['name'],
                        'price' => $price,
                        'image_url' => $product['image_url'],
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ];
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
} catch (Exception $e) {
    error_log("Checkout Error: " . $e->getMessage());
    echo '<div class="alert alert-danger">An error occurred while loading the checkout. Please try again.</div>';
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'email', 'address', 'city', 'state', 'zip'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }

        // Validate payment details
        if (empty($_POST['card_number']) || empty($_POST['expiry']) || empty($_POST['cvv'])) {
            throw new Exception("Please fill in all payment details.");
        }

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert order
            $query = "INSERT INTO orders (total_amount, status) VALUES (?, 'pending')";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "d", $cart_total);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);

            // Insert order items
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            foreach ($cart_items as $item) {
                mysqli_stmt_bind_param($stmt, "iiid", 
                    $order_id,
                    $item['id'],
                    $item['quantity'],
                    $item['price']
                );
                mysqli_stmt_execute($stmt);

                // Update product stock
                $update_stock = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $stock_stmt = mysqli_prepare($conn, $update_stock);
                mysqli_stmt_bind_param($stock_stmt, "ii", $item['quantity'], $item['id']);
                mysqli_stmt_execute($stock_stmt);
            }

            // Commit transaction
            mysqli_commit($conn);

            // Clear cart
            $_SESSION['cart'] = [];

            // Redirect to success page
            header("Location: order_success.php?order_id=" . $order_id);
            exit();

        } catch (Exception $e) {
            mysqli_rollback($conn);
            throw $e;
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tech Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Checkout</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" id="checkout-form" onsubmit="return validateForm('checkout-form')">
                            <!-- Shipping Information -->
                            <h5 class="mb-3">Shipping Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="zip" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control" id="zip" name="zip" required>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <h5 class="mb-3 mt-4">Payment Information</h5>
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number *</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" 
                                       required maxlength="19" onkeyup="formatCardNumber(this)">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry" class="form-label">Expiry Date *</label>
                                    <input type="text" class="form-control" id="expiry" name="expiry" 
                                           required maxlength="5" placeholder="MM/YY" onkeyup="formatExpiry(this)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" 
                                           required maxlength="3" onkeyup="this.value=this.value.replace(/[^\d]/g,'')">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Summary</h5>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            input.value = formattedValue;
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            input.value = value;
        }
    </script>
</body>
</html> 