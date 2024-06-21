<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../controllers/AuthController.php';

$authController = new AuthController();

// Route pour l'inscription
// Exemple de gestion des erreurs dans votre script PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validation et traitement des données
        $result = $authController->register($data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['adresse'], $data['telephone']);

        // Réussite : renvoyer une réponse JSON avec les données enregistrées
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Utilisateur enregistré avec succès']);

    } catch (Exception $e) {
        // Erreur : renvoyer une réponse JSON avec l'erreur
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}


// Route pour la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->login($data['email'], $data['mot_de_passe']);
    echo json_encode($result);
}

// Route pour la gestion du profil utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'profile') {
    $id_utilisateur = $_GET['id'];
    $result = $authController->getProfile($id_utilisateur);
    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'updateProfile') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->updateProfile($data['id_utilisateur'], $data['nom'], $data['prenom'], $data['email'], $data['adresse'], $data['telephone']);
    echo json_encode($result);
}
?>
