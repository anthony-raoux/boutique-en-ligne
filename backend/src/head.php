<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session si elle n'est pas déjà démarrée
}

// Vérifier si l'utilisateur est connecté en tant qu'utilisateur normal ou administrateur
$loggedIn = isset($_SESSION['user_id']);
$adminLoggedIn = isset($_SESSION['admin_id']);

// Déterminer la page actuelle
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="../../frontend/src/assets/scripts/navbar.js"></script>
    <script src="../../frontend/src/assets/scripts/solde_banner.js"></script>

    <link rel="shortcut icon" href="../../images/favicon-logo" type="image/x-icon">
</head>




