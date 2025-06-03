<?php
session_start();
require_once 'config/database.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['name', 'email', 'subject', 'message'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }

        // Sanitize input
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Insert into database
        $query = "INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $subject, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Thank you for your message. We'll get back to you soon!";
            // Clear form data
            $_POST = array();
        } else {
            throw new Exception("Error sending message. Please try again.");
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Tech Zone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .contact-info i {
            color: #ff8533;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        .contact-form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .contact-form .form-control {
            border-radius: 25px;
            padding: 0.75rem 1.25rem;
        }
        .contact-form .form-control:focus {
            border-color: #ff8533;
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25);
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
        /* Submit Button Specific Styles */
        .contact-form .btn-submit {
            background-color: #ff8533;
            border-color: #ff8533;
            color: #fff;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
        }
        .contact-form .btn-submit:hover {
            background-color: #ff751a;
            border-color: #ff751a;
            color: #fff;
        }
        .contact-form .btn-submit:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 133, 51, 0.25);
        }
        /* Dark Button Override */
        .btn-dark {
            background-color: #ff8533 !important;
            border-color: #ff8533 !important;
            color: #fff !important;
        }
        .btn-dark:hover {
            background-color: #ff751a !important;
            border-color: #ff751a !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Contact Us</h1>
                        
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                        <?php endif; ?>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="contact.php" id="contact-form" onsubmit="return validateForm('contact-form')">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" 
                                          required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Other Ways to Reach Us</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                                <p>123 Tech Street<br>Silicon Valley, CA 94043</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-phone me-2"></i>Phone</h6>
                                <p>+1 (555) 123-4567</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-envelope me-2"></i>Email</h6>
                                <p>support@techzone.com</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-clock me-2"></i>Business Hours</h6>
                                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                                   Saturday: 10:00 AM - 4:00 PM</p>
                            </div>
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