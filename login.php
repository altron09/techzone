<?php
// Database configuration
$servername = "localhost"; // XAMPP default MySQL host
$username = "root";        // XAMPP default username
$password = "";            // XAMPP default password (blank)
$dbname = "techzone";      // Your database name - create this in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $response['message'] = "Please fill in all fields";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email, password, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password (assuming passwords are hashed with password_hash())
            if ($password==$user['password']) {
                // Password is correct, start a session
                session_start();
                
                // Store user data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                $response['success'] = true;
                $response['message'] = "Login successful";
                $response['redirect'] = "dashboard.php"; // Redirect to dashboard after login
            } else {
                $response['message'] = "Invalid email or password $email";
            }
        } else {
            $response['message'] = "Invalid email or password ";
        }
        
        $stmt->close();
    }
    
    // Send JSON response if it's an AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else if ($response['success']) {
        // Standard form submission - redirect on success
        header('Location: ' . $response['redirect']);
        exit;
    }
}

// Close connection
$conn->close();
?>