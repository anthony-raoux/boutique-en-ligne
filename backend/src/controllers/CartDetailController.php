<?php
require_once __DIR__ . '/BaseController.php';

class CartDetailController extends BaseController {
    public function getCartDetails($user_id) {
        $stmt = $this->db->prepare("
            SELECT p.nom, p.prix, ci.quantity 
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
