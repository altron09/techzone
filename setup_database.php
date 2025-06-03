<?php
require_once 'config/database.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Read the SQL file
    $sql = file_get_contents('database_setup.sql');
    
    // Split the SQL file into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each query
    foreach ($queries as $query) {
        if (!empty($query)) {
            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error executing query: " . mysqli_error($conn) . "\nQuery: " . $query);
            }
        }
    }
    
    echo "Database setup completed successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 