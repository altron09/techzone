<?php
session_start();
require_once 'cart_functions.php';

// Handle Remove Item
if (isset($_POST['remove_item'])) {
    $product_id = (int) $_POST['remove_item'];
    remove_from_cart($product_id);
    header("Location: cart.php?removed=1");
    exit;
}

// Handle Update Cart
if (isset($_POST['update_cart'])) {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        $quantities = array_map('intval', $_POST['quantity']);
        update_cart($quantities);
        header("Location: cart.php?updated=1");
        exit;
    }
}

// Handle Clear Cart
if (isset($_POST['clear_cart'])) {
    clear_cart();
    header("Location: cart.php?cleared=1");
    exit;
}

// Default fallback
header("Location: cart.php");
exit;
