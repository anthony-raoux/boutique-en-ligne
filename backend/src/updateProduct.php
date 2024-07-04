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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit - Dashboard</title>
  <style>
    /* Styles pour la page de modification de produit */

body {
    font-family: Arial, sans-serif;
    background-color: #000; /* Fond noir */
    color: gray; /* Texte blanc */
    margin: 0;
    padding: 0;
}

.content {
    padding: 20px;
}

h1 {
    color: #fff; /* Titre en texte blanc */
}

.message {
    background-color: #007bff; /* Fond bleu pour le message */
    color: gray; /* Texte blanc pour le message */
    padding: 10px;
    margin-bottom: 20px;
}

.update-product-form {
    background-color: #333; /* Fond gris foncé pour le formulaire de mise à jour */
    color: gray; /* Texte blanc pour le formulaire de mise à jour */
    padding: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 10px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea,
.form-group select,
.form-group button,
.form-group input[type="file"] {
    width: 100%;
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 5px;
}

.form-group textarea {
    resize: vertical;
    height: 100px; /* Ajuster la hauteur de la zone de texte */
}

.form-group button {
    background-color: #007bff; /* Couleur de fond pour les boutons */
    color: #fff; /* Texte blanc pour les boutons */
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 16px;
}

.form-group button:hover {
    background-color: #0056b3; /* Couleur de fond des boutons au survol */
}

.form-group img {
    display: block;
    margin-top: 10px;
    max-width: 100%;
}


  </style>
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
