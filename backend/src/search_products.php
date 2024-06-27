<?php
// Connexion à votre base de données et autres configurations nécessaires

// Vérifier si la requête GET contient une chaîne de recherche
if (isset($_GET['q'])) {
    $searchQuery = $_GET['q'];

    // Exécutez une requête SQL pour rechercher des produits basés sur $searchQuery
    $stmt = $pdo->prepare("SELECT nom FROM produits WHERE nom LIKE ?");
    $stmt->execute(["%$searchQuery%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les résultats en tant que JSON
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}
?>
