<?php
require_once 'controllers/ProductController.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

$productController = new ProductController();
$products = $productController->getProductsByCategory($category_id);

header('Content-Type: application/json');
echo json_encode($products);
?>
