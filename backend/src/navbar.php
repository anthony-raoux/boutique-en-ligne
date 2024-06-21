<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session si elle n'est pas déjà démarrée
}

// Vérifier si l'utilisateur est connecté en tant qu'utilisateur normal ou administrateur
$loggedIn = isset($_SESSION['user_id']);
$adminLoggedIn = isset($_SESSION['admin_id']);

// Déterminer la page actuelle
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
?>

<nav class="navbar">
    <ul>
        <li><a href="index.php" class="<?php echo ($currentPage === 'index') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="shop.php" class="<?php echo ($currentPage === 'shop') ? 'active' : ''; ?>">Shop</a></li>
        <?php if ($loggedIn) : ?>
            <li><a href="profile.php" class="<?php echo ($currentPage === 'profile') ? 'active' : ''; ?>">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php elseif ($adminLoggedIn) : ?>
            <li><a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else : ?>
            <li><a href="login.php" class="<?php echo ($currentPage === 'login') ? 'active' : ''; ?>">Login</a></li>
            <li><a href="register.php" class="<?php echo ($currentPage === 'register') ? 'active' : ''; ?>">Register</a></li>
        <?php endif; ?>
        <!-- Ajouter un message de débogage -->
        <li>Session status: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Not active'; ?></li>
        <li>User ID: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'; ?></li>
        <li>Admin ID: <?php echo isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'Not set'; ?></li>
    </ul>
</nav>
