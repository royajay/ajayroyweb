<?php
/**
 * Admin: Google Reviews Settings
 */
session_start();
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$message_type = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save_settings') {
    $google_api_key = $mysqli->real_escape_string($_POST['google_api_key']);
    $google_place_id = $mysqli->real_escape_string($_POST['google_place_id']);
    
    // Update or insert settings
    $settings = array(
        'google_api_key' => $google_api_key,
        'google_place_id' => $google_place_id
    );
    
    $all_saved = true;
    foreach ($settings as $key => $value) {
        $check_query = "SELECT id FROM site_settings WHERE setting_name = '$key'";
        $check_result = $mysqli->query($check_query);
        
        if ($check_result && $check_result->num_rows > 0) {
            // Update
            $update_query = "UPDATE site_settings SET setting_value = '$value' WHERE setting_name = '$key'";
            if (!$mysqli->query($update_query)) {
                $all_saved = false;
            }
        } else {
            // Insert
            $insert_query = "INSERT INTO site_settings (setting_name, setting_value) VALUES ('$key', '$value')";
            if (!$mysqli->query($insert_query)) {
                $all_saved = false;
            }
        }
    }
    
    if ($all_saved) {
        $message = 'Google Reviews settings saved successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error saving settings: ' . $mysqli->error;
        $message_type = 'error';
    }
}

// Get current settings
$settings = array();
$settings_query = "SELECT setting_name, setting_value FROM site_settings WHERE setting_name IN ('google_api_key', 'google_place_id')";
$settings_result = $mysqli->query($settings_query);

if ($settings_result) {
    while ($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}

// Test Google API
$test_result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'test_api') {
    $api_key = isset($settings['google_api_key']) ? $settings['google_api_key'] : '';
    $place_id = isset($settings['google_place_id']) ? $settings['google_place_id'] : '';
    
    if (empty($api_key) || empty($place_id)) {
        $test_result = '<div class="message error">Please save API key and Place ID first</div>';
    } else {
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . "&key=" . urlencode($api_key) . "&fields=rating,reviews,name";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            if (isset($data['result']['reviews'])) {
                $review_count = count($data['result']['reviews']);
                $rating = $data['result']['rating'] ?? 'N/A';
                $place_name = $data['result']['name'] ?? 'Location';
                $test_result = '<div class="message success">✓ Connection successful!<br>Place: ' . htmlspecialchars($place_name) . '<br>Rating: ' . htmlspecialchars($rating) . '<br>Reviews: ' . $review_count . '</div>';
            } else {
                $test_result = '<div class="message error">No reviews found for this place</div>';
            }
        } else {
            $test_result = '<div class="message error">Connection failed (HTTP ' . $http_code . '). Check your API key and Place ID.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Reviews Settings</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(90deg, #0052CC 0%, #0066FF 100%);
            color: #fff;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        header h1 {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .controls {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0052CC;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #003d99;
        }
        .btn-secondary {
            background: #666;
        }
        .btn-secondary:hover {
            background: #555;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .settings-box {
            background: #fff;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
        }
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #0052CC;
        }
        .input-help {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 1.3rem;
            color: #0052CC;
            margin-bottom: 15px;
            border-bottom: 2px solid #0052CC;
            padding-bottom: 10px;
        }
        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #0052CC;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .info-box h4 {
            color: #0052CC;
            margin-bottom: 8px;
        }
        .info-box p {
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .info-box ol {
            margin-left: 20px;
            margin-top: 10px;
        }
        .info-box li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Google Reviews Settings</h1>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="controls">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>

        <div class="settings-box">
            <!-- Information Section -->
            <div class="section">
                <div class="section-title">How to Set Up Google Places API</div>
                <div class="info-box">
                    <h4>📍 Step 1: Get Your Google Place ID</h4>
                    <p>You can use this free tool to find your business's Place ID:</p>
                    <p><strong><a href="https://developers.google.com/maps/documentation/places/web-service/overview" target="_blank" style="color: #0052CC;">Google Places API Documentation</a></strong></p>
                    <p>Or visit: <a href="https://www.google.com/maps" target="_blank" style="color: #0052CC;">Google Maps</a> → Search your business → Copy the Place ID from the URL</p>
                </div>

                <div class="info-box">
                    <h4>🔑 Step 2: Create Google Cloud Project & Get API Key</h4>
                    <ol>
                        <li>Go to <a href="https://console.cloud.google.com/" target="_blank" style="color: #0052CC;">Google Cloud Console</a></li>
                        <li>Create a new project</li>
                        <li>Enable "Places API" and "Maps JavaScript API"</li>
                        <li>Go to Credentials → Create API Key</li>
                        <li>Restrict the key to "Places API"</li>
                        <li>Copy the API Key and paste it below</li>
                    </ol>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="section">
                <div class="section-title">Configuration</div>
                <form method="POST">
                    <input type="hidden" name="action" value="save_settings">

                    <div class="form-group">
                        <label for="google_api_key">Google Places API Key *</label>
                        <input type="text" id="google_api_key" name="google_api_key" 
                               value="<?php echo isset($settings['google_api_key']) ? htmlspecialchars($settings['google_api_key']) : ''; ?>"
                               placeholder="AIzaSyD...your-api-key-here...">
                        <div class="input-help">Your API key for accessing Google Places API</div>
                    </div>

                    <div class="form-group">
                        <label for="google_place_id">Google Place ID *</label>
                        <input type="text" id="google_place_id" name="google_place_id" 
                               value="<?php echo isset($settings['google_place_id']) ? htmlspecialchars($settings['google_place_id']) : ''; ?>"
                               placeholder="ChIJ...your-place-id-here...">
                        <div class="input-help">The unique identifier for your business location on Google Maps</div>
                    </div>

                    <button type="submit" class="btn">💾 Save Settings</button>
                </form>
            </div>

            <!-- Test Connection -->
            <div class="section">
                <div class="section-title">Test Connection</div>
                <form method="POST">
                    <input type="hidden" name="action" value="test_api">
                    <button type="submit" class="btn">🧪 Test Google Connection</button>
                </form>
                <?php if ($test_result): echo $test_result; endif; ?>
            </div>

            <!-- Usage Info -->
            <div class="section">
                <div class="section-title">How It Works</div>
                <div class="info-box">
                    <h4>✓ Review Loading Process</h4>
                    <ol>
                        <li>Website fetches reviews from your Google Places API</li>
                        <li>Reviews display with "Verified Google Review" badge</li>
                        <li>Reviews are also synced to local database for backup/caching</li>
                        <li>If Google API fails, falls back to local reviews</li>
                        <li>Reviews update automatically each time page loads</li>
                    </ol>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="section">
                <div class="section-title">Troubleshooting</div>
                <div class="info-box">
                    <h4>❌ Common Issues</h4>
                    <p><strong>No reviews showing?</strong></p>
                    <ul style="margin-left: 20px; margin-top: 8px;">
                        <li>Make sure API key is correct and active</li>
                        <li>Make sure Place ID is correct</li>
                        <li>Check that Places API is enabled in Google Cloud Console</li>
                        <li>Allow a few minutes for API changes to take effect</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
