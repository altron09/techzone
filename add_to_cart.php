<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Validate input
    if ($product_id <= 0 || $quantity <= 0) {
        header('Location: products.php?error=invalid_input');
        exit();
    }

    // Get product details from database
    $query = "SELECT id, name, price, image_url, stock FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        header('Location: products.php?error=database_error');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        header('Location: products.php?error=database_error');
        exit();
    }

    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$product) {
        header('Location: products.php?error=product_not_found');
        exit();
    }

    // Check if requested quantity is available in stock
    if ($quantity > $product['stock']) {
        header('Location: products.php?error=insufficient_stock');
        exit();
    }

    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add or update item in cart
    if (isset($_SESSION['cart'][$product_id]) && is_array($_SESSION['cart'][$product_id])) {
        // Check if total quantity (existing + new) exceeds stock
        $new_quantity = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
        if ($new_quantity > $product['stock']) {
            header('Location: products.php?error=insufficient_stock');
            exit();
        }
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Create new cart item
        $_SESSION['cart'][$product_id] = array(
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'quantity' => $quantity,
            'image_url' => $product['image_url'],
            'stock' => (int)$product['stock']
        );
    }

    // Redirect to cart page with success message
    header('Location: cart.php?success=added');
    exit();
} else {
    // If not POST request, redirect to products page
    header('Location: products.php');
    exit();
}
?> 