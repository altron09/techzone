<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';
$product_id = $_POST['product_id'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

$response = [
    'success' => false,
    'message' => '',
    'cartCount' => count($_SESSION['cart']),
    'cartTotal' => 0
];

switch ($action) {
    case 'add':
        // Check if product exists and has stock
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND stock > 0");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => 1
                ];
            }
            
            $response['success'] = true;
            $response['message'] = 'Product added to cart';
        } else {
            $response['message'] = 'Product not available';
        }
        break;
        
    case 'update':
        if (isset($_SESSION['cart'][$product_id])) {
            $quantity = max(1, intval($quantity));
            
            // Check stock availability
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            if ($quantity <= $product['stock']) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                $response['success'] = true;
                $response['message'] = 'Cart updated';
            } else {
                $response['message'] = 'Not enough stock available';
            }
        }
        break;
        
    case 'remove':
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $response['success'] = true;
            $response['message'] = 'Product removed from cart';
        }
        break;
        
    default:
        $response['message'] = 'Invalid action';
}

// Calculate cart total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
$response['cartTotal'] = $total;
$response['cartCount'] = count($_SESSION['cart']);

echo json_encode($response);
?> 