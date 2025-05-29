<?php
// Database configuration
$db_host = "localhost";  // MySQL server (usually 'localhost')
$db_user = "root";       // Default XAMPP username
$db_pass = "";           // Default XAMPP password (empty)
$db_name = "techzone";  // Your database name

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Create connection using mysqli (object-oriented style)
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check connection
       if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
    // Uncomment to test connection (then remove after testing)
    // echo "Connected successfully to database: " . $db_name;
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>