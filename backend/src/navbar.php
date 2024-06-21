<!-- backend/src/navbar.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start or resume session if not already started
}

// Initialize $loggedIn variable based on session
$loggedIn = isset($_SESSION['user_id']);
?>


<nav>
    <ul>
        <li><a href="index.php" <?php echo ($currentPage === 'home') ? 'class="active"' : ''; ?>>Home</a></li>
        <li><a href="about.php" <?php echo ($currentPage === 'about') ? 'class="active"' : ''; ?>>About</a></li>
        <li><a href="services.php" <?php echo ($currentPage === 'services') ? 'class="active"' : ''; ?>>Services</a></li>
        
        <?php if ($loggedIn) : ?>
            <!-- Display these links if user is logged in -->
            <li><a href="profile.php" <?php echo ($currentPage === 'profile') ? 'class="active"' : ''; ?>>Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else : ?>
            <!-- Display these links if user is not logged in -->
            <li><a href="login.php" <?php echo ($currentPage === 'login') ? 'class="active"' : ''; ?>>Login</a></li>
            <li><a href="register.php" <?php echo ($currentPage === 'register') ? 'class="active"' : ''; ?>>Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
