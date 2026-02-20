<?php
/**
 * Site Settings
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_SESSION)) {
    session_start();
}
require_login();

$user = get_current_user();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = sanitize_input($_POST['site_name']);
    $site_description = sanitize_input($_POST['site_description']);
    $admin_email = sanitize_input($_POST['admin_email']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    $instagram_url = sanitize_input($_POST['instagram_url']);
    $linkedin_url = sanitize_input($_POST['linkedin_url']);
    $whatsapp = sanitize_input($_POST['whatsapp']);
    
    $updates = array();
    
    // Update each setting
    $settings_data = array(
        'site_name' => $site_name,
        'site_description' => $site_description,
        'admin_email' => $admin_email,
        'phone' => $phone,
        'address' => $address,
        'instagram_url' => $instagram_url,
        'linkedin_url' => $linkedin_url,
        'whatsapp' => $whatsapp
    );
    
    foreach ($settings_data as $key => $value) {
        $stmt = $mysqli->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_name = ?");
        $stmt->bind_param('ss', $value, $key);
        $stmt->execute();
        $stmt->close();
    }
    
    $message = 'Settings updated successfully!';
}

// Get current settings
$settings = array();
$query = "SELECT setting_name, setting_value FROM site_settings";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_name']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-btn, .logout-btn {
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .back-btn:hover, .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .content-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .content-card h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-section h3 {
            color: #667eea;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1rem;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .btn {
            padding: 0.8rem 2rem;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #764ba2;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>⚙️ Site Settings</h1>
        <div>
            <a href="dashboard.php" class="back-btn">← Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2>Website Configuration</h2>
            
            <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Site Information -->
                <div class="form-section">
                    <h3>Site Information</h3>
                    
                    <div class="form-group">
                        <label for="site_name">Website Name</label>
                        <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'Ajay Roy'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="site_description">Site Description</label>
                        <textarea id="site_description" name="site_description"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3>Contact Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="admin_email">Email Address</label>
                            <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email'] ?? 'admin@rayajay.com.np'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? '+977 9745232233'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? 'Kathmandu, Nepal'); ?>">
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="form-section">
                    <h3>Social Media Links</h3>
                    
                    <div class="form-group">
                        <label for="instagram_url">Instagram Profile URL</label>
                        <input type="text" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/yourusername" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn Profile URL</label>
                        <input type="text" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/in/yourprofile" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="whatsapp">WhatsApp Number</label>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="+977 9745232233" value="<?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?>">
                    </div>
                </div>

                <button type="submit" class="btn">💾 Save Settings</button>
            </form>
        </div>
    </div>
</body>
</html>
