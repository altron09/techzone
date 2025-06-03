<?php
session_start();
require_once 'config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin/login.php');
    exit;
}

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $category = trim(mysqli_real_escape_string($conn, $_POST['category']));
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = "Product name is required";
    }
    if (empty($description)) {
        $errors[] = "Product description is required";
    }
    if ($price === false || $price <= 0) {
        $errors[] = "Please enter a valid price";
    }
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    if ($stock === false || $stock < 0) {
        $errors[] = "Please enter a valid stock quantity";
    }
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF images are allowed";
        } else {
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_url = $upload_path;
            } else {
                $errors[] = "Error uploading image";
            }
        }
    } else {
        $errors[] = "Product image is required";
    }
    
    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $query = "INSERT INTO products (name, description, price, image_url, category, stock) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssdssi", $name, $description, $price, $image_url, $category, $stock);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = '<div class="alert alert-success">
                <h4 class="alert-heading">Success!</h4>
                <p>Product has been added successfully.</p>
                <hr>
                <p class="mb-0">You can now view it in the <a href="admin/dashboard.php" class="alert-link">dashboard</a>.</p>
            </div>';
            
            // Clear form data after successful submission
            $_POST = array();
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
    
    // If there are errors, display them
    if (!empty($errors)) {
        $message = '<div class="alert alert-danger"><ul class="mb-0">';
        foreach ($errors as $error) {
            $message .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $message .= '</ul></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Tech Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            margin-top: 1rem;
        }
        .btn-black-white {
            background: #000;
            color: #fff;
            border: 2px solid #000;
            transition: all 0.2s;
        }
        .btn-black-white:hover {
            background: #fff;
            color: #000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin/dashboard.php">Admin Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a href="admin/dashboard.php" class="btn btn-primary me-2">Back to Dashboard</a>
                <a href="admin/logout.php" class="btn btn-outline-primary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="form-container">
            <h2 class="text-center mb-4">Add New Product</h2>
            
            <?php echo $message; ?>
            
            <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                               required>
                        <div class="invalid-feedback">Please enter a product name.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" 
                               value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category']) : ''; ?>" 
                               required>
                        <div class="invalid-feedback">Please enter a category.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php 
                        echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; 
                    ?></textarea>
                    <div class="invalid-feedback">Please enter a description.</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" 
                               value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" 
                               step="0.01" min="0" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" 
                               value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>" 
                               min="0" required>
                        <div class="invalid-feedback">Please enter stock quantity.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    <div class="invalid-feedback">Please select an image.</div>
                    <img id="imagePreview" class="preview-image d-none" src="#" alt="Preview">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-black-white">Add Product</button>
                    <a href="admin/dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
            }
        });
    </script>
</body>
</html> 