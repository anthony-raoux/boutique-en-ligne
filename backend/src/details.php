<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer l'ID du produit
$product_id = $_GET['product_id'] ?? '';

if (!$product_id) {
    echo "Produit non trouvé.";
    exit;
}

// Récupérer les détails du produit
$result = $productController->getProductById($product_id);
$product = $result['product'] ?? null;

if (!$product) {
    echo "Produit non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <style>
        .product-details { margin: 20px auto; width: 80%; max-width: 800px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .product-details img { width: 100%; height: auto; border-radius: 8px; }
        .product-details h1 { font-size: 2rem; margin-bottom: 10px; }
        .product-details p { font-size: 1rem; line-height: 1.5; }
        .product-details .price { font-size: 1.5rem; font-weight: bold; color: #007bff; }
        .product-details .add-to-cart { margin-top: 20px; }
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <div class="product-details">
            <?php if (!empty($product['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
            <?php else: ?>
                <img src="placeholder.jpg" alt="Image indisponible" />
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($product['nom']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
            <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
            <p>Catégorie: <?php echo htmlspecialchars($product['nom_categorie']); ?></p>
            <button class="add-to-cart" onclick="addToCart(<?php echo $product['id_produit']; ?>)">Ajouter au panier</button>
        </div>
    </main>
    <footer></footer>
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
    </script>
</body>
</html>
