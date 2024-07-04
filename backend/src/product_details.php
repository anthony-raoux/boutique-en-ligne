<!-- product_details.php -->
<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Vérifier si un ID de produit est passé dans l'URL
if (!isset($_GET['product_id'])) {
    header("Location: shop.php"); // Rediriger vers la page principale du shop si l'ID n'est pas spécifié
    exit();
}

$product_id = $_GET['product_id'];

// Récupérer les détails du produit
$result = $productController->getProductById($product_id);

if (!$result['success']) {
    echo "Erreur : " . $result['error'];
    exit();
}

$product = $result['product'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit - <?php echo htmlspecialchars($product['nom']); ?></title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Assurez-vous de mettre le bon chemin vers votre fichier CSS -->
    <style>
        /* Vos styles CSS spécifiques pour la page de détails */
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
<header>
    <!-- Inclure ici le code de votre en-tête (Header) si nécessaire -->
</header>

<main>
    <h1>Détails du produit - <?php echo htmlspecialchars($product['nom']); ?></h1>

    <div class="product-details">
        <?php if (!empty($product['image'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
        <?php else: ?>
            <img src="placeholder.jpg" alt="Image indisponible" /> <!-- Remplacez placeholder.jpg par votre image par défaut -->
        <?php endif; ?>
        <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
        <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
        <!-- Ajoutez ici d'autres informations sur le produit si nécessaire -->
        
        <!-- Exemple de bouton d'ajout au panier -->
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id_produit']; ?>">
            <button type="submit">Ajouter au panier</button>
        </form>
    </div>
</main>

<footer>
    <!-- Inclure ici le code de votre pied de page (Footer) si nécessaire -->
</footer>

</body>
</html>
