<?php
require_once __DIR__ . '/BaseController.php';

class HistoriqueAchatsController extends BaseController {
    public function getOrderHistory($user_id) {
        $conn = $this->db; // Récupérer la connexion à la base de données depuis BaseController

        $sql = "SELECT oi.quantity, p.nom, p.prix, o.id, o.payment_status
                FROM order_items oi
                INNER JOIN produits p ON oi.product_id = p.id_produit
                INNER JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $orders = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($orders[$row['id']])) {
                $orders[$row['id']] = [
                    'id' => $row['id'],
                    'payment_status' => $row['payment_status'],
                    'items' => []
                ];
            }
            $orders[$row['id']]['items'][] = [
                'quantity' => $row['quantity'],
                'name' => $row['nom'], // Utilisation de 'nom' pour le nom du produit
                'price' => $row['prix']
            ];
        }
        return array_values($orders); // Retourne les commandes comme un tableau indexé
    }
}
?>