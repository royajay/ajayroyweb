<?php
/**
 * API endpoint to retrieve portfolio projects
 * Returns JSON array of portfolio items
 */

header('Content-Type: application/json');

require_once '../includes/config.php';

$query = "SELECT id, title, description, category FROM portfolio WHERE active=1 ORDER BY order_by ASC LIMIT 6";
$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$portfolio = array();
while ($row = $result->fetch_assoc()) {
    $portfolio[] = $row;
}

echo json_encode($portfolio);
$result->free();
?>
