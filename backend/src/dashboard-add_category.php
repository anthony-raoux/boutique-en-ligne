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

// Vérifier les erreurs de récupération des catégories
if (isset($resultCategories['success']) && !$resultCategories['success']) {
    echo "Erreur lors de la récupération des catégories : " . $resultCategories['error'];
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

include 'head.php';
include 'navbar.php';
?>


    <!-- Section pour gérer les catégories -->
        <section class="max-h-screen">
            <div class="mx-auto py-5 px-4 lg:px-12">
                <div class="bg-black text-white p-6 rounded-lg my-6">
                    <h2 class="text-2xl font-bold mb-4 text-white">Ajouter une nouvelle catégorie</h2>

                    <!-- Formulaire pour ajouter une nouvelle catégorie -->
                    <div class="mb-6 ">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="mb-4">
                                <label for="nom_categorie" class="block text-white">Nom de la catégorie:</label>
                                <input type="text" id="nom_categorie" name="nom_categorie" class="w-full px-3 py-2 text-sm bg-zinc-900 border border-gray-300 text-white rounded" required>
                            </div>
                            <button type="submit" name="addCategory" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>



          <!-- Liste des catégories existantes -->
        <div class="bg-black text-white p-6 rounded-lg my-6">
            <h2 class="text-2xl font-bold mb-4 text-white">Liste des catégories</h2>
            <?php if (empty($categories)) : ?>
                <p>Aucune catégorie trouvée.</p>
            <?php else : ?>
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $categorie) : ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($categorie['nom'] ?? ''); ?></td>
                                <td class="border px-4 py-2">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="id_categorie" value="<?php echo htmlspecialchars($categorie['id_categorie']); ?>">
                                        <button type="submit" name="deleteCategory" class="bg-red-500 text-white px-4 py-2 rounded">Supprimer</button>
                                    </form>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="id_categorie" value="<?php echo htmlspecialchars($categorie['id_categorie']); ?>">
                                        <input type="text" name="nom_categorie" value="<?php echo htmlspecialchars($categorie['nom']); ?>" required>
                                        <button type="submit" name="updateCategory" class="bg-yellow-500 text-white px-4 py-2 rounded">Modifier</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
   
<?php include_once 'footer.php'; ?>