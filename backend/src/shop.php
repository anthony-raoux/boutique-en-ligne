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

include_once 'head.php';
include_once 'navbar.php';
?>


    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- oui 1 -->
            <form id="categoryFilterForm" method="GET" class="mb-6">
                <!-- oui 5 -->
                <div class="flex items-center gap-5">
                    <!-- oui 6 -->
                    <div class="w-full">
                        <label for="category" class="block text-sm font-medium text-white">Filtrer par catégorie :</label>
                        <select id="category" name="category" class="block w-full border border-gray-500 bg-black text-white p-2 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id_categorie']); ?>" <?php echo $categoryFilter == $category['id_categorie'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="mt-5 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Filtrer</button>
                </div>
            </form>
            <!-- oui 2 -->
            <?php if (!empty($message)): ?>
                <!-- oui 7 -->
                <p class="mb-4 text-sm text-red-500"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php if (empty($products)): ?>
                    <p class="col-span-full text-center text-gray-500">Aucun produit disponible pour le moment.</p>
                <?php else: ?>

                    <?php foreach ($products as $product): ?>

                        <div class="product bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center hover:bg-gray-100 transition duration-300" data-id="<?php echo htmlspecialchars($product['id_produit']); ?>">

                            <h2 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($product['nom']); ?></h2>

                            <?php if (!empty($product['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="w-full h-48 object-cover rounded-md mb-4"/>
                            <?php else: ?>
                                <img src="placeholder.jpg" alt="Image indisponible" class="w-full h-48 object-cover rounded-md mb-4"/>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
    </main>

    <?php 
        include_once 'footer.php';
    ?>

<script>
        document.querySelectorAll('.product').forEach(product => {
            product.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                window.location.href = 'details.php?product_id=' + encodeURIComponent(productId);
            });
        });
    </script>