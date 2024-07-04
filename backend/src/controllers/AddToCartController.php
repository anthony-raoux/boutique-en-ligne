<?php
require_once __DIR__ . '/BaseController.php';

class AddToCartController extends BaseController {
    public function addToCart($product_id, $quantity, $user_id = null) {
        $db = $this->db; // Assurez-vous que $this->db est correctement initialisé dans BaseController

        // Vérifier si l'utilisateur est connecté pour utiliser $user_id
        // Vous pouvez gérer la connexion utilisateur dans detail.php et récupérer $_SESSION['user_id']

        // Logique d'ajout au panier
        // Exemple de logique d'ajout au panier à partir de votre exemple

        $cart = $db->prepare("SELECT * FROM carts WHERE user_id = ?");
        $cart->execute([$user_id]);
        $cart = $cart->fetch();

        if (!$cart) {
            $stmt = $db->prepare("INSERT INTO carts (user_id) VALUES (?)");
            $stmt->execute([$user_id]);
            $cart_id = $db->lastInsertId();
        } else {
            $cart_id = $cart['id'];
        }

        $stmt = $db->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, $product_id]);
        $cart_item = $stmt->fetch();

        if ($cart_item) {
            $stmt = $db->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$quantity, $cart_item['id']]);
        } else {
            $stmt = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$cart_id, $product_id, $quantity]);
        }

        // Redirection vers la page de détail du panier ou une autre page appropriée après l'ajout au panier
        header('Location: ../src/cart_detail.php');
        exit;
    }
}
?>