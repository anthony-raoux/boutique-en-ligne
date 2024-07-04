<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

$product_id = $_GET['product_id'] ?? '';

if (!$product_id) {
    echo "Produit non trouvé.";
    exit;
}

$result = $productController->getProductById($product_id);
$product = $result['product'] ?? null;

if (!$product) {
    echo "Produit non trouvé.";
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
    <section  class="bg-black text-white overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="aspect-w-3 aspect-h-4 rounded-lg overflow-hidden">
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="object-cover object-center h-full w-full">
                        <?php else: ?>
                            <img src="../../images/placeholder.jpg" alt="Image indisponible" class="object-cover object-center h-full w-full">
                        <?php endif; ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($product['nom']); ?></h2>
                        <p class="mt-4 text-gray-700"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        <p class="mt-4 text-lg font-medium text-gray-900">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                        <p class="mt-2 text-sm text-gray-600">Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                        <p class="mt-2 text-sm text-gray-600">Catégorie: <?php echo htmlspecialchars($product['nom_categorie']); ?></p>
                        <div class="mt-6">
                            <button class="w-full flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-base font-medium text-white hover:bg-indigo-700" onclick="addToCart(<?php echo $product['id_produit']; ?>)">Ajouter au panier</button>
                            <?php if (array_key_exists($product_id, $_SESSION['cart'])): ?>
                                <button class="mt-2 w-full flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-base font-medium text-white hover:bg-red-700" onclick="removeFromCart(<?php echo $product['id_produit']; ?>)">Supprimer</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produit ajouté au panier!');
                } else {
                    alert('Erreur: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }

        function removeFromCart(productId) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
</body>
</html>
