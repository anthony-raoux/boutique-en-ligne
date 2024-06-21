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
$products = $productController->getAllProducts();

// Message d'erreur ou de succès lors des opérations
$message = '';

// Traitement des actions sur les produits
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un nouveau produit
    if (isset($_POST['addProduct'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $prix = floatval($_POST['prix']);
        $image = htmlspecialchars($_POST['image']); // À adapter selon votre gestion d'images
        $stock = intval($_POST['stock']);
        $id_categorie = intval($_POST['id_categorie']);

        $result = $productController->addProduct($nom, $description, $prix, $image, $stock, $id_categorie);

        if ($result['success']) {
            $message = "Produit ajouté avec succès.";
            $products = $productController->getAllProducts(); // Mettre à jour la liste des produits après l'ajout
        } else {
            $message = "Erreur lors de l'ajout du produit: " . $result['error'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des produits</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Dashboard - Gestion des produits</h1>

        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Formulaire pour ajouter un nouveau produit -->
        <div class="add-product-form">
            <h2>Ajouter un produit</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Prix:</label>
                    <input type="number" id="prix" name="prix" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="text" id="image" name="image">
                </div>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
                <div class="form-group">
                    <label for="id_categorie">Catégorie:</label>
                    <input type="number" id="id_categorie" name="id_categorie" required>
                </div>
                <button type="submit" name="addProduct">Ajouter</button>
            </form>
        </div>

    <!-- Liste des produits existants -->
<div class="product-list">
    <h2>Liste des produits</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products) && is_array($products)) : ?>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo isset($product['nom']) ? htmlspecialchars($product['nom']) : ''; ?></td>
                        <td><?php echo isset($product['description']) ? htmlspecialchars($product['description']) : ''; ?></td>
                        <td><?php echo isset($product['prix']) ? number_format($product['prix'], 2, ',', ' ') : ''; ?></td>
                        <td><?php echo isset($product['stock']) ? $product['stock'] : ''; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo isset($product['id_produit']) ? $product['id_produit'] : ''; ?>">Modifier</a>
                            <a href="delete_product.php?id=<?php echo isset($product['id_produit']) ? $product['id_produit'] : ''; ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun produit trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


    <script src="../frontend/js/script.js"></script>
</body>
</html>
