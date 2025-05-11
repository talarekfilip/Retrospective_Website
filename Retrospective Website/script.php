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

// Funkcja do pobierania przybliżonej lokalizacji na podstawie IP
function getLocationFromIP($ip) {
    $url = "http://ip-api.com/json/" . $ip;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data && $data['status'] == 'success') {
        return $data['country'] . ', ' . $data['city'];
    }
    
    return 'Nieznana lokalizacja';
}

// Funkcja do weryfikacji nazwy użytkownika Roblox
function verifyRobloxUsername($username) {
    if (empty($username)) {
        return ['valid' => false, 'error' => 'Username is required'];
    }
    if (strlen($username) < 3 || strlen($username) > 20) {
        return ['valid' => false, 'error' => 'Username must be between 3 and 20 characters'];
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return ['valid' => false, 'error' => 'Username can only contain letters, numbers, and underscores'];
    }
    $reserved_usernames = ['admin', 'moderator', 'system', 'bot', 'retrospective'];
    if (in_array(strtolower($username), $reserved_usernames)) {
        return ['valid' => false, 'error' => 'This username is reserved'];
    }
    $applications = file_exists('applications.txt') ? file_get_contents('applications.txt') : '';
    if (strpos($applications, $username) !== false) {
        return ['valid' => false, 'error' => 'This username is already taken'];
    }
    return ['valid' => true, 'data' => ['name' => $username]];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nickname'])) {
        $username = trim($_POST['nickname']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');
        $line = $username . '|' . $ip . '|' . $date . "\n";
        // Sprawdź, czy użytkownik już istnieje
        $users = file_exists('users.txt') ? file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        $exists = false;
        foreach ($users as $user) {
            $parts = explode('|', $user);
            if (isset($parts[0]) && ($parts[0] === $username || (isset($parts[1]) && $parts[1] === $ip))) {
                $exists = true;
                break;
            }
        }
        if ($exists) {
            echo json_encode(['success' => false, 'message' => 'This user has already submitted an application']);
        } else {
            if (file_put_contents('users.txt', $line, FILE_APPEND) === false) {
                echo json_encode(['success' => false, 'message' => 'Could not write to users.txt. Check file permissions.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 