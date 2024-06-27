<?php
session_start();
require_once './controllers/CartDetailController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        // Instancier le contrôleur CartDetailController
        $controller = new CartDetailController();
        $controller->removeFromCart($_SESSION['user_id'], $product_id);

        // Redirection vers la page du panier après la suppression
        header('Location: cart_detail.php');
        exit;
    }
}
