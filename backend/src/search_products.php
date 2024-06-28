<?php
// Connexion à la base de données et autres configurations nécessaires
require_once 'config/Database.php';

// Vérifier si le paramètre de recherche (q) est présent
if (isset($_GET['q'])) {
    $searchTerm = $_GET['q'];

    // Utiliser PDO pour interroger la base de données
    $database = new Database();
    $conn = $database->connect();

    // Exemple de requête SQL pour rechercher des produits par nom (ajustez selon votre schéma de base de données)
    $sql = "SELECT * FROM produits WHERE nom LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer les suggestions à renvoyer sous forme de JSON
    $suggestions = array_map(function($product) {
        return ['id' => $product['id_produit'], 'nom' => $product['nom']];
    }, $products);

    // Renvoyer les suggestions sous forme de JSON
    header('Content-Type: application/json');
    echo json_encode(['suggestions' => $suggestions]);
    exit;
}

// Si aucun terme de recherche n'est fourni, renvoyer une réponse vide
http_response_code(400); // Bad Request
exit;
?>
