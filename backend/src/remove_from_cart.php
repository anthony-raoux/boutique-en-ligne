<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'error' => 'Produit non trouvé']);
    exit;
}

// Supprimer le produit du panier
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Produit non trouvé dans le panier']);
}

// Supprimer le produit du panier dans la base de données
$query = $db->prepare('DELETE FROM ligne_de_commande WHERE user_id = ? AND product_id = ?');
$query->execute([$user_id, $product_id]);

?>