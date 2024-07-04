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
    <h1 class="text-3xl font-bold text-center mb-8">Modifier un produit</h1>

    <?php if (!empty($message)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Update product form -->
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id_produit=' . $productId); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description:</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border rounded" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="prix" class="block text-gray-700">Prix:</label>
                <input type="number" id="prix" name="prix" step="0.01" value="<?php echo htmlspecialchars($product['prix']); ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Image:</label>
                <input type="file" id="image" name="image" class="w-full px-3 py-2 border rounded">
                <?php if ($product['image']) : ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="mt-4 w-24 h-24 object-cover">
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-gray-700">Stock:</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="id_categorie" class="block text-gray-700">Catégorie:</label>
                <input type="number" id="id_categorie" name="id_categorie" value="<?php echo htmlspecialchars($product['id_categorie']); ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <button type="submit" name="updateProduct" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
        </form>
    </div>
</div>
</body>
</html>
