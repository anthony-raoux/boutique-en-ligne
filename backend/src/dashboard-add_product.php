<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    session_start();

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    require_once 'controllers/ProductController.php';

    $productController = new ProductController();
    $result = $productController->getAllProducts();
    $products = $result['products'] ?? [];
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['addProduct'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $description = htmlspecialchars($_POST['description']);
            $prix = floatval($_POST['prix']);
            $stock = intval($_POST['stock']);
            $id_categorie = isset($_POST['id_categorie']) ? intval($_POST['id_categorie']) : null;

            if ($id_categorie === null) {
                $message = "Erreur: la catégorie n'est pas sélectionnée.";
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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

    include_once 'head.php';
    include_once 'navbar.php';
?>

<section class="mx-auto max-w-screen-2xl px-4 lg:px-12">
    <div class="bg-black text-white p-6 rounded-lg my-6">
        <h2 class="text-2xl font-bold mb-4">Ajouter un produit</h2>
        <?php if ($message): ?>
            <div class="bg-red-500 text-white p-2 mb-4 rounded">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nom" class="block text-white">Nom:</label>
                <input type="text" id="nom" name="nom" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required>
            </div>
            <div class="mb-4">
                <label for="prix" class="block text-gray-700 text-white">Prix:</label>
                <input type="number" id="prix" name="prix" step="0.01" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required>
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 text-white">Stock:</label>
                <input type="number" id="stock" name="stock" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required>
            </div>
            <div class="mb-4">
                <label for="id_categorie" class="block text-gray-700 text-white">Catégorie:</label>
                <select id="id_categorie" name="id_categorie" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required>
                    <option value="" disabled selected>Sélectionner une catégorie</option>
                    <option value="1">Consoles</option>
                    <option value="2">Accessoires</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-white">Description:</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required></textarea>
            </div>
            <div class="col-span-full mb-10">
                <label for="cover-photo" class="block text-sm font-medium leading-6 text-white">Image:</label>
                <div class="mt-2 flex justify-center rounded-lg border border-gray-300 bg-zinc-900 text-white px-6 py-10 rounded">
                    <label for="image" class="relative cursor-pointer w-full flex flex-col items-center text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                        <div class="mt-4 flex text-sm leading-6 text-gray-600">
                            <span class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>Upload a file</span>
                                <input id="image" name="image" type="file" class="sr-only">
                            </span>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                    </label>
                </div>
            </div>
            <button type="submit" name="addProduct" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Ajouter</button>
        </form>
    </div>
</section>

<?php include_once 'footer.php'; ?>
