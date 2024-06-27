<?php
require_once __DIR__ . '/BaseController.php';

class HistoriqueAchatsController extends BaseController {
    public function getOrderHistory($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $stmt = $this->db->prepare("SELECT p.name, p.price, oi.quantity FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }

        return $orders;
    }
}
?>
