<?php
require_once __DIR__ . '/BaseController.php';

class CheckoutController extends BaseController {
    public function checkout($user_id) {
        $cart = $this->db->prepare("SELECT * FROM carts WHERE user_id = ?");
        $cart->execute([$user_id]);
        $cart = $cart->fetch();

        if (!$cart) {
            echo "No items in cart.";
            exit();
        }

        $stmt = $this->db->prepare("INSERT INTO orders (user_id, payment_status) VALUES (?, 'Pending')");
        $stmt->execute([$user_id]);
        $order_id = $this->db->lastInsertId();

        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cart['id']]);
        $cart_items = $stmt->fetchAll();

        foreach ($cart_items as $item) {
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['quantity']]);
        }

        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cart['id']]);
        $stmt = $this->db->prepare("DELETE FROM carts WHERE id = ?");
        $stmt->execute([$cart['id']]);

        header('Location: ../src/order_confirmation.php?order_id=' . $order_id);
    }
}
?>
