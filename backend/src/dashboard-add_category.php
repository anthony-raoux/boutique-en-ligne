<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Charger les catégories existantes si elles ne sont pas déjà en session
if (!isset($_SESSION['categories'])) {
    $result = $productController->getCategories();
    $_SESSION['categories'] = $result['categories'] ?? [];
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
            $categories = $productController->getCategories();
            $_SESSION['categories'] = $categories['categories'] ?? [];
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
            $categories = $productController->getCategories();
            $_SESSION['categories'] = $categories['categories'] ?? [];
        } else {
            $message = "Erreur lors de la suppression de la catégorie: " . $result['error'];
        }
    }
}

$categories = $_SESSION['categories'];

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
        
   
<?php include_once 'footer.php'; ?>