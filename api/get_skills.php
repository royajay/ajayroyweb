<?php
/**
 * API endpoint to retrieve skills data
 * Returns JSON array of skills with proficiency levels
 */

header('Content-Type: application/json');

require_once '../includes/config.php';

$query = "SELECT id, skill_name, proficiency FROM skills WHERE active=1 ORDER BY order_by ASC";
$result = $mysqli->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$skills = array();
while ($row = $result->fetch_assoc()) {
    $skills[] = $row;
}

echo json_encode($skills);
$result->free();
?>
