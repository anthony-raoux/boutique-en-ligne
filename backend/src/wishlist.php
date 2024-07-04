<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'controllers/WishlistController.php';
require_once 'controllers/AddToCartController.php';
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();
$wishlistController = new WishlistController($db);
$addToCartController = new AddToCartController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_from_wishlist'])) {
        // Action : Retirer de la wishlist
        $product_id = $_POST['product_id'];
        $wishlistController->removeFromWishlist($_SESSION['user_id'], $product_id);
    } elseif (isset($_POST['add_to_cart'])) {
        // Action : Ajouter au panier
        $product_id = $_POST['product_id'];
        $addToCartController->addToCart($product_id, 1, $_SESSION['user_id']);
        // Après avoir ajouté au panier, retirer de la wishlist
        $wishlistController->removeFromWishlist($_SESSION['user_id'], $product_id);

        // Redirection vers la wishlist après l'action pour éviter le re-postage
        header('Location: wishlist.php');
        exit;
    }
}

$wishlist = $wishlistController->getWishlist($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre wishlist</title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* Fond blanc */
            color: #333; /* Texte noir par défaut */
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333; /* Fond noir de la navbar */
            color: #FFF; /* Texte blanc dans la navbar */
            padding: 10px 20px; /* Espacement interne */
        }

        h1 {
            color: #333; /* Titre en blanc */
        }

        p, h2 {
            color: #333; /* Texte en blanc */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9; /* Fond gris clair pour les éléments de la liste */
        }

        button {
            background-color: #007bff; /* Couleur de fond des boutons */
            color: #fff; /* Texte blanc des boutons */
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        button:hover {
            background-color: yellow; /* Couleur de fond des boutons au survol */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php include 'navbar.php'; ?>
    </div>

    <div class="content">
        <h1>Votre wishlist</h1>
        <?php if (count($wishlist) > 0): ?>
            <ul>
            <?php foreach ($wishlist as $product): ?>
                <li>
                    <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                    <form action="wishlist.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id_produit'] ?>">
                        <button type="submit" name="remove_from_wishlist">Retirer de la wishlist</button>
                    </form>
                    <form action="wishlist.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id_produit'] ?>">
                        <button type="submit" name="add_to_cart">Ajouter au panier</button>
                    </form>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Votre wishlist est vide.</p>
        <?php endif; ?>
    </div>

    <script>
        // Vous pouvez ajouter du JavaScript ici si nécessaire
    </script>
</body>
</html>
