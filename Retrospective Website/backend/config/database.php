<?php
define('DB_HOST', 'localhost2');
define('DB_USER', '39348930_dupadupa');
define('DB_PASS', 'zaq1@WSX');
define('DB_NAME', '39348930_dupadupa');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?> 