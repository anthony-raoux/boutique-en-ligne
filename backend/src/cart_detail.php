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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        h2 a {
            color: #333;
            text-decoration: none;
        }

        h2 a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: yellow;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }

        a.checkout {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        a.checkout:hover {
            text-decoration: underline;
        }

        /* Styles pour la navbar */
        .navbar {
            background-color: #333;
            color: #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .navbar a {
            color: #ccc;
            text-decoration: none;
            margin: 0 10px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Inclusion de la navbar -->
    <?php require_once 'navbar.php'; ?>

    <div class="container">
        <h1>Your Cart</h1>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <h2><a href="details.php?product_id=<?= $item['id_produit'] ?>"><?php echo htmlspecialchars($item['nom'] ?? ''); ?></a></h2>
                    <p>Price: <?php echo htmlspecialchars($item['prix'] ?? ''); ?> €</p>
                    <p>Quantity: <?php echo htmlspecialchars($item['quantity'] ?? ''); ?></p>

                    <!-- Formulaire pour supprimer un produit -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id_produit']); ?>">
                        <button type="submit">Remove from Cart</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="total">
            <h2>Total: <?php echo htmlspecialchars($total); ?> €</h2>
        </div>
        <a href="checkout.php" class="checkout">Checkout</a>
    </div>
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
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêcher le comportement par défaut du formulaire
                const productId = this.parentElement.querySelector('input[name="product_id"]').value;
                removeFromCart(productId);
            });
        });
    </script>

</body>
</html>
