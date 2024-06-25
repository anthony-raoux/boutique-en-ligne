<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer toutes les catégories
$categories = $productController->getCategories();

// Récupérer tous les produits ou filtrer par catégorie
$categoryFilter = $_GET['category'] ?? '';
$result = $productController->getAllProducts($categoryFilter);
$products = $result['products'] ?? [];

// Debugging: Check the result of getAllProducts
if (!$result['success']) {
    echo "Erreur : " . $result['error'];
}

// Message d'erreur ou de succès lors des opérations
$message = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <style>
        .products { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 20px; }
        .product { border: 1px solid #ccc; border-radius: 8px; padding: 15px; width: 300px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .product img { width: 100%; height: auto; border-radius: 8px; }
        .product h2 { font-size: 1.5rem; margin-bottom: 10px; }
        .product p { font-size: 1rem; line-height: 1.5; }
        .product .price { font-size: 1.25rem; font-weight: bold; color: #007bff; }
        .filter { margin-bottom: 20px; }
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <h1>Shop</h1>
        <form id="categoryFilterForm" method="GET">
            <label for="category">Filtrer par catégorie :</label>
            <select id="category" name="category">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id_categorie']); ?>" <?php echo $categoryFilter == $category['id_categorie'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['nom']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <div class="products">
            <?php if (empty($products)): ?>
                <p>Aucun produit disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product" data-id="<?php echo $product['id_produit']; ?>">
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image indisponible" />
                        <?php endif; ?>
                        <h2><?php echo htmlspecialchars($product['nom'] ?? 'Nom indisponible'); ?></h2>
                        <p><?php echo htmlspecialchars($product['description'] ?? 'Description indisponible'); ?></p>
                        <p class="price">Prix: <?php echo htmlspecialchars($product['prix'] ?? 'Prix indisponible'); ?> €</p>
                        <p>Stock: <?php echo htmlspecialchars($product['stock'] ?? 'Stock indisponible'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <footer></footer>
    <script>
        document.querySelectorAll('.product').forEach(product => {
            product.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                window.location.href = 'details.php?product_id=' + encodeURIComponent(productId);
            });
        });
    </script>
</body>
</html>
