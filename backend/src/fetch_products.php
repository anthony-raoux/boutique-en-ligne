<?php
// fetch_products.php

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer la catégorie filtrée depuis la requête GET
$category = $_GET['category'] ?? '';

// Récupérer les produits filtrés par catégorie
$result = $productController->getProductsByCategory($category);

// Préparer la réponse JSON
$response = [
    'success' => false,
    'error' => 'Erreur lors de la récupération des produits.'
];

if ($result['success']) {
    $response = [
        'success' => true,
        'products' => $result['products']
    ];
} else {
    $response['error'] = $result['error'];
}

// Envoyer la réponse JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
