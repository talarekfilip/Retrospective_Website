<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Połączenie z bazą danych
$conn = new mysqli('localhost2', '39348930_dupadupa', 'zaq1@WSX', '39348930_dupadupa');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sprawdzenie struktury tabeli admins
$result = $conn->query("DESCRIBE admins");
echo "<h2>Struktura tabeli admins:</h2>";
echo "<pre>";
while($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

// Sprawdzenie danych w tabeli admins
$result = $conn->query("SELECT * FROM admins");
echo "<h2>Dane w tabeli admins:</h2>";
echo "<pre>";
while($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

// Generowanie nowego hasha dla hasła "admin123"
$password = "admin123";
$new_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Nowy hash dla hasła 'admin123':</h2>";
echo $new_hash . "<br><br>";

// Aktualizacja hasła w bazie danych
$sql = "UPDATE admins SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $new_hash);

if ($stmt->execute()) {
    echo "Hasło zostało zaktualizowane!<br><br>";
} else {
    echo "Błąd podczas aktualizacji hasła: " . $stmt->error . "<br><br>";
}

// Sprawdzenie czy hasło jest prawidłowo zahashowane
echo "<h2>Test weryfikacji hasła:</h2>";
echo "Test hasła 'admin123': " . (password_verify($password, $new_hash) ? "OK" : "BŁĄD") . "<br><br>";

// Wyświetlenie aktualnych danych
$result = $conn->query("SELECT * FROM admins");
echo "<h2>Aktualne dane w tabeli admins:</h2>";
echo "<pre>";
while($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

$password = 'NOWE_HASLO'; // Zmień to na hasło, które chcesz użyć
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash dla hasła '$password': " . $hash;
?> 