<?php
/**
 * Manage Statistics
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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'edit') {
        $id = intval($_POST['id']);
        $stat_value = intval($_POST['stat_value']);
        $icon = sanitize_input($_POST['icon']);
        
        if ($stat_value < 0) {
            $error = 'Please enter a valid value';
        } else {
            $stmt = $mysqli->prepare("UPDATE statistics SET stat_value=?, icon=? WHERE id=?");
            $stmt->bind_param('isi', $stat_value, $icon, $id);
            if ($stmt->execute()) {
                $message = 'Statistic updated successfully!';
            } else {
                $error = 'Error updating statistic';
            }
            $stmt->close();
        }
    }
}

// Get all statistics
$query = "SELECT * FROM statistics ORDER BY stat_name ASC";
$result = $mysqli->query($query);
$stats = array();
while ($row = $result->fetch_assoc()) {
    $stats[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Statistics - Admin</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .content-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .content-card h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .stat-card {
            background: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            transition: border-color 0.3s;
        }
        .stat-card:hover {
            border-color: #667eea;
        }
        .stat-name {
            font-size: 0.9rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .stat-display {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            font-size: 2.5rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        .stat-edit-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: underline;
            margin-top: 1rem;
        }
        .stat-edit-btn:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>📊 Manage Statistics</h1>
        <div>
            <a href="dashboard.php" class="back-btn">← Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="content-card">
            <h2>Key Metrics</h2>
            <p style="color: #666; margin-bottom: 2rem;">Edit the key statistics displayed on your website homepage.</p>
            
            <?php if (empty($stats)): ?>
            <p style="color: #999; text-align: center; padding: 2rem;">No statistics found.</p>
            <?php else: ?>
            
            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                <div class="stat-card">
                    <div class="stat-name"><?php echo htmlspecialchars($stat['stat_name']); ?></div>
                    <div class="stat-display">
                        <div class="stat-icon"><?php echo htmlspecialchars($stat['icon']); ?></div>
                        <div class="stat-value"><?php echo $stat['stat_value']; ?></div>
                    </div>
                    <button class="stat-edit-btn" onclick="editStat(<?php echo htmlspecialchars(json_encode($stat)); ?>)">✏️ Edit</button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </div>
    </div>

    <script>
        function editStat(stat) {
            const stat_value = prompt('Value:', stat.stat_value);
            if (stat_value === null) return;
            
            const icon = prompt('Icon (emoji):', stat.icon);
            if (icon === null) return;

            if (isNaN(stat_value) || parseInt(stat_value) < 0) {
                alert('Please enter a valid number');
                return;
            }

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="${stat.id}">
                <input type="hidden" name="stat_value" value="${stat_value}">
                <input type="hidden" name="icon" value="${icon}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
