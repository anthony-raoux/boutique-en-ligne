<?php
require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Routes pour la gestion des produits
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Route pour récupérer tous les produits
    if ($_GET['action'] === 'products') {
        $result = $productController->getAllProducts();
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // Route pour récupérer un produit par son ID
    elseif ($_GET['action'] === 'product' && isset($_GET['id'])) {
        $productId = $_GET['id'];
        $result = $productController->getProductById($productId);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Route pour ajouter un nouveau produit
    if ($_GET['action'] === 'addProduct') {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $productController->addProduct($data['nom'], $data['description'], $data['prix'], $data['image'], $data['stock'], $data['id_categorie']);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Route pour mettre à jour un produit existant
    parse_str(file_get_contents('php://input'), $putData); // Récupérer les données du corps de la requête PUT
    if ($_GET['action'] === 'updateProduct' && isset($putData['id_produit'])) {
        $result = $productController->updateProduct($putData['id_produit'], $putData['nom'], $putData['description'], $putData['prix'], $putData['image'], $putData['stock'], $putData['id_categorie']);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Route pour supprimer un produit
    parse_str(file_get_contents('php://input'), $deleteData); // Récupérer les données du corps de la requête DELETE
    if ($_GET['action'] === 'deleteProduct' && isset($deleteData['id_produit'])) {
        $result = $productController->deleteProduct($deleteData['id_produit']);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?>
