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
$product = $productController->getProductById($product_id);

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
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <h1><?php echo htmlspecialchars($product['nom']); ?></h1>
        <div class="product-details">
            <?php if (!empty($product['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
            <?php else: ?>
                <img src="placeholder.jpg" alt="Image indisponible" />
            <?php endif; ?>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
            <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
        </div>
    </main>
    <footer></footer>
</body>
</html>
