<?php
/**
 * Database Configuration File
 * Update these credentials for your local or production environment
 */

// Database Connection Details
define('DB_HOST', 'localhost');           // Database host
define('DB_USER', 'root');                // Database username (default for localhost)
define('DB_PASS', '');                    // Database password (empty for localhost)
define('DB_NAME', 'ajay_roy_portfolio');  // Database name

// Site Configuration
define('SITE_NAME', 'Ajay Roy - SEO Specialist & Digital Marketer');
define('SITE_URL', 'http://localhost:8000');
define('ADMIN_EMAIL', 'admin@rayajay.com.np');

// Error Reporting (Set to 0 in production)
define('DEBUG_MODE', true);

// Try to establish database connection
try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($mysqli->connect_error) {
        throw new Exception('Connection Error: ' . $mysqli->connect_error);
    }
    
    // Set charset to UTF-8
    $mysqli->set_charset('utf8mb4');
    
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "Error: " . $e->getMessage();
    } else {
        echo "Database connection error. Please contact administrator.";
    }
    die();
}

// Function to escape strings for safety
function escape_string($string) {
    global $mysqli;
    return $mysqli->real_escape_string($string);
}

// Function to sanitize input
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

?>
