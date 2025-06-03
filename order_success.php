<?php
session_start();
require_once 'config/database.php';

$order_id = $_GET['order_id'] ?? 0;

// Get order details
$stmt = $conn->prepare("
    SELECT o.*, oi.*, p.name as product_name, p.image_url 
    FROM orders o 
    JOIN order_items oi ON o.id = oi.order_id 
    JOIN products p ON oi.product_id = p.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$order_items = [];
$order_total = 0;
$order_date = '';

while ($row = $result->fetch_assoc()) {
    $order_items[] = $row;
    $order_total = $row['total_amount'];
    $order_date = $row['created_at'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h1 class="mt-3">Order Confirmed!</h1>
                        <p class="lead">Thank you for your purchase.</p>
                        <p>Your order number is: <strong>#<?php echo str_pad($order_id, 8, '0', STR_PAD_LEFT); ?></strong></p>
                        <p>Order date: <?php echo date('F j, Y', strtotime($order_date)); ?></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Order Details</h5>
                        <?php foreach ($order_items as $item): ?>
                            <div class="row align-items-center mb-3">
                                <div class="col-md-2">
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                         class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <h6><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                    <p class="text-muted">Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-0">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>$<?php echo number_format($order_total, 2); ?></strong>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="products.php" class="btn btn-primary">Continue Shopping</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-outline-primary">View Order History</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 