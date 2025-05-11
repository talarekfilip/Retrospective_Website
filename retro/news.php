<?php
$conn = new mysqli('localhost2', '39348930_dupadupa', 'zaq1@WSX', '39348930_dupadupa');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News - Retrospective Guild</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #111216;
        }
        .news-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0 10px 0;
        }
        .news-logo img {
            width: 80px;
            height: 80px;
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.22);
        }
        .news-container {
            max-width: 1100px;
            margin: 0 auto 40px auto;
            padding: 28px 18px 36px 18px;
            background: rgba(40,44,52,0.97);
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.28);
        }
        .news-header {
            margin-bottom: 28px;
            text-align: center;
        }
        .news-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #7289DA;
            margin: 0;
            letter-spacing: 1px;
        }
        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            align-items: stretch;
        }
        .news-item {
            background: rgba(30,34,44,0.99);
            padding: 22px 20px 18px 20px;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(114,137,218,0.10), 0 2px 8px rgba(0,0,0,0.13);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-height: 120px;
            transition: box-shadow 0.22s, transform 0.22s;
            border: 1.5px solid rgba(114,137,218,0.08);
        }
        .news-item:hover {
            box-shadow: 0 8px 28px rgba(114,137,218,0.18), 0 4px 16px rgba(0,0,0,0.18);
            transform: translateY(-4px) scale(1.015);
            border-color: #7289DA33;
        }
        .news-item h2 {
            color: #7289DA;
            margin: 0 0 10px 0;
            font-size: 1.18rem;
            font-weight: 600;
            letter-spacing: 0.4px;
        }
        .news-item p {
            margin: 0 0 14px 0;
            font-size: 1.04rem;
            line-height: 1.6;
            color: #e3e3e3;
        }
        .news-item .date {
            margin-top: auto;
            font-size: 0.97rem;
            color: #aaa;
            font-style: italic;
        }
        .made-by {
            margin-top: 36px;
            text-align: right;
            color: #fff;
            font-size: 14px;
            opacity: 0.7;
        }
        @media (max-width: 900px) {
            .news-list {
                grid-template-columns: 1fr;
            }
            .news-logo {
                margin: 24px 0 8px 0;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="news-logo">
        <img src="logo.png" alt="Retrospective Guild Logo">
    </div>
    <div class="news-container">
        <div class="news-header">
            <h1>News</h1>
        </div>
        <div class="news-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="news-item">';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo '<div class="date">Added: ' . date('M d, Y H:i', strtotime($row['created_at'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No news to display.</p>';
            }
            ?>
        </div>
        <div class="made-by"><strong>made by tari v1.1.0</strong></div>
    </div>
</body>
</html> 