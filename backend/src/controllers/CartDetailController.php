<?php
require_once __DIR__ . '/BaseController.php';

class CartDetailController extends BaseController {
    public function removeFromCart($user_id, $product_id) {
        // Vérifier si le produit existe dans le panier
        if (isset($_SESSION['cart'][$product_id])) {
            // Supprimer le produit du panier
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function getCartDetails($user_id) {
        // Récupérer les détails du panier de l'utilisateur
        $stmt = $this->db->prepare("
            SELECT p.id_produit, p.nom, p.prix, ci.quantity 
            FROM cart_items ci 
            JOIN produits p ON ci.product_id = p.id_produit 
            JOIN carts c ON ci.cart_id = c.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
?>
