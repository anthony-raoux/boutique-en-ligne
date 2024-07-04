<?php
session_start();
require_once 'controllers/ProductController.php';

header('Content-Type: application/json');

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $productController = new ProductController();
    $productDetails = $productController->getProductDetails($productId);

    if ($productDetails) {
        $response = [
            'success' => true,
            'product' => [
                'nom' => $productDetails['nom'],
                'description' => $productDetails['description'],
                'prix' => $productDetails['prix'],
                'stock' => $productDetails['stock'],
            ]
        ];
    } else {
        $response = [
            'success' => false,
            'error' => 'Produit non trouvé'
        ];
    }
} else {
    $response = [
        'success' => false,
        'error' => 'ID de produit non spécifié'
    ];
}

echo json_encode($response);
?>
