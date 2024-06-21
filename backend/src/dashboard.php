<?php
session_start();

// Vérifiez si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirigez vers la page de connexion si non connecté
    exit();
}

// Inclure votre logique de gestion des produits et autres données nécessaires

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php
    $currentPage = 'dashboard'; // Définissez la page actuelle pour surligner dans la navbar
    include 'navbar.php'; // Inclure la navbar
    ?>

    <div class="content">
        <h1>Dashboard</h1>

        <!-- Affichage des informations du tableau de bord -->

        <h3>Total des produits</h3>
        <?php
        // Placeholder pour le nombre total de produits
        $totalProducts = 0;
        echo "<p>Total des produits: $totalProducts</p>";
        ?>

        <h3>Liste des produits</h3>
        <?php
        // Placeholder pour afficher la liste des produits
        $products = []; // Initialisation d'un tableau vide par défaut

        // Vérifiez si $products est défini et non vide avant d'itérer dessus
        if (isset($products) && is_array($products) && count($products) > 0) {
            echo "<ul>";
            foreach ($products as $product) {
                echo "<li>{$product['nom_produit']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucun produit trouvé.</p>";
        }
        ?>

    </div>

    <script src="../frontend/js/script.js"></script>
</body>
</html>
