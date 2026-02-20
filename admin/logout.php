<?php
/**
 * Admin Logout
 */

if (!isset($_SESSION)) {
    session_start();
}

// Destroy session
session_destroy();

// Clear session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Redirect to login
header('Location: login.php?message=logged_out');
exit;
?>
