<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start or resume session if not already started
}

// Initialize $loggedIn variable based on session
$loggedIn = isset($_SESSION['user_id']);

// Determine current page
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
?>

<nav class="navbar">
    <ul>
        <li><a href="index.php" class="<?php echo ($currentPage === 'index') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="shop.php" class="<?php echo ($currentPage === 'shop') ? 'active' : ''; ?>">Shop</a></li>
        <?php if ($loggedIn) : ?>
            <li><a href="profile.php" class="<?php echo ($currentPage === 'profile') ? 'active' : ''; ?>">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else : ?>
            <li><a href="login.php" class="<?php echo ($currentPage === 'login') ? 'active' : ''; ?>">Login</a></li>
            <li><a href="register.php" class="<?php echo ($currentPage === 'register') ? 'active' : ''; ?>">Register</a></li>
        <?php endif; ?>
        <!-- Ajouter un message de débogage -->
        <li>Session status: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Not active'; ?></li>
        <li>User ID: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'; ?></li>
    </ul>
</nav>
