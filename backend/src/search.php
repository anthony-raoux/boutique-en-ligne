<?php
// Simuler une recherche par mots-clés
if (isset($_GET['q'])) {
    $query = $_GET['q'];

    // Ici, vous pourriez exécuter une requête SQL pour obtenir les résultats de la recherche réels
    // Par exemple, recherchez dans une base de données ou un tableau de produits
    $suggestions = [
        'iPhone',
        'Samsung Galaxy',
        'iPad',
        'MacBook Pro',
        'HP Laptop',
        'Canon Camera',
        'Sony Headphones',
        'Dell Monitor'
    ];

    // Filtrer les suggestions qui correspondent au texte de recherche
    $filteredSuggestions = array_filter($suggestions, function($item) use ($query) {
        return stripos($item, $query) !== false;
    });

    // Retourner les suggestions filtrées en tant que réponse JSON
    header('Content-Type: application/json');
    echo json_encode($filteredSuggestions);
    exit;
}

// Si aucune valeur 'q' n'est fournie, retourner une réponse vide
http_response_code(400);
echo json_encode(['error' => 'No search query provided']);
exit;
?>
