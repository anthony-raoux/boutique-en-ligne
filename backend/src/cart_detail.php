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

$total = 0;
foreach ($items as $item) {
    $total += $item['prix'] * $item['quantity'];
}
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
    <h2><a href="details.php?product_id=<?= $item['id_produit'] ?>"><?php echo htmlspecialchars($item['nom'] ?? ''); ?></a></h2>
<p>Prix: <?php echo htmlspecialchars($item['prix'] ?? ''); ?> €</p>
<p>Quantité: <?php echo htmlspecialchars($item['quantity'] ?? ''); ?></p>

        <!-- Formulaire pour supprimer un produit -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id_produit']); ?>">
    <button type="submit">Retirer du panier</button>
</form>

    </li>
<?php endforeach; ?>


    </ul>
    <h2>Total: <?php echo htmlspecialchars($total); ?> €</h2>
    <a href="checkout.php">Checkout</a>

    <!-- Inclusion du fichier JavaScript -->
    <script>
// Sélection de l'élément span contenant le nombre de produits dans la navbar
const cartItemCountElement = document.getElementById('cartItemCount');

// Fonction pour mettre à jour le nombre de produits dans la navbar
function updateCartItemCount(count) {
    cartItemCountElement.textContent = count;
}

// Fonction pour supprimer un produit du panier via AJAX
function removeFromCart(productId) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
    })
    .then(response => {
        if (response.ok) {
            // Mettre à jour le nombre de produits dans la navbar après suppression
            fetchCartItemCount();
            // Supprimer l'élément du DOM
            document.querySelector(`[data-product-id="${productId}"]`).remove();
        } else {
            console.error('Erreur lors de la suppression du produit du panier.');
        }
    })
    .catch(error => console.error('Erreur lors de la suppression du produit du panier :', error));
}

// Fonction pour récupérer le nombre de produits dans le panier via AJAX
function fetchCartItemCount() {
    fetch('fetch_cart_item_count.php')
    .then(response => {
        if (response.ok) {
            return response.json(); // Convertir la réponse en JSON
        } else {
            throw new Error('Réponse réseau incorrecte');
        }
    })
    .then(data => {
        updateCartItemCount(data.cartItemCount);
    })
    .catch(error => console.error('Erreur lors de la récupération du nombre de produits dans le panier :', error));
}

// Appel initial pour afficher le nombre de produits au chargement de la page
fetchCartItemCount();

// Ajouter des écouteurs d'événements pour les boutons de suppression
document.querySelectorAll('.remove-from-cart-button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        removeFromCart(productId);
    });
});
</script>


</body>
</html>
