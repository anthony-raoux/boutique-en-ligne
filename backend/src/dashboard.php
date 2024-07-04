<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer toutes les catégories
$resultCategories = $productController->getCategories();
$categories = $resultCategories['categories'] ?? [];

// Récupérer tous les produits
$resultProducts = $productController->getAllProducts();
$products = $resultProducts['products'] ?? [];

// Vérifier les erreurs de récupération des catégories et des produits
if (isset($resultCategories['success']) && !$resultCategories['success']) {
    echo "Erreur lors de la récupération des catégories : " . $resultCategories['error'];
}

if (isset($resultProducts['success']) && !$resultProducts['success']) {
    echo "Erreur lors de la récupération des produits : " . $resultProducts['error'];
} else {
    echo "Produits récupérés avec succès";
}

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
            $resultCategories = $productController->getCategories();
            $categories = $resultCategories['categories'] ?? [];
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
            $resultCategories = $productController->getCategories();
            $categories = $resultCategories['categories'] ?? [];
        } else {
            $message = "Erreur lors de la suppression de la catégorie: " . $result['error'];
        }
    }

    // Mise à jour d'une catégorie
    if (isset($_POST['updateCategory'])) {
        $idCategorie = intval($_POST['id_categorie']);
        $nomCategorie = htmlspecialchars($_POST['nom_categorie']);
        $idParentCategorie = isset($_POST['id_parent_categorie']) ? intval($_POST['id_parent_categorie']) : null;

        // Appel à la méthode du contrôleur pour mettre à jour la catégorie
        $result = $productController->updateCategory($idCategorie, $nomCategorie, $idParentCategorie);

        if ($result['success']) {
            $message = "Catégorie mise à jour avec succès.";
            // Mettre à jour la liste des catégories après la mise à jour
            $resultCategories = $productController->getCategories();
            $categories = $resultCategories['categories'] ?? [];
        } else {
            $message = "Erreur lors de la mise à jour de la catégorie: " . $result['error'];
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
                $resultProducts = $productController->getAllProducts(); // Mettre à jour la liste des produits après l'ajout
                $products = $resultProducts['products'] ?? [];  // Récupérer les produits ou initialiser à un tableau vide
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
    <style>
    /* Styles généraux */
    body {
        font-family: Arial, sans-serif;
        background-color: #000;
        color: gray;
        margin: 0;
        padding: 0;
    }

    .content {
        padding: 20px;
    }

    h1, h2, h3 {
        color: #fff;
    }

    .message {
        background-color: #007bff; /* Couleur de fond pour le message */
        color: #fff; /* Texte blanc pour le message */
        padding: 10px;
        margin-bottom: 20px;
    }

    /* Styles spécifiques pour le formulaire d'ajout de produit avec fond blanc */

    .add-product-form.white-background {
        background-color: #fff; /* Fond blanc */
        color: #000; /* Texte noir */
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #ccc; /* Bordure grise */
        border-radius: 4px;
    }

    .add-product-form.white-background .form-group label {
        color: #000; /* Couleur du texte noir pour les labels */
    }

    .add-category-form {
        background-color: #333; /* Fond gris foncé pour les formulaires d'ajout */
        color: gray; /* Texte blanc pour les formulaires d'ajout */
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #555; /* Bordure plus foncée */
        border-radius: 4px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #fff; /* Couleur du texte blanc pour les labels */
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

    .category-list ul,
    .product-list table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .category-list ul li,
    .product-list table th,
    .product-list table td {
        border: 1px solid #ccc;
        padding: 8px;
    }

    .product-list table th {
        background-color: #007bff; /* Couleur de fond pour les entêtes de tableau */
        color: #fff; /* Texte blanc pour les entêtes de tableau */
        font-weight: bold;
        text-align: left;
    }

    .product-list table td img {
        display: block;
        margin: 0 auto;
    }

    .product-list table td:last-child form {
        display: inline-block;
        margin-left: 5px;
    }

    .product-list table td:last-child form button {
        background-color: #dc3545; /* Couleur de fond pour les boutons de suppression */
        color: #fff; /* Texte blanc pour les boutons de suppression */
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
    }

    .product-list table td:last-child form button:hover {
        background-color: #c82333; /* Couleur de fond des boutons de suppression au survol */
    }
</style>

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

        <!-- Section pour gérer les catégories -->
        <div class="category-management">
            <h2>Gestion des catégories</h2>
            
            <!-- Formulaire pour ajouter une nouvelle catégorie -->
            <div class="add-category-form">
                <h3>Ajouter une catégorie</h3>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-group">
                        <label for="nom_categorie">Nom de la catégorie:</label>
                        <input type="text" id="nom_categorie" name="nom_categorie" required>
                    </div>
                    <div class="form-group">
                        <label for="id_parent_categorie">Catégorie parente (optionnel):</label>
                        <input type="number" id="id_parent_categorie" name="id_parent_categorie">
                    </div>
                    <button type="submit" name="addCategory">Ajouter</button>
                </form>
            </div>

           <!-- Liste des catégories existantes -->
<div class="category-list">
    <h3>Liste des catégories</h3>
    <?php if (empty($categories)) : ?>
        <p>Aucune catégorie trouvée.</p>
    <?php else : ?>
        <ul>
            <?php foreach ($categories as $category) : ?>
                <li>
                    <?php echo htmlspecialchars($category['nom'] ?? ''); ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id_categorie" value="<?php echo htmlspecialchars($category['id_categorie'] ?? ''); ?>">
                        <button type="submit" name="deleteCategory">Supprimer</button>
                    </form>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id_categorie" value="<?php echo htmlspecialchars($category['id_categorie'] ?? ''); ?>">
                        <input type="text" name="nom_categorie" value="<?php echo htmlspecialchars($category['nom'] ?? ''); ?>" required>
                        <input type="number" name="id_parent_categorie" value="<?php echo htmlspecialchars($category['id_parent_categorie'] ?? ''); ?>">
                        <button type="submit" name="updateCategory">Modifier</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
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
