<?php
/**
 * Contact form processor
 * Handles form submission and stores in database
 */

header('Content-Type: application/json');

require_once 'includes/config.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

$response = array('success' => false, 'message' => '');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject)) {
        $response['message'] = 'Please fill in all required fields.';
        echo json_encode($response);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please provide a valid email address.';
        echo json_encode($response);
        exit;
    }
    
    // Prepare insert statement
    $stmt = $mysqli->prepare("INSERT INTO contacts (name, email, phone, subject, message, created_at, read_status) VALUES (?, ?, ?, ?, ?, NOW(), 0)");
    
    if (!$stmt) {
        $response['message'] = 'Database error: ' . $mysqli->error;
        echo json_encode($response);
        exit;
    }
    
    // Bind parameters (s = string)
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    
    // Execute statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Thank you! Your message has been sent successfully. I will get back to you soon.';
        
        // Optional: Send email notification to admin
        // send_admin_email($name, $email, $phone, $subject, $message);
        
    } else {
        $response['message'] = 'Error submitting form: ' . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
