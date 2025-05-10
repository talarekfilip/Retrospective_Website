<?php
session_start();

// Sprawdzenie, czy formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Połączenie z bazą danych
    $conn = new mysqli('localhost', '39348930_users', '518632067Ab*', '39348930_users');
    
    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Zapytanie do bazy danych
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        
        // Przekierowanie do panelu administracyjnego
        header('Location: adminpanel.php');
        exit;
    } else {
        $error_message = "Nieprawidłowa nazwa użytkownika lub hasło.";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Logowanie</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Helvetica Neue", Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .sky {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .star {
            position: absolute;
            border-radius: 50%;
            background-color: white;
            opacity: 0.7;
            animation: move 5s linear infinite;
        }

        @keyframes move {
            from { transform: translateY(0); }
            to { transform: translateY(100vh); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
            max-width: 400px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #7289DA;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 16px;
            font-weight: 500;
        }

        .form-group input {
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 16px;
            width: 100%;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #7289DA;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 0 0 3px rgba(114, 137, 218, 0.3);
        }

        .login-button {
            padding: 12px 20px;
            background-color: #7289DA;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .login-button:hover {
            background-color: #5865F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-link {
            margin-top: 20px;
            text-align: center;
        }

        .back-link a {
            color: #7289DA;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #5865F2;
            text-decoration: underline;
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 60px;
            height: 60px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="sky"></div> <!-- Tło gwiazd -->

    <div class="login-container">
        <div class="logo-container">
            <img src="logo.png" alt="Retrospective">
        </div>
        <div class="login-header">
            <h1>Panel Administracyjny</h1>
            <p>Zaloguj się, aby uzyskać dostęp do panelu administracyjnego</p>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="username">Nazwa użytkownika</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt"></i>
                Zaloguj się
            </button>
        </form>

        <div class="back-link">
            <a href="index.html">
                <i class="fas fa-arrow-left"></i> Powrót do strony głównej
            </a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sky = document.querySelector(".sky");

            function createStar() {
                const star = document.createElement("div");
                star.classList.add("star");

                const size = Math.random() * 3 + 1;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.left = `${Math.random() * 100}vw`;
                star.style.top = `-${size}px`;
                star.style.animationDuration = `${Math.random() * 3 + 2}s`;
                star.style.animationDelay = `${Math.random()}s`;

                sky.appendChild(star);

                setTimeout(() => {
                    star.remove();
                }, 5000);
            }

            setInterval(createStar, 100);
        });
    </script>
</body>
</html> 