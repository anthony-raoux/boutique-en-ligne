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
            <!-- oui 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- oui 8 -->
                <?php if (empty($products)): ?>
                    <p class="col-span-full text-center text-gray-500">Aucun produit disponible pour le moment.</p>
                <?php else: ?>
                    <!-- oui 4 -->
                    <?php foreach ($products as $product): ?>
                        <!-- oui 9 -->
                        <div class="product cursor-pointer bg-black text-white border border-gray-500 rounded p-6 flex flex-col items-center" data-id="<?php echo $product['id_produit']; ?>">
                            <!-- oui 10 -->
                            <h2 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($product['nom']); ?></h2>
                            <!-- oui 11 -->
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

    <!-- Modal -->
    <div id="productModal" class="fixed inset-0 hidden z-10 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="block absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" class="text-gray-400 hover:text-gray-500" id="closeModal">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modalContent"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" id="closeModalButton">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    

    <script>
        document.querySelectorAll('.product').forEach(product => {
            product.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                fetch(`details.php?product_id=${encodeURIComponent(productId)}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('modalContent').innerHTML = data;
                        document.getElementById('productModal').classList.remove('hidden');
                    });
            });
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('productModal').classList.add('hidden');
        });

        document.getElementById('closeModalButton').addEventListener('click', () => {
            document.getElementById('productModal').classList.add('hidden');
        });
    </script>
