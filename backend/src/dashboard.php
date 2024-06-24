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
$products = $result['products'] ?? [];  // Récupérer les produits ou initialiser à un tableau vide

// Debugging: Check the result of getAllProducts
if (!$result['success']) {
    echo "Erreur : " . $result['error'];
} else {
    echo "Produits récupérés avec succès";
}

// Message d'erreur ou de succès lors des opérations
$message = '';

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
                $result = $productController->getAllProducts(); // Mettre à jour la liste des produits après l'ajout
                $products = $result['products'] ?? [];  // Récupérer les produits ou initialiser à un tableau vide
            } else {
                $message = "Erreur lors de l'ajout du produit: " . $result['error'];
            }
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
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
                    <input type="file" id="image" name="image" required>
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
            <?php if (empty($products)) : ?>
                <p>Aucun produit trouvé.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Image</th>
                            <th>Stock</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['nom'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($product['description'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($product['prix'] ?? ''); ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom'] ?? ''); ?>" width="50">
                                    <?php else: ?>
                                        <img src="path/to/default/image.jpg" alt="Image par défaut" width="50">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['stock'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($product['nom_categorie'] ?? ''); ?></td>
                                <td>
                                    <form action="deleteProduct.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                    <form action="updateProduct.php" method="get" style="display:inline;">
                                        <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                        <button type="submit">Modifier</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
