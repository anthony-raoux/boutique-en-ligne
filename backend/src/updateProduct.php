<?php
require_once 'controllers/ProductController.php';

// Start session and check admin authentication
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure product ID is passed via GET parameter
if (!isset($_GET['id_produit'])) {
    header("Location: dashboard.php");
    exit();
}

// Initialize database connection (PDO assumed)
$dsn = 'mysql:host=localhost;dbname=boutique_en_ligne';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Initialize product controller with the database connection
$productController = new ProductController($db);

// Fetch product details for update
$productId = $_GET['id_produit'];
$productResult = $productController->getProductById($productId);

// Handle product not found
if (!$productResult['success']) {
    $_SESSION['message'] = "Produit non trouvé.";
    header("Location: dashboard.php");
    exit();
}

$product = $productResult['product'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])) {
    // Validate and sanitize input
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $id_categorie = intval($_POST['id_categorie']);

    // Handle image update
    $image = $product['image']; // Default to current image if not updated
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageTmpName = $_FILES['image']['tmp_name'];
    } else {
        $imageTmpName = null; // Handle case where image is not updated
    }

    // Update product in database
    $updateResult = $productController->updateProduct($productId, $nom, $description, $prix, $imageTmpName, $stock, $id_categorie);

    if ($updateResult['success']) {
        $_SESSION['message'] = "Produit mis à jour avec succès.";
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Erreur lors de la mise à jour du produit: " . htmlspecialchars($updateResult['error']);
    }
}

include 'head.php';
include 'navbar.php';
?>

<div class="container mx-auto px-4 py-8">

    <?php if (!empty($message)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center text-white mb-8">Modifier un produit</h1>

        <?php if (!empty($message)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Update product form -->
        <div class="p-8 rounded-lg">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id_produit=' . $productId); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold leading-6 text-white">Détails du Produit</h3>
                    <p class="mt-1 text-sm text-gray-400">Modifiez les informations du produit.</p>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Nom:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" class="w-full bg-stone-900 text-white px-3 py-2 border rounded">
                        </div>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Description:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border bg-stone-900 text-white rounded"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Prix:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <input type="number" id="prix" name="prix" step="0.01" value="<?php echo htmlspecialchars($product['prix']); ?>" class="w-full bg-stone-900 text-white px-3 py-2 border rounded">
                        </div>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Image:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <input type="file" id="image" name="image" class="w-full px-3 py-2 bg-stone-900 text-white border rounded">
                            <?php if ($product['image']) : ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="mt-4 w-24 h-24 object-cover">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Stock:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" class=" bg-stone-900 text-white w-full px-3 py-2 border rounded">
                        </div>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <div class="text-sm font-medium leading-6 text-white">Catégorie:</div>
                        <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            <input type="text" id="id_categorie" name="id_categorie" value="<?php echo htmlspecialchars($product['id_categorie']); ?>" class="w-full bg-stone-900 text-white px-3 py-2 border rounded">
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="updateProduct" class="bg-black hover:bg-white hover:text-black text-white px-4 py-2 rounded mt-10 w-2/4">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    require_once 'footer.php';
?>