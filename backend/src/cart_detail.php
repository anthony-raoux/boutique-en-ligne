<?php
session_start();
require_once './controllers/CartDetailController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Instancier le contrôleur CartDetailController
    $controller = new CartDetailController();
    
    // Supprimer le produit du panier de l'utilisateur
    $controller->removeFromCart($_SESSION['user_id'], $product_id);

    // Rediriger vers la page du panier après la suppression
    header('Location: cart_detail.php');
    exit;
}

// Récupérer les détails du panier de l'utilisateur
$controller = new CartDetailController();
$items = $controller->getCartDetails($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart Detail</title>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <h1>Your Cart</h1>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <?php echo $item['quantity'] . ' x ' . $item['nom'] . ' - $' . $item['prix']; ?>
                <!-- Formulaire pour supprimer un produit -->
                <form method="post" action="cart_detail.php">
                    <input type="hidden" name="product_id" value="<?php echo $item['id_produit']; ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="checkout.php">Checkout</a>
</body>
</html>