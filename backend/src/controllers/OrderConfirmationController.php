<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../config/Database.php';

class OrderConfirmationController extends BaseController {
    public function getOrderDetails($order_id) {
        $db = new Database();
        $conn = $db->connect();

        $sql_order = "SELECT * FROM orders WHERE id = ?";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->execute([$order_id]);
        $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null;
        }

        $sql_items = "SELECT oi.quantity, p.nom, p.prix FROM order_items oi
                      INNER JOIN produits p ON oi.product_id = p.id_produit
                      WHERE oi.order_id = ?";
        $stmt_items = $conn->prepare($sql_items);
        $stmt_items->execute([$order_id]);
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        return ['order' => $order, 'items' => $items];
    }
}
?>

