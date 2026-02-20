<?php
/**
 * Manage Portfolio
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
    
    if ($action === 'add') {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $category = sanitize_input($_POST['category']);
        
        if (empty($title) || empty($description) || empty($category)) {
            $error = 'Please fill in all required fields';
        } else {
            $stmt = $mysqli->prepare("INSERT INTO portfolio (title, description, category, active) VALUES (?, ?, ?, 1)");
            $stmt->bind_param('sss', $title, $description, $category);
            if ($stmt->execute()) {
                $message = 'Portfolio item added successfully!';
            } else {
                $error = 'Error adding portfolio item';
            }
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $mysqli->prepare("DELETE FROM portfolio WHERE id=?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Portfolio item deleted successfully!';
        } else {
            $error = 'Error deleting portfolio item';
        }
        $stmt->close();
    }
}

// Get all portfolio items
$query = "SELECT * FROM portfolio ORDER BY order_by ASC";
$result = $mysqli->query($query);
$portfolio = array();
while ($row = $result->fetch_assoc()) {
    $portfolio[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Portfolio - Admin</title>
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
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1rem;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: #fff;
        }
        .btn-primary:hover {
            background: #764ba2;
        }
        .btn-danger {
            background: #e74c3c;
            color: #fff;
        }
        .btn-danger:hover {
            background: #c0392b;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #f0f0f0;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        table tr:hover {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #f0f0f0;
            color: #333;
        }
        .action-btn {
            background: none;
            border: none;
            padding: 0.3rem 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: #667eea;
            margin-right: 0.5rem;
        }
        .action-btn:hover {
            text-decoration: underline;
        }
        .delete-btn {
            color: #e74c3c;
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
        <h1>📁 Manage Portfolio</h1>
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

        <!-- Add Portfolio Form -->
        <div class="content-card">
            <h2>Add New Project</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Project Title *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="SEO">SEO</option>
                            <option value="Digital Marketing">Digital Marketing</option>
                            <option value="Content Marketing">Content Marketing</option>
                            <option value="Social Media">Social Media</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Project Description *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Add Project</button>
            </form>
        </div>

        <!-- Portfolio List -->
        <div class="content-card">
            <h2>All Projects</h2>
            
            <?php if (empty($portfolio)): ?>
            <p style="color: #999; text-align: center; padding: 2rem;">No portfolio items found.</p>
            <?php else: ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolio as $item): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                        <td><span class="badge"><?php echo htmlspecialchars($item['category']); ?></span></td>
                        <td><?php echo htmlspecialchars(substr($item['description'], 0, 60)) . (strlen($item['description']) > 60 ? '...' : ''); ?></td>
                        <td>
                            <span class="badge" style="background: <?php echo $item['active'] ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $item['active'] ? '#155724' : '#721c24'; ?>;">
                                <?php echo $item['active'] ? '✓ Active' : '✗ Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete this project?');">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="action-btn delete-btn">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
