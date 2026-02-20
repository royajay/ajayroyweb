<?php
/**
 * Get all active reviews
 */
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $query = "SELECT id, reviewer_name, review_text, rating, review_date FROM reviews WHERE active=1 ORDER BY review_date DESC";
    $result = $mysqli->query($query);
    
    if (!$result) {
        throw new Exception("Database error: " . $mysqli->error);
    }
    
    $reviews = array();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    // If no reviews exist, return empty array
    echo json_encode($reviews);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
