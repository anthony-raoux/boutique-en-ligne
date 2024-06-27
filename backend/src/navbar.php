<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session si elle n'est pas déjà démarrée
}

// Vérifier si l'utilisateur est connecté en tant qu'utilisateur normal ou administrateur
$loggedIn = isset($_SESSION['user_id']);
$adminLoggedIn = isset($_SESSION['admin_id']);

// Déterminer la page actuelle
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');

// Calculer le nombre d'articles dans le panier
$cartItemCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de produits</title>
    <style>
        .suggestions {
            list-style-type: none;
            padding: 0;
            margin: 0;
            border: 1px solid #ccc;
        }
        .suggestions li {
            padding: 10px;
            cursor: pointer;
        }
        .suggestions li:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php" class="<?php echo ($currentPage === 'index') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="shop.php" class="<?php echo ($currentPage === 'shop') ? 'active' : ''; ?>">Shop</a></li>
            <?php if ($loggedIn) : ?>
                <li><a href="profile.php" class="<?php echo ($currentPage === 'profile') ? 'active' : ''; ?>">Profile</a></li>
                <li><a href="cart.php" class="<?php echo ($currentPage === 'cart') ? 'active' : ''; ?>">Cart (<?php echo $cartItemCount; ?>)</a></li>
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

    <div class="search-container">
        <input type="text" id="search-input" placeholder="Rechercher des produits...">
        <ul class="suggestions" id="suggestions-list"></ul>
    </div>

    <script src="../frontend/assets/scripts/barrerecherche.js"></script>
</body>
</html>
