<?php
require_once 'controllers/ProductController.php';

$category = $_GET['category'] ?? '';

$productController = new ProductController();
$result = $productController->getProductsByCategory($category);

header('Content-Type: application/json');
echo json_encode($result);
?>
