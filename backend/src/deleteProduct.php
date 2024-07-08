<?php
require_once 'controllers/ProductController.php';

// Start session and check admin authentication
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize product controller
$productController = new ProductController();

// Fetch all products to display in the dropdown
$productResult = $productController->getAllProducts();

// Handle form submission for product deletion
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct'])) {
    $productId = $_POST['product_id'];

    // Delete the product
    $deleteResult = $productController->deleteProduct($productId);

    if ($deleteResult['success']) {
        $_SESSION['message'] = "Produit supprimé avec succès.";
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Erreur lors de la suppression du produit: " . htmlspecialchars($deleteResult['error']);
    }
}

?>


    <div class="content">
        <h1 class="text-white">Supprimer un produit</h1>

        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="delete-product-form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label  class="text-white" for="product_id">Sélectionner un produit à supprimer :</label>
                    <select id="product_id" name="product_id" required>
                        <option value="" disabled selected>Choisir un produit</option>
                        <?php foreach ($productResult['products'] as $product) : ?>
                            <option value="<?php echo $product['id_produit']; ?>"><?php echo htmlspecialchars($product['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="text-white" type="submit" name="deleteProduct">Supprimer le produit</button>
            </form>
        </div>
    </div>