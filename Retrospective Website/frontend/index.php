<?php
require_once '../backend/includes/navbar.php';
// Połączenie z bazą danych
$conn = new mysqli('localhost2', '39348930_dupadupa', 'zaq1@WSX', '39348930_dupadupa');

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobieranie newsów
$sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrospective Guild</title>

    <meta property="og:title" content="Retrospective Guild">
    <meta property="og:description" content="Retrospective. Const Top 20 Guild in Deepwoken.">
    <meta property="og:image" content="http://retrospective.com.pl/logo.jpg">
    <meta property="og:url" content="http://retrospective.com.pl">
    <link rel="icon" href="../assets/images/logo.png" type="image/png">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .latest-news {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(40,44,52,0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .news-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .news-header h2 {
            color: #7289DA;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        .news-list {
            display: grid;
            gap: 20px;
        }
        .news-item {
            background: rgba(255,255,255,0.07);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .news-item h3 {
            color: #7289DA;
            margin: 0 0 15px 0;
            font-size: 1.3rem;
        }
        .news-item p {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #fff;
        }
        .news-item .date {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #aaa;
        }
        .view-all-news {
            text-align: center;
            margin-top: 20px;
        }
        .view-all-news a {
            display: inline-block;
            padding: 10px 20px;
            background: #7289DA;
            color: #fff;
            text-decoration: none;
            border-radius: 7px;
            transition: background 0.2s;
        }
        .view-all-news a:hover {
            background: #5865F2;
        }
    </style>
</head>
<body>
    <div id="loading-screen">
        <div class="loading-content">
            <img src="logo.png" alt="Retrospective Guild" class="loading-logo">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <div class="sky"></div>

    <nav class="navigation">
        <div class="nav-logo">
            <img src="logo.png" alt="Retrospective" width="40" height="40">
            <span>Retrospective</span>
        </div>
        <div class="nav-links">
            <a href="#guild-advert" class="nav-link">Guild Info</a>
            <a href="#join-form" class="nav-link">Join Us</a>
            <a href="aboutme.html" class="nav-link">About Creator</a>
            <a href="admin.php" class="nav-link">Admin Panel</a>
        </div>
    </nav>

    <div class="spacer"></div>

    <div class="title">Retrospective. Const Top 20 Guild in Deepwoken.</div>

    <!-- Sekcja z newsami -->
    <div class="latest-news">
        <div class="news-header">
            <h2>Latest news</h2>
        </div>
        
        <div class="news-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="news-item">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo '<div class="date">Added: ' . date('M d, Y H:i', strtotime($row['created_at'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No news to display.</p>';
            }
            ?>
        </div>
        
        <div class="view-all-news">
            <a href="news.php">See all news</a>
        </div>
    </div>

    <div class="services">
        <div class="service-guild-info" onclick="scrollToGuildAdvert()">
            <b>Guild Advert</b>
        </div>
    </div>

    <div class="spacer"></div>

    <div id="guild-advert" class="content-wrapper">
        <div class="guild-content">
            <h1>ꕥ <strong>Retrospective</strong> ꕥ</h1>
            <h2><i>A consistent TOP 20 EU guild</i></h2>
            <p><sub><i>Top 13</strong> last season</i></sub></p>

            <h2>Overall Info</h2>
            <p>We are relatively a new guild, we are rather a combination of members from various guilds that agreed to unite. We are very organised when it comes to managing our precious members as well as our discord server. We oftentimes sit and talk on vc. We are handling ganks nearly everyday, so u won't be bored outta ur mind.</p>

            <h2>What we offer</h2>
            <ul>
                <li><strong>EX top 250s</strong> capable of handling multiple people at once, no matter the situation in a fight</li>
                <li><strong>lots of allies</strong> willing to help anytime</li>
                <li>Amazing <strong>PvE members</strong> capable of soloing <strong>enmity</strong>, as well as quickly handling <strong>dilluvian</strong>, <strong>hellmode</strong>, and <strong>layer 2</strong></li>
                <li>Wonderful <strong>build makers</strong> that aren't just repeating what's been said countless times on YouTube, but they think past it, trying to make even better builds than what the meta has to offer</li>
                <li><strong>Maxed out guild base</strong> with 10 rooms and a guild arena</li>
            </ul>

            <h2>What we need</h2>
            <ul>
                <li><strong>Loyalty</strong> to the guild and avoidance of unnecessary guild hopping</li>
                <li><strong>Activity</strong> when possible (excessive inactivity may result in a kick)</li>
                <li><strong>Above average skill</strong> so you can be of help in a big gank</li>
                <li><strong>A good build</strong> (so you can put your skill to good use)</li>
                <li><strong>Experience</strong> (we want you to know most of the things that are going around in the game)</li>
            </ul>
        </div>

        <div id="join-form" class="nickname-form">
            <h2>Join Our Guild</h2>
            <form id="nicknameForm" onsubmit="return submitNickname(event)">
                <div class="form-group">
                    <input type="text" id="nickname" name="nickname" placeholder="Enter your ROBLOX nickname" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-btn">
                        <span>Submit Application</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            <div id="form-message" class="form-message"></div>
        </div>

        <div id="discord-invite" class="contact locked">
            <a href="javascript:void(0);" onclick="return false;">
                <strong>Join our Discord</strong>
                <div class="contact-icons locked-icon">
                    <i class="fas fa-lock"></i>
                </div>
            </a>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Retrospective Guild</h3>
                <p>A <i>consistent</i> TOP 20 EU guild in Deepwoken</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#guild-advert">Guild Info</a></li>
                    <li><a href="#join-form">Join Us</a></li>
                    <li><a href="aboutme.html">About</a></li>
                </ul>
            </div>
        </div>
        <div class="made-by"><strong>made by tari v1.1.0</strong></div>
    </footer>

    <audio id="background-music" autoplay loop>
        <source src="./background.mp3" type="audio/mpeg">
    </audio>
    
    <script src="js/script.js"></script>
</body>
</html> 