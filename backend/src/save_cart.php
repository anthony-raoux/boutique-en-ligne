<?php
session_start();
require_once 'config/Database.php'; // Assurez-vous d'avoir le bon chemin vers votre classe Database
require_once 'controllers/ProductController.php';

$productController = new ProductController();
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'error' => 'Produit non trouvé']);
    exit;
}

// Ajouter le produit au panier en session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 1;
} else {
    $_SESSION['cart'][$product_id]++;
}

// Récupérer l'ID de l'utilisateur (simulé ici, vous devez implémenter la gestion d'authentification appropriée)
$user_id = 1; // Exemple : ID de l'utilisateur connecté

// Calculer le prix total des produits dans le panier
$totalPrice = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $result = $productController->getProductById($product_id);
    if ($result['success']) {
        $product = $result['product'];
        $totalPrice += $product['prix'] * $quantity;
    }
}

// Enregistrer le panier dans la base de données
try {
    // Démarre une transaction
    $db->beginTransaction();

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $query = "INSERT INTO panier (user_id, product_id, quantity, total_price, created_at) 
                  VALUES (:user_id, :product_id, :quantity, :total_price, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':total_price', $totalPrice, PDO::PARAM_STR); // Utilisation de PARAM_STR car total_price est un montant

        $stmt->execute();
    }

    // Commit la transaction
    $db->commit();

    // Réinitialiser le panier en session après l'enregistrement
    $_SESSION['cart'] = [];

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Rollback en cas d'erreur
    $db->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
