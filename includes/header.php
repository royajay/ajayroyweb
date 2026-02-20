<?php
// Shared header - outputs <head> and navigation
require_once __DIR__ . '/config.php';

$settings = [];
$settings_result = $mysqli->query("SELECT setting_name, setting_value FROM site_settings");
if ($settings_result) {
    while ($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Ajay Roy - Portfolio'; ?></title>
    <meta name="description" content="<?php echo isset($settings['site_description']) ? htmlspecialchars($settings['site_description']) : 'Professional SEO and Digital Marketing services'; ?>">
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>

    <nav>
        <div class="nav-container">
            <div class="logo">
                <img src="images/logo.png" alt="Ajay Roy Logo">
                <span class="logo-text">Ajay Roy</span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="index.php" <?php echo ($current === 'index.php') ? 'class="active"' : ''; ?>>Home</a>
                </li>
                <li>
                    <a href="about.php" <?php echo ($current === 'about.php') ? 'class="active"' : ''; ?>>About</a>
                </li>
                <li>
                    <a href="services.php" <?php echo ($current === 'services.php') ? 'class="active"' : ''; ?>>Services</a>
                </li>
                <li>
                    <a href="portfolio.php" <?php echo ($current === 'portfolio.php') ? 'class="active"' : ''; ?>>Portfolio</a>
                </li>
                <li>
                    <a href="gallery.php" <?php echo ($current === 'gallery.php') ? 'class="active"' : ''; ?>>Gallery</a>
                </li>
                <li>
                    <a href="contact.php" <?php echo ($current === 'contact.php') ? 'class="active"' : ''; ?>>Contact</a>
                </li>
                <li>
                    <a href="admin/" style="background: #0052CC; padding: 0.5rem 1rem; border-radius: 4px;">Admin</a>
                </li>
            </ul>

            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Page Content Starts -->
