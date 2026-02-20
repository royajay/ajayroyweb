<?php
/**
 * API endpoint to retrieve all active services
 * Returns JSON array of services
 */

header('Content-Type: application/json');

require_once '../includes/config.php';

$query = "SELECT id, title, description, icon FROM services WHERE active=1 ORDER BY order_by ASC";
$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$services = array();
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

echo json_encode($services);
$result->free();
?>
