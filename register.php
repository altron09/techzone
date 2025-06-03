<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            // Create new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Button Styles */
        .btn-primary {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .btn-primary:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
        .btn-primary:focus {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25) !important;
        }
        .btn-primary:active {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
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
        /* Form Styles */
        .form-control:focus {
            border-color: #ff8533;
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25);
        }
        /* Link Colors */
        a {
            color: #ff8533;
            text-decoration: none;
        }
        a:hover {
            color: #ff751a;
            text-decoration: underline;
        }
        /* Alert Colors */
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        /* Password Strength Indicator */
        .password-strength {
            height: 5px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .password-strength.weak {
            background-color: #dc3545;
            width: 25%;
        }
        .password-strength.medium {
            background-color: #ffc107;
            width: 50%;
        }
        .password-strength.strong {
            background-color: #ff8533;
            width: 75%;
        }
        .password-strength.very-strong {
            background-color: #28a745;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Register</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="register.php" onsubmit="return validateForm('registerForm')">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required onkeyup="updatePasswordStrength(this.value)">
                                <div class="password-strength mt-2"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 