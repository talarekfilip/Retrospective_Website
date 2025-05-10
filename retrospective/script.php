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

    // Sprawdzenie długości nazwy użytkownika
    if (strlen($username) < 3 || strlen($username) > 20) {
        return ['valid' => false, 'error' => 'Username must be between 3 and 20 characters'];
    }

    // Sprawdzenie dozwolonych znaków
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return ['valid' => false, 'error' => 'Username can only contain letters, numbers, and underscores'];
    }

    // Sprawdzenie czy nazwa użytkownika nie jest zarezerwowana
    $reserved_usernames = ['admin', 'moderator', 'system', 'bot', 'retrospective'];
    if (in_array(strtolower($username), $reserved_usernames)) {
        return ['valid' => false, 'error' => 'This username is reserved'];
    }

    // Sprawdzenie czy nazwa użytkownika nie jest już w użyciu
    $applications = file_get_contents('applications.txt');
    if (strpos($applications, $username) !== false) {
        return ['valid' => false, 'error' => 'This username is already taken'];
    }

    return ['valid' => true, 'data' => ['name' => $username]];
}

// Obsługa żądania AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nickname = $data['nickname'] ?? '';

    $result = verifyRobloxUsername($nickname);

    if ($result['valid']) {
        try {
            // Save to applications.txt with timestamp
            $timestamp = date('Y-m-d H:i:s');
            $entry = "{$timestamp} | {$nickname}" . PHP_EOL;
            
            if (file_put_contents('applications.txt', $entry, FILE_APPEND) === false) {
                throw new Exception("Error writing to applications.txt");
            }
            
            // Pobieranie danych klienta
            $ip = $_SERVER['REMOTE_ADDR'];
            $location = getLocationFromIP($ip);
            
            // Formatowanie danych do zapisu
            $user_data = $nickname . ':' . $ip . ':' . $location . "\n";
            
            // Zapisywanie danych do pliku
            file_put_contents('users.txt', $user_data, FILE_APPEND);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $result['error']]);
    }
    exit;
}

// Obsługa żądania GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = $_GET['username'] ?? '';
    $result = verifyRobloxUsername($username);
    echo json_encode($result);
    exit;
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?> 