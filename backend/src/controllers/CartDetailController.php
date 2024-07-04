<?php
require_once __DIR__ . '/BaseController.php';

class CartDetailController extends BaseController {
    public function removeFromCart($user_id, $product_id) {
        // Supprimer le produit du panier dans la base de données
        $stmt = $this->db->prepare("
            DELETE ci 
            FROM cart_items ci 
            JOIN carts c ON ci.cart_id = c.id 
            WHERE ci.product_id = ? AND c.user_id = ?
        ");
        $stmt->execute([$product_id, $user_id]);

        // Vérifier si le produit existe dans le panier en session et le supprimer
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function getCartDetails($user_id) {
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

    public function getCartTotal($user_id) {
        $stmt = $this->db->prepare("
            SELECT SUM(p.prix * ci.quantity) AS total 
            FROM cart_items ci 
            JOIN produits p ON ci.product_id = p.id_produit 
            JOIN carts c ON ci.cart_id = c.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }
}
?>