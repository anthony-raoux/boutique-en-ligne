<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer toutes les catégories
$resultCategories = $productController->getCategories();
$categories = $resultCategories['categories'] ?? [];

// Récupérer tous les produits ou filtrer par catégorie
$categoryFilter = $_GET['category'] ?? '';
$resultProducts = $productController->getAllProducts($categoryFilter);
$products = $resultProducts['products'] ?? [];

// Debugging: Check the result of getAllProducts
if (!$resultProducts['success']) {
    echo "Erreur : " . htmlspecialchars($resultProducts['error']);
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
    
    </style>
</head>
</head>
<body class="bg-gray-100">
    <?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <h1 class="text-3xl font-bold mt-8 mb-4 text-center">Shop</h1>
        <form id="categoryFilterForm" method="GET" class="flex justify-center items-center mb-8">
            <label for="category" class="mr-2">Filtrer par catégorie :</label>
            <select id="category" name="category" class="border rounded py-2 px-4">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id_categorie']); ?>" <?php echo $categoryFilter == $category['id_categorie'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['nom']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white rounded py-2 px-4 ml-2">Filtrer</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="text-center text-red-500"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($products)): ?>
                <p class="text-center text-xl">Aucun produit disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center hover:bg-gray-100 transition duration-300" data-id="<?php echo htmlspecialchars($product['id_produit']); ?>">
                        <h2 class="text-xl font-bold mb-2 text-center"><?php echo htmlspecialchars($product['nom']); ?></h2>
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="w-full h-auto rounded-lg mb-4">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image indisponible" class="w-full h-auto rounded-lg mb-4">
                        <?php endif; ?>
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