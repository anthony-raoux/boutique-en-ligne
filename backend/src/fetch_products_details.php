<?php
session_start();
require_once 'controllers/ProductController.php';

// Vérifier si l'ID du produit est fourni dans les paramètres GET
if (isset($_GET['product_id'])) {
    // Récupérer l'ID du produit depuis les paramètres GET
    $productId = $_GET['product_id'];

    // Initialiser le contrôleur de produit
    $productController = new ProductController();

    // Récupérer les détails du produit
    $productDetails = $productController->getProductDetails($productId);

    if ($productDetails) {
        // Construire la réponse JSON avec les détails du produit
        $response = [
            'success' => true,
            'product' => [
                'nom' => $productDetails['nom'],
                'description' => $productDetails['description'],
                'prix' => $productDetails['prix'],
                'stock' => $productDetails['stock'],
                // Ajouter d'autres détails si nécessaire
            ]
        ];
    } else {
        // Cas où le produit n'est pas trouvé
        $response = [
            'success' => false,
            'error' => 'Produit non trouvé'
        ];
    }
} else {
    // Cas où l'ID du produit n'est pas spécifié
    $response = [
        'success' => false,
        'error' => 'ID de produit non spécifié'
    ];
}

// Retourner la réponse JSON
header('Content-Type: application/json');
echo json_encode($response);

