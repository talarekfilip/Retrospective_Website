<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin.php');
    exit;
}

// Połączenie z bazą danych
$conn = new mysqli('localhost2', '39348930_dupadupa', 'zaq1@WSX', '39348930_dupadupa');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa usuwania newsów
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_news'])) {
    $news_id = (int)$_POST['delete_news'];
    $sql = "DELETE FROM news WHERE id = $news_id";
    $conn->query($sql);
    header('Location: adminpanel.php');
    exit;
}

// Obsługa dodawania newsów
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['news'])) {
    $news = $conn->real_escape_string($_POST['news']);
    $title = $conn->real_escape_string($_POST['title']);
    $sql = "INSERT INTO news (title, content, created_at) VALUES ('$title', '$news', NOW())";
    $conn->query($sql);
    header('Location: adminpanel.php');
    exit;
}

// Pobieranie newsów
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);

// Pobieranie użytkowników z pliku users.txt
$users = file_exists('users.txt') ? file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Retrospective Guild</title>
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
            margin: 20px;
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

        .admin-header p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.5);
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

        .form-group input[type="text"],
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
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group input[type="text"]:focus,
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
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            position: relative;
        }

        .news-item h3 {
            margin: 0 0 10px 0;
            color: #7289DA;
        }

        .news-item p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
        }

        .news-item .date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 10px;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .delete-button:hover {
            color: #ff0000;
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

        .made-by-admin {
            margin-top: 30px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            background: linear-gradient(90deg, #ff00cc, #3333ff, #00ff99, #ffcc00, #ff00cc);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            animation: gradientMove 3s ease-in-out infinite;
            letter-spacing: 1px;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>
    <div class="sky"></div>

    <div class="admin-container">
        <div class="logo-container">
            <img src="logo.png" alt="Retrospective">
        </div>
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
        </div>

        <form class="news-form" method="POST" action="">
            <div class="form-group">
                <label for="title">News Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="news">News Content</label>
                <textarea id="news" name="news" required></textarea>
            </div>
            <button type="submit" class="submit-button">
                <i class="fas fa-paper-plane"></i>
                Add News
            </button>
        </form>

        <!-- User list -->
        <div class="users-list" style="margin-top:40px;">
            <h2 style="color:#7289DA; text-align:center;">User list</h2>
            <?php
            if (!empty($users)) {
                echo '<table style="width:100%;border-collapse:collapse;background:rgba(255,255,255,0.03);border-radius:8px;overflow:hidden;">';
                echo '<thead><tr style="background:rgba(114,137,218,0.15);color:#7289DA;"><th style="padding:10px;">Username</th><th style="padding:10px;">IP address</th><th style="padding:10px;">Date</th></tr></thead><tbody>';
                foreach ($users as $user) {
                    $parts = explode('|', $user);
                    echo '<tr style="border-bottom:1px solid rgba(255,255,255,0.07);">';
                    echo '<td style="padding:10px;">' . htmlspecialchars($parts[0]) . '</td>';
                    echo '<td style="padding:10px;">' . htmlspecialchars($parts[1]) . '</td>';
                    echo '<td style="padding:10px;">' . (isset($parts[2]) ? htmlspecialchars($parts[2]) : '-') . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p style="text-align:center;">No users to display.</p>';
            }
            ?>
        </div>

        <div class="news-list">
            <h2>Recent News</h2>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="news-item">';
                    echo '<form method="POST" action="" style="display: inline;">';
                    echo '<input type="hidden" name="delete_news" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this news?\')">';
                    echo '<i class="fas fa-trash"></i>';
                    echo '</button>';
                    echo '</form>';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo '<div class="date">Posted on: ' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No news available.</p>';
            }
            ?>
        </div>

        <div class="back-link">
            <a href="index.html">Back to Homepage</a>
        </div>

        <div class="made-by-admin">made by tari v0.1</div>
    </div>

    <script>
        // Create stars in the background
        function createStars() {
            const sky = document.querySelector('.sky');
            const starCount = 100;

            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.width = Math.random() * 3 + 'px';
                star.style.height = star.style.width;
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDuration = Math.random() * 3 + 2 + 's';
                star.style.animationDelay = Math.random() * 2 + 's';
                sky.appendChild(star);
            }
        }

        createStars();
    </script>
</body>
</html>
