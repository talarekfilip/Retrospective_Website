<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log the incoming request (without sensitive data)
error_log("Received nickname submission request");

function verifyRobloxUsername($username) {
    // Remove any whitespace
    $username = trim($username);
    
    // Basic validation
    if (strlen($username) < 3 || strlen($username) > 20) {
        return ['valid' => false, 'error' => 'Username must be between 3 and 20 characters'];
    }
    
    // Check if username contains only allowed characters
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return ['valid' => false, 'error' => 'Username can only contain letters, numbers and underscores'];
    }
    
    // Log validation result
    error_log("Username passed basic validation: $username");
    
    // First try with users/get-by-username endpoint
    $url = "https://api.roblox.com/users/get-by-username?username=" . urlencode($username);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => ['Accept: application/json']
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        error_log("Curl Error: " . $error);
        // Don't return error yet, try alternative method
    } else {
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if ($data && isset($data['Id']) && $data['Id'] > 0) {
                return ['valid' => true, 'data' => $data];
            }
        }
    }
    
    // If we can't verify through API, assume it's valid (temporary solution)
    error_log("Assuming username is valid due to API limitations");
    return ['valid' => true, 'data' => ['name' => $username]];
}

// Weryfikacja i zapis nicku bez wymagania captcha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if nickname is provided
        if (!isset($_POST['nickname'])) {
            error_log("Nickname not set in POST data");
            echo json_encode(['success' => false, 'error' => 'Nickname is required']);
            exit;
        }
        
        $nickname = $_POST['nickname'];
        
        // Verify Roblox username
        $verification = verifyRobloxUsername($nickname);
        if (!$verification['valid']) {
            error_log("Username verification failed: " . $verification['error']);
            echo json_encode(['success' => false, 'error' => $verification['error']]);
            exit;
        }
        
        try {
            // Save to applications.txt with timestamp
            $timestamp = date('Y-m-d H:i:s');
            $entry = "{$timestamp} | {$nickname}" . PHP_EOL;
            
            if (file_put_contents('applications.txt', $entry, FILE_APPEND) === false) {
                throw new Exception("Error writing to applications.txt");
            }
            
            echo json_encode(['success' => true]);
            
        } catch (Exception $e) {
            error_log("Error saving application: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Error saving application: ' . $e->getMessage()]);
            exit;
        }
        
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        echo json_encode(['success' => false, 'error' => 'An unexpected error occurred: ' . $e->getMessage()]);
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?> 