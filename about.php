<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - E-Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- About Hero Section -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 mb-4">About Our Store</h1>
                <p class="lead mb-4">Your trusted destination for quality technology products and exceptional service.</p>
                <p class="text-muted">Founded with a passion for technology and customer satisfaction, we've grown to become a leading provider of tech products and solutions. Our commitment to quality, innovation, and customer service sets us apart in the industry.</p>
            </div>
            <div class="col-md-6">
                <img src="assets/images/about-hero.jpg" alt="About Us" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>

    <!-- Our Values Section -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                        <h4>Quality First</h4>
                        <p class="text-muted">We carefully select each product to ensure the highest quality standards for our customers.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-heart fa-3x mb-3 text-primary"></i>
                        <h4>Customer Focus</h4>
                        <p class="text-muted">Your satisfaction is our priority. We're dedicated to providing exceptional service and support.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-lightbulb fa-3x mb-3 text-primary"></i>
                        <h4>Innovation</h4>
                        <p class="text-muted">We stay ahead of the curve by offering the latest technology and innovative solutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Our Team</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="assets/images/team1.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">John Doe</h5>
                        <p class="card-text text-muted">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="assets/images/team2.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Jane Smith</h5>
                        <p class="card-text text-muted">Customer Service Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="assets/images/team3.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mike Johnson</h5>
                        <p class="card-text text-muted">Technical Director</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="assets/images/team4.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Sarah Williams</h5>
                        <p class="card-text text-muted">Marketing Manager</p>
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