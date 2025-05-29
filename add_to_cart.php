<?php
session_start();
require_once 'cart_functions.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'cart_count' => 0
];

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Validate and sanitize input
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if (!$product_id || !$quantity) {
    $response['message'] = 'Invalid product or quantity';
    echo json_encode($response);
    exit;
}

// Validate quantity
if ($quantity < 1) {
    $response['message'] = 'Quantity must be at least 1';
    echo json_encode($response);
    exit;
}

try {
    // Add item to cart
    add_to_cart($product_id, $quantity);
    
    // Calculate new cart count
    $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
    
    $response['success'] = true;
    $response['message'] = 'Item added to cart successfully';
    $response['cart_count'] = $cart_count;
} catch (Exception $e) {
    $response['message'] = 'Error adding item to cart: ' . $e->getMessage();
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response); 