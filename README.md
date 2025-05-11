# Retrospective

## Opis
Retrospective to strona internetowa z panelem administracyjnym, systemem logowania i funkcjonalnością dodawania newsów. Projekt zawiera również system śledzenia użytkowników z zapisem ich lokalizacji.

## Funkcjonalności
- Panel administracyjny z systemem logowania
- Dodawanie i wyświetlanie newsów
- System śledzenia użytkowników (nickname, IP, lokalizacja)
- Responsywny design z animowanym tłem
- Weryfikacja nazw użytkowników

## Wymagania
- PHP 7.4 lub nowszy
- MySQL/MariaDB
- Serwer WWW (np. Apache, Nginx)

## Instalacja
1. Sklonuj repozytorium
2. Skonfiguruj bazę danych MySQL:
   - Utwórz bazę danych `39348930_users`
   - Utwórz tabelę `users` z kolumnami:
     - `username` (VARCHAR)
     - `password` (VARCHAR)
   - Utwórz tabelę `news` z kolumnami:
     - `id` (AUTO_INCREMENT)
     - `content` (TEXT)
     - `created_at` (TIMESTAMP)
3. Skonfiguruj dostęp do bazy danych w plikach:
   - `admin.php`
   - `adminpanel.php`
4. Upewnij się, że pliki `applications.txt` i `users.txt` mają uprawnienia do zapisu

## Struktura plików
- `index.html` - Strona główna
- `aboutme.html` - Strona "O mnie"
- `admin.php` - Panel logowania administratora
- `adminpanel.php` - Panel administracyjny
- `script.php` - Skrypt obsługujący zapisywanie danych użytkowników
- `style.css` - Style CSS
- `script.js` - Skrypty JavaScript
- `applications.txt` - Plik z zapisanymi aplikacjami
- `users.txt` - Plik z danymi użytkowników

## Bezpieczeństwo
- Hasła są przechowywane w bazie danych
- Implementacja sesji PHP dla panelu administracyjnego
- Walidacja danych wejściowych
- Zabezpieczenie przed SQL injection

## Autor
tari

## Licencja
Wszelkie prawa zastrzeżone 