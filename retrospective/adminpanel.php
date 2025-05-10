<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}

// Połączenie z bazą danych
$conn = new mysqli('localhost', '39348930_users', '518632067Ab*', '39348930_users');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa dodawania newsów
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['news'])) {
    $news = $_POST['news'];
    $sql = "INSERT INTO news (content) VALUES ('$news')";
    $conn->query($sql);
}

// Pobieranie istniejących newsów
$sql = "SELECT * FROM news ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
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

        .admin-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
            max-width: 800px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .admin-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #7289DA;
        }

        .news-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
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

        .form-group textarea {
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 16px;
            width: 100%;
            outline: none;
            transition: all 0.3s ease;
            min-height: 100px;
            resize: vertical;
        }

        .form-group textarea:focus {
            border-color: #7289DA;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 0 0 3px rgba(114, 137, 218, 0.3);
        }

        .submit-button {
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

        .submit-button:hover {
            background-color: #5865F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .news-list {
            margin-top: 30px;
        }

        .news-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .news-item p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
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
            .admin-container {
                padding: 20px;
            }

            .admin-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="sky"></div> <!-- Tło gwiazd -->

    <div class="admin-container">
        <div class="logo-container">
            <img src="logo.png" alt="Retrospective">
        </div>
        <div class="admin-header">
            <h1>Panel Administracyjny</h1>
            <p>Witaj, <?php echo $_SESSION['admin_username']; ?>!</p>
        </div>

        <form class="news-form" method="POST" action="">
            <div class="form-group">
                <label for="news">Dodaj nowość</label>
                <textarea id="news" name="news" required></textarea>
            </div>
            <button type="submit" class="submit-button">
                <i class="fas fa-paper-plane"></i>
                Dodaj
            </button>
        </form>

        <div class="news-list">
            <h2>Ostatnie nowości</h2>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="news-item">';
                    echo '<p>' . $row['content'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Brak nowości.</p>';
            }
            ?>
        </div>

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
