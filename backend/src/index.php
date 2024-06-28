<?php
session_start(); // Démarrage de la session (si ce n'est pas déjà fait)
require_once 'config/Database.php';

// Connexion à la base de données
$database = new Database();
$db = $database->connect();

// Requête pour récupérer 3 produits au hasard
$stmt_random = $db->query('SELECT * FROM produits ORDER BY RAND() LIMIT 3');
$random_products = $stmt_random->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer 3 autres produits au hasard (différents de la première requête)
$stmt_random2 = $db->query('SELECT * FROM produits ORDER BY RAND() LIMIT 3');
$random_products2 = $stmt_random2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../frontend/css/styles.css">
    <style>
        .product-image {
            width: 100%;
            height: auto;
            max-height: 400px; /* Ajustez la hauteur maximale selon vos besoins */
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="content">
        <h1 class="text-3xl font-bold mb-4">Welcome to the Home Page</h1>

        <!-- Section: 3 produits au hasard -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-2">Nos produits :  </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($random_products as $product): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <!-- Affichage de l'image -->
                        <?php 
                            $imageData = $product['image']; // Supposons que 'image' est le nom de votre colonne BLOB
                            $imageType = pathinfo($imageData, PATHINFO_EXTENSION);
                            $imageBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        ?>
                        <img src="<?= $imageBase64 ?>" alt="<?= htmlspecialchars($product['nom']) ?>" class="mb-2 product-image">
                        <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($product['nom']) ?></h3>
                        <p class="text-gray-600 mb-2"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="text-gray-800 font-semibold"><?= htmlspecialchars($product['prix']) ?> €</p>
                        <!-- Lien vers la page de détail du produit -->
                        <a href="details.php?product_id=<?= $product['id_produit'] ?>" class="block mt-2 text-blue-500 hover:text-blue-700">Voir les détails</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Section: 3 meilleures ventes -->
        <div>
            <h2 class="text-2xl font-semibold mb-2">Meilleures ventes :  </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($random_products2 as $product): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <!-- Affichage de l'image -->
                        <?php 
                            $imageData = $product['image']; // Supposons que 'image' est le nom de votre colonne BLOB
                            $imageType = pathinfo($imageData, PATHINFO_EXTENSION);
                            $imageBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        ?>
                        <img src="<?= $imageBase64 ?>" alt="<?= htmlspecialchars($product['nom']) ?>" class="mb-2 product-image">
                        <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($product['nom']) ?></h3>
                        <p class="text-gray-600 mb-2"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="text-gray-800 font-semibold"><?= htmlspecialchars($product['prix']) ?> €</p>
                        <!-- Lien vers la page de détail du produit -->
                        <a href="details.php?product_id=<?= $product['id_produit'] ?>" class="block mt-2 text-blue-500 hover:text-blue-700">Voir les détails</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="../frontend/js/script.js"></script>
</body>
</html>
