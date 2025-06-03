<?php
session_start();
require_once 'config/database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'cart_total' => 0,
    'cart_count' => 0
];

try {
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product_id and quantity are provided
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        throw new Exception('Missing required parameters');
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Validate product exists and has enough stock
    $query = "SELECT stock FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 0) {
        throw new Exception('Product not found');
    }

    $product = mysqli_fetch_assoc($result);
    
    // Validate quantity against stock
    if ($quantity > $product['stock']) {
        throw new Exception('Requested quantity exceeds available stock');
    }

    // Update or remove item
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
        $response['message'] = 'Cart updated successfully';
    } else {
        unset($_SESSION['cart'][$product_id]);
        $response['message'] = 'Item removed from cart';
    }
    $response['success'] = true;

    // Calculate new cart total and count
    if (!empty($_SESSION['cart'])) {
        $product_ids = array_keys($_SESSION['cart']);
        $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
        $query = "SELECT id, price FROM products WHERE id IN ($placeholders)";
        
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception('Database error: ' . mysqli_error($conn));
        }

        // Bind parameters
        $types = str_repeat('i', count($product_ids));
        mysqli_stmt_bind_param($stmt, $types, ...$product_ids);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($product = mysqli_fetch_assoc($result)) {
            $response['cart_total'] += $product['price'] * $_SESSION['cart'][$product['id']];
        }
        
        // Calculate total items in cart
        $response['cart_count'] = array_sum($_SESSION['cart']);
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Cart Error: ' . $e->getMessage());
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>