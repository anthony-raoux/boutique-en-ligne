<?php
session_start();
require_once 'config/Database.php';
require_once 'controllers/ProductController.php';

$productController = new ProductController();
$database = new Database();
$db = $database->connect();

$data = $_POST; // Utilisation de $_POST pour récupérer les données du formulaire
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'error' => 'ID de produit manquant']);
    exit;
}

// Vérifier si le produit existe dans la base de données
$productResult = $productController->getProductById($product_id);
$product = $productResult['product'] ?? null;

if (!$product) {
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

// Récupérer l'ID de l'utilisateur (à adapter selon votre gestion d'authentification)
$user_id = 1;

// Calculer le prix total des produits dans le panier
$totalPrice = 0;
foreach ($_SESSION['cart'] as $prod_id => $quantity) {
    // Récupérer le prix du produit à partir de $product
    $totalPrice += $product['prix'] * $quantity; // Utiliser $product['prix'] au lieu de $product['product']['prix']
}

// Enregistrer le panier dans la base de données
try {
    $db->beginTransaction();

    $query = "INSERT INTO panier (user_id, product_id, quantity, total_price, created_at) 
              VALUES (:user_id, :product_id, :quantity, :total_price, NOW())
              ON DUPLICATE KEY UPDATE quantity = :quantity, total_price = :total_price";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $_SESSION['cart'][$product_id], PDO::PARAM_INT);
    $stmt->bindParam(':total_price', $totalPrice, PDO::PARAM_STR);

    $stmt->execute();

    $db->commit();

    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier avec succès']);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout au panier: ' . $e->getMessage()]);
}
?>
