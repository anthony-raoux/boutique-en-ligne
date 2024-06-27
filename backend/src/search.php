<?php
require_once 'controllers/ProductController.php';

$productController = new ProductController();

$query = $_GET['query'] ?? '';

if ($query) {
    $result = $productController->searchProducts($query);
    echo json_encode(['success' => true, 'products' => $result]);
} else {
    echo json_encode(['success' => false, 'products' => []]);
}
?>
