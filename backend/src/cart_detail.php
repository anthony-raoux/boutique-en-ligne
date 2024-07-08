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

require_once 'head.php';
require_once 'navbar.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-white">Votre Panier</h1>
    
    <?php if (empty($items)): ?>
        <div class="flex flex-col items-center justify-center">
            <img src="../../images/panier-vide.png" alt="Panier vide" class="mb-4">
            <p class="text-white">Votre panier est vide.</p>
        </div>
    <?php else: ?>
        <ul class="bg-black border border-white rounded-lg p-6">
            <?php foreach ($items as $item): ?>
                <li class="flex justify-between items-center mb-4">
                    <div>
                        <span class="font-medium text-white"><?php echo $item['quantity']; ?> x</span>
                        <span class="font-bold text-white"><?php echo $item['nom']; ?></span>
                        <span class="text-gray-300">- $<?php echo $item['prix']; ?></span>
                    </div>
                    <!-- Formulaire pour supprimer un produit -->
                    <form method="post" action="cart_detail.php" class="ml-4">
                        <input type="hidden" name="product_id" value="<?php echo $item['id_produit']; ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                            Supprimer
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="text-right mt-6">
            <a href="checkout.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Passer à la caisse
            </a>
        </div>
    <?php endif; ?>
</div>
