<?php
/**
 * Admin: Manage Reviews
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

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = intval($_POST['id']);
    $delete_query = "DELETE FROM reviews WHERE id = $id";
    if ($mysqli->query($delete_query)) {
        $message = 'Review deleted successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error deleting review: ' . $mysqli->error;
        $message_type = 'error';
    }
}

// Handle toggle active status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'toggle') {
    $id = intval($_POST['id']);
    $toggle_query = "UPDATE reviews SET active = NOT active WHERE id = $id";
    if ($mysqli->query($toggle_query)) {
        $message = 'Review status updated!';
        $message_type = 'success';
    } else {
        $message = 'Error updating review: ' . $mysqli->error;
        $message_type = 'error';
    }
}

// Handle add/edit review
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save') {
    $id = isset($_POST['id']) && $_POST['id'] != '' ? intval($_POST['id']) : null;
    $reviewer_name = $mysqli->real_escape_string($_POST['reviewer_name']);
    $review_text = $mysqli->real_escape_string($_POST['review_text']);
    $rating = intval($_POST['rating']);
    $review_date = $mysqli->real_escape_string($_POST['review_date']);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($id) {
        // Update
        $save_query = "UPDATE reviews SET reviewer_name='$reviewer_name', review_text='$review_text', rating=$rating, review_date='$review_date', active=$active WHERE id=$id";
    } else {
        // Insert
        $save_query = "INSERT INTO reviews (reviewer_name, review_text, rating, review_date, active) VALUES ('$reviewer_name', '$review_text', $rating, '$review_date', $active)";
    }

    if ($mysqli->query($save_query)) {
        $message = $id ? 'Review updated successfully!' : 'Review added successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error saving review: ' . $mysqli->error;
        $message_type = 'error';
    }
}

// Get reviews
$reviews_query = "SELECT * FROM reviews ORDER BY review_date DESC";
$reviews_result = $mysqli->query($reviews_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
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
            max-width: 1200px;
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
            max-width: 1200px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th {
            background: #0052CC;
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .star-rating {
            color: #FFC107;
            letter-spacing: 2px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #0052CC;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal.show {
            display: block;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 4px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .btn-action {
            padding: 8px 12px;
            margin: 2px;
            font-size: 0.9rem;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Manage Google Reviews</h1>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="controls">
            <button class="btn" onclick="openModal()">+ Add New Review</button>
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Reviewer Name</th>
                    <th>Rating</th>
                    <th>Review Text</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['reviewer_name']); ?></td>
                        <td>
                            <span class="star-rating">
                                <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(substr($review['review_text'], 0, 50)) . '...'; ?></td>
                        <td><?php echo date('M d, Y', strtotime($review['review_date'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo $review['active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $review['active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-action" onclick="editReview(<?php echo $review['id']; ?>)">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                                <button type="submit" class="btn btn-action" style="background: #666;">Toggle</button>
                            </form>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                                <button type="submit" class="btn btn-action" style="background: #dc3545;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add/Edit Review</h2>
            <form method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" id="reviewId" value="">

                <div class="form-group">
                    <label for="reviewer_name">Reviewer Name *</label>
                    <input type="text" id="reviewer_name" name="reviewer_name" required>
                </div>

                <div class="form-group">
                    <label for="review_text">Review Text *</label>
                    <textarea id="review_text" name="review_text" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="rating">Rating *</label>
                    <select id="rating" name="rating" required>
                        <option value="">Select Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                        <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                        <option value="3">⭐⭐⭐ 3 Stars</option>
                        <option value="2">⭐⭐ 2 Stars</option>
                        <option value="1">⭐ 1 Star</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review_date">Review Date *</label>
                    <input type="datetime-local" id="review_date" name="review_date" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="active" name="active" checked>
                        Active
                    </label>
                </div>

                <button type="submit" class="btn">Save Review</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('reviewId').value = '';
            document.getElementById('reviewer_name').value = '';
            document.getElementById('review_text').value = '';
            document.getElementById('rating').value = '';
            document.getElementById('active').checked = true;
            const now = new Date();
            document.getElementById('review_date').value = now.toISOString().slice(0, 16);
            document.getElementById('reviewModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('reviewModal').classList.remove('show');
        }

        function editReview(id) {
            // In a real implementation, you'd fetch the review data
            alert('Edit functionality would be implemented with AJAX in production');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('reviewModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
