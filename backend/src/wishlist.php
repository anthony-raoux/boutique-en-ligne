<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'controllers/WishlistController.php';
require_once 'controllers/AddToCartController.php'; // Ajoutez le contrôleur pour gérer l'ajout au panier
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();
$wishlistController = new WishlistController($db);
$addToCartController = new AddToCartController($db); // Instanciez le contrôleur pour l'ajout au panier

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_from_wishlist'])) {
        // Action : Retirer de la wishlist
        $product_id = $_POST['product_id'];
        $wishlistController->removeFromWishlist($_SESSION['user_id'], $product_id);
    } elseif (isset($_POST['add_to_cart'])) {
        // Action : Ajouter au panier
        $product_id = $_POST['product_id'];
        $addToCartController->addToCart($product_id, 1, $_SESSION['user_id']);
    }

    // Redirection vers la page actuelle pour éviter le re-postage des données
    header('Location: wishlist.php');
    exit;
}

$wishlist = $wishlistController->getWishlist($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre wishlist</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Votre wishlist</h1>
        <?php if (count($wishlist) > 0): ?>
            <ul>
            <?php foreach ($wishlist as $product): ?>
    <li>
        <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p>Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
        <form action="addToCart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id_produit'] ?>">
            <button type="submit" name="addToCart">Ajouter au panier</button>
        </form>
        <form action="wishlist.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id_produit'] ?>">
            <button type="submit" name="remove_from_wishlist">Retirer de la wishlist</button>
        </form>
    </li>
<?php endforeach; ?>

            </ul>
        <?php else: ?>
            <p>Votre wishlist est vide.</p>
        <?php endif; ?>
    </div>

    <script src="path/to/your/js/wishlist.js"></script>
</body>
</html>
