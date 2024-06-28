<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boutique_en_ligne";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie si un terme de recherche est passé en paramètre
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
        
        // Requête SQL pour rechercher des produits basés sur le terme de recherche
        $stmt = $conn->prepare("SELECT nom FROM produits WHERE nom LIKE :query");
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupération des noms de produits trouvés
        $suggestions = array_column($results, 'nom');

        // Retourner les suggestions filtrées en tant que réponse JSON
        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit;
    } else {
        // Si aucun terme de recherche n'est fourni, retourner une réponse d'erreur
        http_response_code(400);
        echo json_encode(['error' => 'No search query provided']);
        exit;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
