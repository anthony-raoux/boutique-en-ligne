<?php
require_once __DIR__ . '/BaseController.php';

class SimulatePaymentController extends BaseController {
    public function completePayment($order_id) {
        $stmt = $this->db->prepare("UPDATE orders SET payment_status = 'Completed' WHERE id = ?");
        $stmt->execute([$order_id]);
    }

    public function getOrder($order_id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetch();
    }
}
?>
