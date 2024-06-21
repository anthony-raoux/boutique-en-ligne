<?php
// Afficher les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/AuthController.php';

$authController = new AuthController();

// Route pour l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->register($data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['adresse'], $data['telephone']);
    echo json_encode($result);
    exit;
}

// Route pour la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->login($data['email'], $data['mot_de_passe']);
    echo json_encode($result);
    exit;
}

// Gestion des erreurs pour les routes non trouvées
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['error' => 'Route not found']);
exit;
?>
