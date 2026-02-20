<?php
/**
 * Admin Dashboard
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION)) {
    session_start();
}
require_login();

$user = get_current_user();
$stats = get_dashboard_stats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ajay Roy Portfolio</title>
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
        .admin-header h1 {
            font-size: 1.8rem;
        }
        .admin-header .logout-btn {
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .admin-header .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-top: 4px solid #667eea;
        }
        .stat-card h3 {
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }
        .stat-card .description {
            color: #666;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        .admin-menu {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .admin-menu h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .menu-btn {
            background: #f0f0f0;
            border: 2px solid #ddd;
            padding: 1rem;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            text-align: center;
            transition: all 0.3s;
            font-weight: 600;
        }
        .menu-btn:hover {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
        }
        .recent-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 2rem;
        }
        .recent-section h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #f0f0f0;
            padding: 0.8rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 0.8rem;
            border-bottom: 1px solid #ddd;
        }
        table tr:hover {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-unread {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-read {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>📊 Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <p style="color: #666; margin-bottom: 2rem;">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>! Manage your portfolio content here.</p>

        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>📧 Total Contacts</h3>
                <div class="number"><?php echo $stats['total_contacts']; ?></div>
                <div class="description">Contact submissions received</div>
            </div>
            <div class="stat-card">
                <h3>🔔 Unread Messages</h3>
                <div class="number" style="color: #e74c3c;"><?php echo $stats['unread_contacts']; ?></div>
                <div class="description">New messages waiting</div>
            </div>
            <div class="stat-card">
                <h3>🎯 Services</h3>
                <div class="number"><?php echo $stats['total_services']; ?></div>
                <div class="description">Active service listings</div>
            </div>
            <div class="stat-card">
                <h3>📁 Portfolio Items</h3>
                <div class="number"><?php echo $stats['total_portfolio']; ?></div>
                <div class="description">Project showcases</div>
            </div>
        </div>

        <!-- Management Menu -->
        <div class="admin-menu">
            <h2>Management Options</h2>
            <div class="menu-grid">
                <a href="contacts.php" class="menu-btn">📬 View Contacts</a>
                <a href="services.php" class="menu-btn">🎯 Manage Services</a>
                <a href="portfolio.php" class="menu-btn">📁 Manage Portfolio</a>
                <a href="skills.php" class="menu-btn">💡 Manage Skills</a>
                <a href="statistics.php" class="menu-btn">📊 Manage Stats</a>
                <a href="settings.php" class="menu-btn">⚙️ Site Settings</a>
            </div>
        </div>

        <!-- Recent Contacts -->
        <div class="recent-section">
            <h3>📨 Recent Contact Submissions</h3>
            <?php
            $query = "SELECT id, name, email, subject, created_at, read_status FROM contacts ORDER BY created_at DESC LIMIT 5";
            $result = $mysqli->query($query);
            
            if ($result && $result->num_rows > 0) {
                echo '<table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while ($row = $result->fetch_assoc()) {
                    $status_class = $row['read_status'] == 0 ? 'badge-unread' : 'badge-read';
                    $status_text = $row['read_status'] == 0 ? 'Unread' : 'Read';
                    echo '<tr>
                            <td>' . htmlspecialchars($row['name']) . '</td>
                            <td>' . htmlspecialchars($row['email']) . '</td>
                            <td>' . htmlspecialchars($row['subject']) . '</td>
                            <td>' . date('F j, Y', strtotime($row['created_at'])) . '</td>
                            <td><span class="badge ' . $status_class . '">' . $status_text . '</span></td>
                          </tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p style="color: #999; text-align: center; padding: 2rem;">No contacts yet.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
