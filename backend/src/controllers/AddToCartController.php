<?php
require_once __DIR__ . '/BaseController.php';

class AddToCartController extends BaseController {
    public function addToCart($product_id, $user_id) {
        $cart = $this->db->prepare("SELECT * FROM carts WHERE user_id = ?");
        $cart->execute([$user_id]);
        $cart = $cart->fetch();

        if (!$cart) {
            $stmt = $this->db->prepare("INSERT INTO carts (user_id) VALUES (?)");
            $stmt->execute([$user_id]);
            $cart_id = $this->db->lastInsertId();
        } else {
            $cart_id = $cart['id'];
        }

        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, $product_id]);
        $cart_item = $stmt->fetch();

        if ($cart_item) {
            $stmt = $this->db->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?");
            $stmt->execute([$cart_item['id']]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->execute([$cart_id, $product_id]);
        }

        header('Location: ../src/cart_detail.php');
    }
}
?>
