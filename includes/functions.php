<?php
/**
 * General utility functions for the website
 */

/**
 * Escape string for database queries (deprecated - use prepared statements instead)
 */
function escape_string($string) {
    global $mysqli;
    return $mysqli->real_escape_string($string);
}

/**
 * Sanitize user input
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Format date for display
 */
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Redirect to login if not authenticated
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Get current logged in user details
 */
function get_current_user() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['admin_id'])) {
        return null;
    }
    
    global $mysqli;
    $admin_id = $_SESSION['admin_id'];
    
    $stmt = $mysqli->prepare("SELECT id, username FROM admin_users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Count total contacts
 */
function get_total_contacts() {
    global $mysqli;
    $result = $mysqli->query("SELECT COUNT(*) as total FROM contacts");
    $row = $result->fetch_assoc();
    return $row['total'];
}

/**
 * Count unread contacts
 */
function get_unread_contacts() {
    global $mysqli;
    $result = $mysqli->query("SELECT COUNT(*) as total FROM contacts WHERE read_status = 0");
    $row = $result->fetch_assoc();
    return $row['total'];
}

/**
 * Get site statistics for dashboard
 */
function get_dashboard_stats() {
    global $mysqli;
    
    $stats = array();
    
    // Total contacts
    $result = $mysqli->query("SELECT COUNT(*) as total FROM contacts");
    $row = $result->fetch_assoc();
    $stats['total_contacts'] = $row['total'];
    
    // Unread contacts
    $result = $mysqli->query("SELECT COUNT(*) as total FROM contacts WHERE read_status = 0");
    $row = $result->fetch_assoc();
    $stats['unread_contacts'] = $row['total'];
    
    // Total services
    $result = $mysqli->query("SELECT COUNT(*) as total FROM services WHERE active = 1");
    $row = $result->fetch_assoc();
    $stats['total_services'] = $row['total'];
    
    // Total portfolio items
    $result = $mysqli->query("SELECT COUNT(*) as total FROM portfolio WHERE active = 1");
    $row = $result->fetch_assoc();
    $stats['total_portfolio'] = $row['total'];
    
    return $stats;
}

/**
 * Send email notification
 */
function send_admin_email($name, $email, $phone, $subject, $message) {
    global $mysqli;
    
    // Get admin email from settings
    $result = $mysqli->query("SELECT setting_value FROM site_settings WHERE setting_name = 'admin_email' LIMIT 1");
    $row = $result->fetch_assoc();
    $admin_email = $row['setting_value'];
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    
    $email_body = "
    <h3>New Contact Form Submission</h3>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Subject:</strong> $subject</p>
    <p><strong>Message:</strong></p>
    <p>" . nl2br($message) . "</p>
    ";
    
    mail($admin_email, "New Contact: " . $subject, $email_body, $headers);
}

?>
