<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="nav-container">
        <!-- <a href="index.php" class="nav-logo">
            <img src="logo.png" alt="Retrospective Guild Logo">
        </a> -->
        <div class="nav-links">
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Main page</a>
            <a href="news.php" class="nav-link <?php echo $current_page == 'news.php' ? 'active' : ''; ?>">News</a>
            <a href="aboutme.html" class="nav-link <?php echo $current_page == 'aboutme.html' ? 'active' : ''; ?>">About Creator</a>
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="adminpanel.php" class="nav-link <?php echo $current_page == 'adminpanel.php' ? 'active' : ''; ?>">Panel admina</a>
            <?php endif; ?>
        </div>
    </div>
</nav> 