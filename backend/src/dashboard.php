<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer tous les produits
$result = $productController->getAllProducts();
$products = $result['products'] ?? [];


// Message d'erreur ou de succès lors des opérations
$message = '';

// Traitement des actions sur les catégories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'une nouvelle catégorie
    if (isset($_POST['addCategory'])) {
        $nomCategorie = htmlspecialchars($_POST['nom_categorie']);
        $idParentCategorie = isset($_POST['id_parent_categorie']) ? intval($_POST['id_parent_categorie']) : null;

        // Appel à la méthode du contrôleur pour ajouter la catégorie
        $result = $productController->addCategory($nomCategorie, $idParentCategorie);

        if ($result['success']) {
            $message = "Catégorie ajoutée avec succès.";
            // Mettre à jour la liste des catégories après l'ajout
            $categories = $productController->getCategories();
            var_dump($categories);
        } else {
            $message = "Erreur lors de l'ajout de la catégorie: " . $result['error'];
        }
    }

    // Suppression d'une catégorie
    if (isset($_POST['deleteCategory'])) {
        $idCategorie = intval($_POST['id_categorie']);

        // Appel à la méthode du contrôleur pour supprimer la catégorie
        $result = $productController->deleteCategory($idCategorie);

        if ($result['success']) {
            $message = "Catégorie supprimée avec succès.";
            // Mettre à jour la liste des catégories après la suppression
            $categories = $productController->getCategories();
        } else {
            $message = "Erreur lors de la suppression de la catégorie: " . $result['error'];
        }
    }
}

// Traitement des actions sur les produits
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un nouveau produit
    if (isset($_POST['addProduct'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $prix = floatval($_POST['prix']);
        $stock = intval($_POST['stock']);
        $id_categorie = intval($_POST['id_categorie']);

        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageTmpName = $_FILES['image']['tmp_name'];
            $result = $productController->addProduct($nom, $description, $prix, $imageTmpName, $stock, $id_categorie);

            if ($result['success']) {
                $message = "Produit ajouté avec succès.";
                $result = $productController->getAllProducts();
                $products = $result['products'] ?? [];
            } else {
                $message = "Erreur lors de l'ajout du produit: " . $result['error'];
            }
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
        }
    }
}

include 'head.php';
include 'navbar.php';
?>

<body class="bg-gray-100">
    <div class="container mx-auto my-5">

        <?php if (!empty($message)) : ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>


            <!-- Start block -->
            <section class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-black relative sm:rounded-lg overflow-hidden">

                    <div class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" placeholder="Search for products" required="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                            </form>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <a href="./dashboard-add_product.php" type="button" id="createProductButton" data-modal-toggle="createProductModal" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                <svg class="h-3.5 w-3.5 mr-1.5 -ml-1" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Ajouter un produits
                            </a>
                            <a href="./dashboard-add_category.php" type="button" id="createProductButton" data-modal-toggle="createProductModal" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                <svg class="h-3.5 w-3.5 mr-1.5 -ml-1" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Gérer mes catégories
                            </a>
                            <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-1.5 -ml-1 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Filtrer par catégorie
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="filterDropdown" class="z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0">
                                <div class="flex items-center justify-between pt-2">
                                    <h6 class="text-sm font-medium text-black dark:text-white">Filters</h6>
                                    <div class="flex items-center space-x-3">
                                        <a href="#" class="flex items-center text-sm font-medium text-primary-600 dark:text-primary-500 hover:underline">Save view</a>
                                        <a href="#" class="flex items-center text-sm font-medium text-primary-600 dark:text-primary-500 hover:underline">Clear all</a>
                                    </div>
                                </div>
                                <div class="pt-3 pb-2">
                                    <label for="input-group-search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" id="input-group-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search keywords...">
                                    </div>
                                </div>
                                <div id="accordion-flush" data-accordion="collapse" data-active-classes="text-black dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                                    <!-- Category -->
                                    <h2 id="category-heading">
                                        <button type="button" class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700" data-accordion-target="#category-body" aria-expanded="true" aria-controls="category-body">
                                            <span>Category</span>
                                            <svg aria-hidden="true" class="w-5 h-5 rotate-180 shrink-0" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="category-body" class="hidden" aria-labelledby="category-heading">
                                        <div class="py-2 font-light border-b border-gray-200 dark:border-gray-600">
                                            <ul class="space-y-2">
                                                <li class="flex items-center">
                                                    <input id="apple" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="apple" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Apple (56)</label>
                                                </li>
                                                <li class="flex items-center">
                                                    <input id="microsoft" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="microsoft" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Microsoft (45)</label>
                                                </li>
                                                <a href="#" class="flex items-center text-sm font-medium text-primary-600 dark:text-primary-500 hover:underline">View all</a>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="price-body" class="hidden" aria-labelledby="price-heading">
                                        <div class="flex items-center py-2 space-x-3 font-light border-b border-gray-200 dark:border-gray-600"><select id="price-from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"><option disabled="" selected="">From</option><option>$500</option><option>$2500</option><option>$5000</option></select><select id="price-to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"><option disabled="" selected="">To</option><option>$500</option><option>$2500</option><option>$5000</option></select></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">Produit</th>
                                    <th scope="col" class="p-4">Categories</th>
                                    <th scope="col" class="p-4">Stock</th>
                                    <th scope="col" class="p-4">Commentaires</th>
                                    <th scope="col" class="p-4">Prix</th>
                                    <th scope="col" class="p-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product) : ?>
                                    <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center mr-3 max-w-xs overflow-hidden">
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom'] ?? ''); ?>" class="h-8 w-auto mr-3">
                                                <?php else: ?>
                                                    <img src="../../images/placeholder.png" alt="Image par défaut" class="h-8 w-auto mr-3">
                                                <?php endif; ?>
                                                <span class="truncate"><?php echo htmlspecialchars($product['nom'] ?? ''); ?></span>
                                            </div>
                                        </th>
                                        <td class="px-4 py-3">
                                            <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300"><?php echo htmlspecialchars($product['nom_categorie'] ?? ''); ?></span>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <?php echo htmlspecialchars($product['stock'] ?? ''); ?>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center max-w-xs overflow-hidden">
                                                <span class="truncate"><?php echo htmlspecialchars($product['description'] ?? ''); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($product['prix'] ?? ''); ?></td>
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center space-x-4">
                                                <!-- Form pour modifier un produit -->
                                                <form action="updateProduct.php" method="get" class="inline">
                                                    <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                                    <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:outline-none focus:ring-primary-300 border border-gray-200 hover:bg-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                
                                                <!-- Form pour voir un produit -->
                                                <form action="" method="post">
                                                    <input type="hidden">
                                                    <button type="button" class="py-2 px-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -ml-0.5">
                                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                
                                                <!-- Form pour supprimer un produit -->
                                                <form action="deleteProduct.php" method="post" class="inline">
                                                    <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                                    <button type="button" class="delete-product-button flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                                            data-product-id="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zm1 5a1 1 0 011 1v7a1 1 0 11-2 0V8a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </section>
        <!-- End block -->

    </div>
<?php include 'footer.php'; ?>


<!-- Modal de confirmation de suppression -->
<div id="delete-product-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-stone-900 p-6 rounded-lg border border-gray-300 w-full max-w-md">
        <p class="text-lg mb-4 text-white">Êtes-vous sûr de vouloir supprimer ce produit ?</p>
        <div class="flex justify-end">
            <form id="delete-product-form" action="deleteProduct.php" method="post">
                <input type="hidden" id="product-id" name="product_id" value="">
                <button type="submit" name="deleteProduct" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg mr-2">Oui, Supprimer</button>
                <button type="button" id="cancel-delete" class="border border-gray-300 px-4 py-2 rounded-lg text-white hover:bg-stone-800">Annuler</button>
            </form>
        </div>
    </div>
</div>



<script>
    const deleteButtons = document.querySelectorAll('.delete-product-button');
    const deleteProductModal = document.getElementById('delete-product-modal');
    const cancelDeleteButton = document.getElementById('cancel-delete');
    const deleteProductForm = document.getElementById('delete-product-form');
    const productIdInput = document.getElementById('product-id');

    // Écouteurs d'événements pour chaque bouton de suppression de produit
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const productId = this.dataset.productId;
            productIdInput.value = productId; // Remplit l'ID du produit dans le champ caché
            deleteProductModal.classList.remove('hidden'); // Affiche le modal de confirmation
        });
    });

    // Écouteur d'événement pour le bouton Annuler du modal
    cancelDeleteButton.addEventListener('click', function() {
        deleteProductModal.classList.add('hidden'); // Cache le modal de confirmation
    });
</script>
