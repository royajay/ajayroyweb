<?php
/**
 * Fetch reviews from Google Places API
 * Falls back to local database if API fails
 */
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // Get Google API credentials from database
    $settings_query = "SELECT setting_value FROM site_settings WHERE setting_name IN ('google_api_key', 'google_place_id')";
    $settings_result = $mysqli->query($settings_query);
    
    $settings = array();
    if ($settings_result) {
        while ($row = $settings_result->fetch_assoc()) {
            $key = str_replace('google_', '', $row['setting_name']);
            $settings[$key] = $row['setting_value'];
        }
    }
    
    // Check if we have the necessary credentials
    if (empty($settings['api_key']) || empty($settings['place_id'])) {
        // Fall back to local database
        fallbackToLocalReviews();
        exit;
    }
    
    // Fetch from Google Places API
    $place_id = $settings['place_id'];
    $api_key = $settings['api_key'];
    
    // Google Places API endpoint
    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($place_id) . "&key=" . urlencode($api_key) . "&fields=rating,reviews";
    
    // Use cURL to fetch data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        // Fall back to local database if API fails
        fallbackToLocalReviews();
        exit;
    }
    
    $data = json_decode($response, true);
    
    if (!isset($data['result']['reviews'])) {
        fallbackToLocalReviews();
        exit;
    }
    
    // Format Google reviews to match our format
    $reviews = array();
    foreach ($data['result']['reviews'] as $google_review) {
        // Only include reviews with text
        if (!empty($google_review['text'])) {
            $reviews[] = array(
                'reviewer_name' => $google_review['author_name'] ?? 'Anonymous',
                'review_text' => $google_review['text'] ?? '',
                'rating' => $google_review['rating'] ?? 5,
                'review_date' => date('Y-m-d H:i:s', $google_review['time'] ?? time()),
                'source' => 'google'
            );
        }
    }
    
    // If we got Google reviews, optionally sync them to database and return
    if (!empty($reviews)) {
        // Optionally: Sync to local database for caching
        syncReviewsToDatabase($reviews);
        echo json_encode($reviews);
    } else {
        // Fall back to local if no reviews from Google
        fallbackToLocalReviews();
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

/**
 * Fallback to local database reviews
 */
function fallbackToLocalReviews() {
    global $mysqli;
    $query = "SELECT id, reviewer_name, review_text, rating, review_date FROM reviews WHERE active=1 ORDER BY review_date DESC";
    $result = $mysqli->query($query);
    
    $reviews = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['source'] = 'local';
            $reviews[] = $row;
        }
    }
    echo json_encode($reviews);
}

/**
 * Sync Google reviews to local database for caching
 */
function syncReviewsToDatabase($reviews) {
    global $mysqli;
    
    try {
        foreach ($reviews as $review) {
            $reviewer_name = $mysqli->real_escape_string($review['reviewer_name']);
            $review_text = $mysqli->real_escape_string($review['review_text']);
            $rating = intval($review['rating']);
            $review_date = $mysqli->real_escape_string($review['review_date']);
            
            // Check if review already exists (by name and date)
            $check_query = "SELECT id FROM reviews WHERE reviewer_name='$reviewer_name' AND review_date='$review_date' LIMIT 1";
            $check_result = $mysqli->query($check_query);
            
            // Only insert if not already in database
            if ($check_result && $check_result->num_rows == 0) {
                $insert_query = "INSERT INTO reviews (reviewer_name, review_text, rating, review_date, active) VALUES ('$reviewer_name', '$review_text', $rating, '$review_date', 1)";
                $mysqli->query($insert_query);
            }
        }
    } catch (Exception $e) {
        // Silently fail - just don't sync to database
        error_log("Review sync error: " . $e->getMessage());
    }
}
?>
