<?php
/**
 * Manage Contacts - View all contact submissions
 */

require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_SESSION)) {
    session_start();
}
require_login();

$user = get_current_user();

// Handle mark as read/unread
if (isset($_POST['action']) && isset($_POST['contact_id'])) {
    $contact_id = intval($_POST['contact_id']);
    $action = $_POST['action'];
    
    if ($action === 'mark_read') {
        $stmt = $mysqli->prepare("UPDATE contacts SET read_status = 1 WHERE id = ?");
        $stmt->bind_param('i', $contact_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'delete') {
        $stmt = $mysqli->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param('i', $contact_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get all contacts
$query = "SELECT id, name, email, phone, subject, message, created_at, read_status FROM contacts ORDER BY created_at DESC";
$result = $mysqli->query($query);
$contacts = array();
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts - Admin</title>
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
        }
        .content-card h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .modal-content h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .modal-content p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-primary {
            background: #667eea;
            color: #fff;
        }
        .btn-secondary {
            background: #ddd;
            color: #333;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>📬 Contact Submissions</h1>
        <div>
            <a href="dashboard.php" class="back-btn">← Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2>All Messages</h2>
            
            <?php if (empty($contacts)): ?>
            <div class="empty-state">
                <p>No contacts yet. Messages will appear here when people submit the contact form.</p>
            </div>
            <?php else: ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td>
                            <a href="javascript:void(0)" onclick="showDetails(<?php echo $contact['id']; ?>)" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                <?php echo htmlspecialchars($contact['subject']); ?>
                            </a>
                        </td>
                        <td><?php echo date('F j, Y', strtotime($contact['created_at'])); ?></td>
                        <td>
                            <span class="badge <?php echo $contact['read_status'] == 0 ? 'badge-unread' : 'badge-read'; ?>">
                                <?php echo $contact['read_status'] == 0 ? 'Unread' : 'Read'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($contact['read_status'] == 0): ?>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                <input type="hidden" name="action" value="mark_read">
                                <button type="submit" class="action-btn">✓ Mark Read</button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Delete this message?');">
                                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="action-btn delete-btn">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr style="background: #f9f9f9; border: none; display:none;" class="details-row" id="details-<?php echo $contact['id']; ?>">
                        <td colspan="6">
                            <div style="padding: 1rem; background: #fff; border: 1px solid #ddd; border-radius: 4px;">
                                <p><strong>Phone:</strong> <?php echo !empty($contact['phone']) ? htmlspecialchars($contact['phone']) : 'Not provided'; ?></p>
                                <p style="margin-top: 1rem;"><strong>Message:</strong></p>
                                <p><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showDetails(contactId) {
            const detailsRow = document.getElementById('details-' + contactId);
            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }
    </script>
</body>
</html>
