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

// Initialize product controller
$productController = new ProductController();

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
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Update product in database
    $updateResult = $productController->updateProduct($productId, $nom, $description, $prix, $image, $stock, $id_categorie);

    if ($updateResult['success']) {
        $_SESSION['message'] = "Produit mis à jour avec succès.";
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Erreur lors de la mise à jour du produit: " . htmlspecialchars($updateResult['error']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit - Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Modifier un produit</h1>

        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Update product form -->
        <div class="update-product-form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id_produit=' . $productId); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Prix:</label>
                    <input type="number" id="prix" name="prix" step="0.01" value="<?php echo htmlspecialchars($product['prix']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image">
                    <?php if ($product['image']) : ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" width="100">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_categorie">Catégorie:</label>
                    <input type="number" id="id_categorie" name="id_categorie" value="<?php echo htmlspecialchars($product['id_categorie']); ?>" required>
                </div>
                <button type="submit" name="updateProduct">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>
