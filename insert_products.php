<?php
require_once 'config/database.php';

// Sample products data
$products = [
    [
        'name' => 'iPhone 15 Pro Max',
        'description' => 'Latest Apple flagship with A17 Pro chip, 48MP camera, and titanium design. Experience the future of mobile technology.',
        'price' => 1199.99,
        'image_url' => 'assets/images/products/iphone15.jpg',
        'category' => 'Smartphones',
        'stock' => 50
    ],
    [
        'name' => 'Samsung Galaxy S24 Ultra',
        'description' => 'Revolutionary AI features, 200MP camera, and S Pen support. The ultimate Android experience.',
        'price' => 1299.99,
        'image_url' => 'assets/images/products/s24ultra.jpg',
        'category' => 'Smartphones',
        'stock' => 45
    ],
    [
        'name' => 'MacBook Pro M3',
        'description' => 'Powered by M3 chip, 14-inch Liquid Retina XDR display, up to 22 hours battery life. Perfect for professionals.',
        'price' => 1999.99,
        'image_url' => 'assets/images/products/macbook.jpg',
        'category' => 'Laptops',
        'stock' => 30
    ],
    [
        'name' => 'Sony WH-1000XM5',
        'description' => 'Industry-leading noise cancellation, 30-hour battery life, and exceptional sound quality. The best wireless headphones.',
        'price' => 399.99,
        'image_url' => 'assets/images/products/sonyxm5.jpg',
        'category' => 'Audio',
        'stock' => 60
    ],
    [
        'name' => 'iPad Pro M2',
        'description' => '12.9-inch Liquid Retina XDR display, M2 chip, and Apple Pencil support. The ultimate tablet for creative professionals.',
        'price' => 1099.99,
        'image_url' => 'assets/images/products/ipad.jpg',
        'category' => 'Tablets',
        'stock' => 40
    ],
    [
        'name' => 'Dell XPS 15',
        'description' => '15.6-inch InfinityEdge display, 12th Gen Intel Core i9, NVIDIA RTX 3050 Ti. Premium Windows laptop for power users.',
        'price' => 1899.99,
        'image_url' => 'assets/images/products/dellxps.jpg',
        'category' => 'Laptops',
        'stock' => 25
    ],
    [
        'name' => 'Apple Watch Series 9',
        'description' => 'Always-On Retina display, advanced health features, and seamless iPhone integration. Your perfect health companion.',
        'price' => 399.99,
        'image_url' => 'assets/images/products/applewatch.jpg',
        'category' => 'Wearables',
        'stock' => 70
    ],
    [
        'name' => 'Samsung Galaxy Tab S9 Ultra',
        'description' => '14.6-inch Dynamic AMOLED 2X display, Snapdragon 8 Gen 2, S Pen included. The ultimate Android tablet experience.',
        'price' => 999.99,
        'image_url' => 'assets/images/products/tabs9.jpg',
        'category' => 'Tablets',
        'stock' => 35
    ]
];

// Clear existing products
mysqli_query($conn, "TRUNCATE TABLE products");

// Insert new products
$insert_query = "INSERT INTO products (name, description, price, image_url, category, stock) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query);

foreach ($products as $product) {
    mysqli_stmt_bind_param($stmt, "ssdssi", 
        $product['name'],
        $product['description'],
        $product['price'],
        $product['image_url'],
        $product['category'],
        $product['stock']
    );
    mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);

echo "Products have been successfully added to the database!";
?> 