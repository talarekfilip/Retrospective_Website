<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: adminpanel.php');
    exit;
}

// Database connection
$conn = new mysqli('mysql.serwer2520415.home.pl', '39348930_dupadupa', 'zaq1@WSX', '39348930_dupadupa');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: adminpanel.php');
            exit;
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'Invalid username';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Retrospective Guild</title>
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
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
            max-width: 400px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #7289DA;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 0 0 3px rgba(114, 137, 218, 0.3);
        }

        .submit-button {
            width: 100%;
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
        }

        .submit-button:hover {
            background-color: #5865F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .error-message {
            color: #ff4444;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
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
    <div class="sky"></div>

    <div class="login-container">
        <div class="logo-container">
            <img src="logo.png" alt="Retrospective">
        </div>
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Please sign in to access the admin panel</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="submit-button">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>

        <div class="back-link">
            <a href="index.html">Back to Homepage</a>
        </div>
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