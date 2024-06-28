<?php
session_start();
require_once './controllers/CartDetailController.php';

$controller = new CartDetailController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'] ?? null;
    
    if ($product_id) {
        $controller->removeFromCart($_SESSION['user_id'], $product_id);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    }
    exit;
}
?>
