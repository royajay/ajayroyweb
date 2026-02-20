<?php
/**
 * System Check & Diagnostics
 * 
 * Use this file to verify your installation
 * Access via: http://localhost/AjayRoy-Website-PHP/system-check.php
 * 
 * ⚠️ DELETE THIS FILE BEFORE GOING LIVE ⚠️
 */

require_once 'includes/config.php';

$checks = array();
$all_good = true;

// Check 1: PHP Version
$php_version_ok = version_compare(PHP_VERSION, '7.4.0', '>=');
$checks[] = array(
    'name' => 'PHP Version',
    'status' => $php_version_ok,
    'info' => PHP_VERSION,
    'required' => '7.4+'
);
if (!$php_version_ok) $all_good = false;

// Check 2: MySQL Extension
$mysqli_ok = extension_loaded('mysqli');
$checks[] = array(
    'name' => 'MySQLi Extension',
    'status' => $mysqli_ok,
    'info' => $mysqli_ok ? 'Loaded' : 'NOT LOADED',
    'required' => 'Required'
);
if (!$mysqli_ok) $all_good = false;

// Check 3: Database Connection
$db_ok = false;
$db_info = '';
if (!mysqli_connect_error()) {
    $db_ok = true;
    $db_info = 'Connected to ' . DB_NAME . ' on ' . DB_HOST;
} else {
    $db_info = mysqli_connect_error();
}
$checks[] = array(
    'name' => 'Database Connection',
    'status' => $db_ok,
    'info' => $db_info,
    'required' => 'Required'
);
if (!$db_ok) $all_good = false;

// Check 4: Tables
$required_tables = array(
    'contacts',
    'services',
    'portfolio',
    'skills',
    'statistics',
    'testimonials',
    'site_settings',
    'admin_users'
);

$missing_tables = array();
$existing_tables = 0;

if ($db_ok) {
    $result = $mysqli->query("SHOW TABLES");
    $tables = array();
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    foreach ($required_tables as $table) {
        if (!in_array($table, $tables)) {
            $missing_tables[] = $table;
        } else {
            $existing_tables++;
        }
    }
}

$tables_ok = empty($missing_tables) && $existing_tables == count($required_tables);
$checks[] = array(
    'name' => 'Database Tables',
    'status' => $tables_ok,
    'info' => $existing_tables . '/' . count($required_tables) . ' found' . (count($missing_tables) > 0 ? ' (Missing: ' . implode(', ', $missing_tables) . ')' : ''),
    'required' => 'All ' . count($required_tables) . ' tables'
);
if (!$tables_ok) $all_good = false;

// Check 5: Sample Data
$services_ok = false;
$services_count = 0;
if ($db_ok && $tables_ok) {
    $result = $mysqli->query("SELECT COUNT(*) as count FROM services");
    $row = $result->fetch_assoc();
    $services_count = $row['count'];
    $services_ok = $services_count > 0;
}
$checks[] = array(
    'name' => 'Sample Services',
    'status' => $services_ok,
    'info' => $services_count . ' services found',
    'required' => '1+'
);

// Check 6: Admin User
$admin_ok = false;
$admin_count = 0;
if ($db_ok && $tables_ok) {
    $result = $mysqli->query("SELECT COUNT(*) as count FROM admin_users");
    $row = $result->fetch_assoc();
    $admin_count = $row['count'];
    $admin_ok = $admin_count > 0;
}
$checks[] = array(
    'name' => 'Admin User',
    'status' => $admin_ok,
    'info' => $admin_count . ' admin user(s) found',
    'required' => '1+'
);

// Check 7: File Permissions (warnings only)
$files_to_check = array(
    'includes/config.php' => is_readable('includes/config.php'),
    'admin/' => is_dir('admin'),
    'api/' => is_dir('api'),
    'database.sql' => file_exists('database.sql')
);

$files_ok = true;
$missing_files = array();
foreach ($files_to_check as $file => $exists) {
    if (!$exists) {
        $files_ok = false;
        $missing_files[] = $file;
    }
}

$checks[] = array(
    'name' => 'Required Files',
    'status' => $files_ok,
    'info' => $files_ok ? 'All files present' : 'Missing: ' . implode(', ', $missing_files),
    'required' => 'All essential files'
);
if (!$files_ok) $all_good = false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - Ajay Roy Portfolio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 2rem;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #252526;
            border: 1px solid #3e3e42;
            border-radius: 4px;
            padding: 2rem;
        }
        h1 {
            color: #4ec9b0;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        .subtitle {
            color: #858585;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            vertical-align: middle;
        }
        .status-ok {
            background: #4ec9b0;
        }
        .status-fail {
            background: #f48771;
        }
        .status-warning {
            background: #dcdcaa;
        }
        .check-item {
            display: grid;
            grid-template-columns: 30px 1fr auto;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #3e3e42;
            align-items: center;
        }
        .check-item:last-child {
            border-bottom: none;
        }
        .check-name {
            font-weight: bold;
            color: #9cdcfe;
        }
        .check-info {
            color: #858585;
            font-size: 0.85rem;
        }
        .check-required {
            color: #ce9178;
            font-size: 0.8rem;
            text-align: right;
        }
        .summary {
            background: #1e1e1e;
            border-left: 3px solid #4ec9b0;
            padding: 1rem;
            margin-top: 2rem;
        }
        .summary.fail {
            border-left-color: #f48771;
        }
        .summary h2 {
            color: #4ec9b0;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        .summary.fail h2 {
            color: #f48771;
        }
        .summary p {
            color: #858585;
            font-size: 0.9rem;
        }
        .action {
            margin-top: 2rem;
            padding: 1rem;
            background: #1e1e1e;
            border: 1px solid #3e3e42;
            border-radius: 4px;
        }
        .action-title {
            color: #4ec9b0;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .action ul {
            list-style: none;
            padding-left: 1rem;
        }
        .action li:before {
            content: "→ ";
            color: #858585;
        }
        code {
            background: #1e1e1e;
            padding: 2px 4px;
            border-radius: 2px;
            color: #ce9178;
        }
        .warning {
            color: #f48771;
            font-weight: bold;
        }
        a {
            color: #4ec9b0;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ System Check & Diagnostics</h1>
        <p class="subtitle">Verify your Ajay Roy Portfolio installation</p>

        <div>
            <?php foreach ($checks as $check): ?>
            <div class="check-item">
                <div>
                    <span class="status-indicator <?php echo $check['status'] ? 'status-ok' : 'status-fail'; ?>"></span>
                </div>
                <div>
                    <div class="check-name">
                        <?php echo $check['status'] ? '✓' : '✗'; ?> 
                        <?php echo $check['name']; ?>
                    </div>
                    <?php if (!empty($check['info'])): ?>
                    <div class="check-info"><?php echo htmlspecialchars($check['info']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="check-required"><?php echo $check['required']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Summary -->
        <div class="summary <?php echo !$all_good ? 'fail' : ''; ?>">
            <h2><?php echo $all_good ? '✅ All Systems GO!' : '❌ Issues Found'; ?></h2>
            <p>
                <?php if ($all_good): ?>
                    Your installation is properly configured. You can proceed with development!
                <?php else: ?>
                    Please resolve the issues shown above before using the application.
                <?php endif; ?>
            </p>
        </div>

        <!-- Action Items -->
        <div class="action">
            <div class="action-title">Next Steps:</div>
            <ul>
                <?php if (!$all_good): ?>
                    <li>Review the failed checks above</li>
                    <li>Check INSTALLATION.md for detailed setup guide</li>
                    <li>Ensure all database tables are imported from database.sql</li>
                    <li>Verify database credentials in includes/config.php</li>
                <?php else: ?>
                    <li><a href="index.php">View Website →</a></li>
                    <li><a href="admin/login.php">Admin Login →</a></li>
                    <li>Start customizing your content</li>
                    <li><span class="warning">DELETE THIS FILE</span> (system-check.php) when deploying to production</li>
                <?php endif; ?>
            </ul>
        </div>

        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #3e3e42; font-size: 0.8rem; color: #858585;">
            <p>This diagnostic tool helps verify your installation.</p>
            <p><span class="warning">⚠️ DELETE THIS FILE (system-check.php) BEFORE DEPLOYING TO PRODUCTION</span></p>
            <p>For help, see <code>INSTALLATION.md</code> or <code>README.md</code></p>
        </div>
    </div>
</body>
</html>
