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

// Fonction pour vérifier si une page est active
function isActivePage($pageName, $currentPage) {
    return $currentPage === $pageName ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de produits</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluez votre fichier CSS -->
    <style>
        .suggestions {
            list-style-type: none;
            padding: 0;
            margin: 0;
            border: 1px solid #ccc;
            position: absolute;
            background-color: #fff;
            width: 100%;
            z-index: 1;
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
            <li><a href="index.php" class="<?= isActivePage('index', $currentPage); ?>">Home</a></li>
            <li><a href="shop.php" class="<?= isActivePage('shop', $currentPage); ?>">Shop</a></li>
            <?php if ($loggedIn) : ?>
                <li><a href="profile.php" class="<?= isActivePage('profile', $currentPage); ?>">Profile</a></li>
                <li><a href="wishlist.php" class="<?= isActivePage('wishlist', $currentPage); ?>">Wishlist</a></li>
                <li><a href="cart_detail.php" class="<?= isActivePage('cart', $currentPage); ?>">Cart (<span id="cartItemCount"><?= $cartItemCount; ?></span>)</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php elseif ($adminLoggedIn) : ?>
                <li><a href="dashboard.php" class="<?= isActivePage('dashboard', $currentPage); ?>">Dashboard</a></li>
                <li><a href="admin_reviews.php" class="<?= isActivePage('admin_reviews', $currentPage); ?>">Modérer les avis</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="login.php" class="<?= isActivePage('login', $currentPage); ?>">Login</a></li>
                <li><a href="register.php" class="<?= isActivePage('register', $currentPage); ?>">Register</a></li>
            <?php endif; ?>
            <!-- Ajouter un message de débogage -->
            <li>Session status: <?= session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Not active'; ?></li>
            <li>User ID: <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'; ?></li>
            <li>Admin ID: <?= isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'Not set'; ?></li>

            <!-- Barre de recherche -->
            <li>
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Rechercher des produits...">
                    <ul class="suggestions" id="suggestions-list"></ul>
                </div>
            </li>
        </ul>
    </nav>


</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');

    searchInput.addEventListener('input', function() {
        const inputValue = this.value.trim();

        if (inputValue.length === 0) {
            suggestionsList.innerHTML = '';
            suggestionsList.style.display = 'none';
            return;
        }

        fetchSuggestions(inputValue)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    });

    function fetchSuggestions(query) {
        return fetch('search.php?q=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response;
            });
    }

    function displaySuggestions(suggestions) {
        if (suggestions.length === 0) {
            suggestionsList.innerHTML = '<li>No suggestions found</li>';
        } else {
            const items = suggestions.map(item => `<li data-product="${item}">${item}</li>`).join('');
            suggestionsList.innerHTML = items;
        }
        suggestionsList.style.display = 'block';

        // Ajouter un écouteur d'événements sur chaque suggestion
        suggestionsList.querySelectorAll('li').forEach(suggestion => {
            suggestion.addEventListener('click', function() {
                const productName = this.getAttribute('data-product');
                window.location.href = `detail.php?nom=${encodeURIComponent(productName)}`;
            });
        });
    }
});


</script>
</html>
