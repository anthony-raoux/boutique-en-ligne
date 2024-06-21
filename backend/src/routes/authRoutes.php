<?php
require_once '../controllers/AuthController.php';

$authController = new AuthController();

// Route pour l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $authController->register($data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['adresse'], $data['telephone']);
    echo json_encode($result);
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
