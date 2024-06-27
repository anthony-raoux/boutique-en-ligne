<?php
session_start();

require_once 'controllers/AddToCartController.php';
require_once 'config/Database.php'; // Assurez-vous que votre configuration de base de données est correctement incluse

// Récupérer les données du formulaire
$product_id = $_POST['product_id'] ?? '';
$quantity = $_POST['quantity'] ?? 1;

// Vérifier si les données sont valides
if (!$product_id || !is_numeric($product_id) || $quantity < 1) {
    echo "Erreur: Données de formulaire non valides.";
    exit;
}

// Récupérer l'ID de l'utilisateur à partir de la session (si nécessaire)
$user_id = $_SESSION['user_id'] ?? null;

// Créer une instance de AddToCartController
$controller = new AddToCartController();

// Appeler la méthode addToCart pour ajouter le produit au panier
$controller->addToCart($product_id, $quantity, $user_id);

// Redirection vers la page de détail du panier ou une autre page appropriée après l'ajout au panier
?>
