<?php
/**
 * API endpoint to retrieve statistics
 * Returns JSON array of key statistics with icons
 */

header('Content-Type: application/json');

require_once '../includes/config.php';

$query = "SELECT id, stat_name, stat_value, icon FROM statistics ORDER BY stat_name ASC";
$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stats = array();
while ($row = $result->fetch_assoc()) {
    $stats[] = $row;
}

echo json_encode($stats);
$result->free();
?>
