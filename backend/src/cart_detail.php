<?php
session_start();
require_once './controllers/CartDetailController.php';

$controller = new CartDetailController();

// Vérifier si une requête POST a été soumise pour supprimer un produit du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Supprimer le produit du panier de l'utilisateur
    $controller->removeFromCart($_SESSION['user_id'], $product_id);

    // Rediriger vers la page du panier après la suppression
    header('Location: cart_detail.php');
    exit;
}

// Récupérer les détails du panier de l'utilisateur
$items = $controller->getCartDetails($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Detail</title>
    <!-- Inclusion du fichier CSS -->
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <h1>Your Cart</h1>
    <ul>
    <?php foreach ($items as $item): ?>
    <li>
        <h2><a href="details.php?product_id=<?= $item['id_produit'] ?>"><?php echo htmlspecialchars($item['nom']); ?></a></h2>
        <p>Prix: <?php echo htmlspecialchars($item['prix']); ?> €</p>
        <p>Quantité: <?php echo htmlspecialchars($item['quantity']); ?></p>
        <!-- Formulaire pour supprimer un produit -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="product_id" value="<?php echo $item['id_produit']; ?>">
            <button type="submit">Retirer du panier</button>
        </form>
    </li>
<?php endforeach; ?>

    </ul>
    <a href="checkout.php">Checkout</a>

    <!-- Inclusion du fichier JavaScript -->
    <script src="../frontend/src/assets/scripts/cart_detail.js"></script>
</body>
</html>
