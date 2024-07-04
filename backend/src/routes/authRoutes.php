<?php
require_once '../controllers/AuthController.php';

$authController = new AuthController();

// Route pour l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->register($data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['adresse'], $data['telephone']);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Route pour la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->login($data['email'], $data['mot_de_passe']);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Route pour la mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'updateProfile') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->updateProfile($data['id_utilisateur'], $data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['adresse'], $data['telephone']);
    header('Content-Type: application/json');
    echo json_encode($result);
}
?>
