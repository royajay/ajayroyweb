<?php
/**
 * Manage Skills
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
        $skill_name = sanitize_input($_POST['skill_name']);
        $proficiency = intval($_POST['proficiency']);
        
        if (empty($skill_name) || $proficiency < 0 || $proficiency > 100) {
            $error = 'Please fill in valid information';
        } else {
            $stmt = $mysqli->prepare("INSERT INTO skills (skill_name, proficiency, active) VALUES (?, ?, 1)");
            $stmt->bind_param('si', $skill_name, $proficiency);
            if ($stmt->execute()) {
                $message = 'Skill added successfully!';
            } else {
                $error = 'Error adding skill';
            }
            $stmt->close();
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $skill_name = sanitize_input($_POST['skill_name']);
        $proficiency = intval($_POST['proficiency']);
        
        if (empty($skill_name) || $proficiency < 0 || $proficiency > 100) {
            $error = 'Please fill in valid information';
        } else {
            $stmt = $mysqli->prepare("UPDATE skills SET skill_name=?, proficiency=? WHERE id=?");
            $stmt->bind_param('sii', $skill_name, $proficiency, $id);
            if ($stmt->execute()) {
                $message = 'Skill updated successfully!';
            } else {
                $error = 'Error updating skill';
            }
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $mysqli->prepare("DELETE FROM skills WHERE id=?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Skill deleted successfully!';
        } else {
            $error = 'Error deleting skill';
        }
        $stmt->close();
    }
}

// Get all skills
$query = "SELECT * FROM skills ORDER BY order_by ASC";
$result = $mysqli->query($query);
$skills = array();
while ($row = $result->fetch_assoc()) {
    $skills[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills - Admin</title>
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
        input[type="number"] {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 1rem;
        }
        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
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
        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }
        .progress {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
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
            grid-template-columns: 2fr 1fr;
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
        <h1>💡 Manage Skills</h1>
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

        <!-- Add Skill Form -->
        <div class="content-card">
            <h2>Add New Skill</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="skill_name">Skill Name *</label>
                        <input type="text" id="skill_name" name="skill_name" required>
                    </div>
                    <div class="form-group">
                        <label for="proficiency">Proficiency (0-100) *</label>
                        <input type="number" id="proficiency" name="proficiency" min="0" max="100" value="85" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Skill</button>
            </form>
        </div>

        <!-- Skills List -->
        <div class="content-card">
            <h2>All Skills</h2>
            
            <?php if (empty($skills)): ?>
            <p style="color: #999; text-align: center; padding: 2rem;">No skills found.</p>
            <?php else: ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Skill Name</th>
                        <th>Proficiency</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $skill): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong></td>
                        <td><?php echo $skill['proficiency']; ?>%</td>
                        <td>
                            <div class="progress-bar" style="width: 200px;">
                                <div class="progress" style="width: <?php echo $skill['proficiency']; ?>%;"></div>
                            </div>
                        </td>
                        <td>
                            <button class="action-btn" onclick="editSkill(<?php echo htmlspecialchars(json_encode($skill)); ?>)">✏️ Edit</button>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete this skill?');">
                                <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
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

    <script>
        function editSkill(skill) {
            const skill_name = prompt('Skill Name:', skill.skill_name);
            if (skill_name === null) return;
            
            const proficiency = prompt('Proficiency (0-100):', skill.proficiency);
            if (proficiency === null) return;

            if (proficiency < 0 || proficiency > 100) {
                alert('Proficiency must be between 0 and 100');
                return;
            }

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="${skill.id}">
                <input type="hidden" name="skill_name" value="${skill_name}">
                <input type="hidden" name="proficiency" value="${proficiency}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
