<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boutique_en_ligne";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
        
        $stmt = $conn->prepare("SELECT id_produit, nom FROM produits WHERE nom LIKE :query");
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($results); // Returning full product info now
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No search query provided']);
        exit;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
