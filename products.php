<?php
session_start();
require_once 'config/database.php';

// Pagination settings
$products_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

// Get total number of products
$total_query = "SELECT COUNT(*) as count FROM products";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['count'];
$total_pages = ceil($total_products / $products_per_page);

// Fetch products with pagination
$products = [];
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $products_per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Tech Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
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
        /* Pagination Styles */
        .pagination .page-link {
            color: #ff8533;
            border-color: #ff8533;
        }
        .pagination .page-item.active .page-link {
            background-color: #ff8533;
            border-color: #ff8533;
            color: #fff;
        }
        .pagination .page-link:hover {
            background-color: #ff751a;
            border-color: #ff751a;
            color: #fff;
        }
        /* All Button Styles */
        .btn,
        .btn:focus,
        .btn:active,
        .btn:visited {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .btn:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
        /* Outline Button Styles */
        .btn-outline-primary,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-primary:visited {
            background-color: transparent !important;
            border-color: #ff8533 !important;
            color: #ff8533 !important;
        }
        .btn-outline-primary:hover {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        /* Product Card Specific Buttons */
        .product-card .btn-primary,
        .product-card .btn-primary:focus,
        .product-card .btn-primary:active,
        .product-card .btn-primary:visited,
        .product-card .add-to-cart,
        .product-card .add-to-cart:focus,
        .product-card .add-to-cart:active,
        .product-card .add-to-cart:visited {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .product-card .btn-primary:hover,
        .product-card .add-to-cart:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
        /* Search Button */
        .search-btn {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .search-btn:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Products Section -->
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Our Products</h2>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             class="card-img-top product-image" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted mb-2" style="min-height:48px;">
                                <?php 
                                    $meta_description = substr(htmlspecialchars($product['description']), 0, 100);
                                    echo $meta_description . (strlen($product['description']) > 100 ? '...' : '');
                                ?>
                            </p>
                            <div class="mt-auto">
                                <div class="mb-2 fw-bold">€<?php echo number_format($product['price'], 2); ?></div>
                                <div class="d-flex gap-2">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-primary btn-sm flex-grow-1" style="background-color: var(--primary-color); border-color: var(--primary-color);">View Details</a>
                                    <button class="btn btn-primary btn-sm add-to-cart" 
                                            data-product-id="<?php echo $product['id']; ?>"
                                            style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>