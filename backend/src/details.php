<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer l'ID du produit depuis les paramètres GET
$product_id = $_GET['product_id'] ?? '';

// Vérifier si product_id est vide ou non défini
if (!$product_id) {
    echo "ID de produit manquant.";
    exit;
}

// Récupérer les détails du produit
$result = $productController->getProductById($product_id);
$product = $result['product'] ?? null;

// Vérifier si le produit existe
if (!$product) {
    echo "Produit non trouvé.";
    exit;
}

// Initialisation du panier s'il n'existe pas déjà en session
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

            <form id="add-to-cart-form" action="addToCart.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product['id_produit']; ?>">
    <input type="number" name="quantity" value="1" min="1" max="10"> <!-- Champ de quantité -->
    <button type="submit">Ajouter au panier</button>
</form>


            <?php
            // Vérifier si le produit est dans le panier
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                echo '<button class="remove-item" onclick="removeFromCart(' . $product['id_produit'] . ')">Supprimer</button>';
            }
            ?>
        </div>
    </main>
    <footer></footer>
  
</body>
</html>
