<?php
// backend/src/register.php

// Include du fichier AuthController.php
require_once './controllers/AuthController.php';
$authController = new AuthController();

// Initialisation des variables pour les entrées du formulaire et les messages d'erreur
$nom = $prenom = $email = $mot_de_passe = $adresse = $telephone = '';
$nomError = $prenomError = $emailError = $mot_de_passeError = $adresseError = $telephoneError = '';
$registerError = '';

// Traitement de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitization et validation des inputs
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));

    // Validation des inputs
    if (empty($nom)) {
        $nomError = 'Please enter your last name.';
    }

    if (empty($prenom)) {
        $prenomError = 'Please enter your first name.';
    }

    if (empty($email)) {
        $emailError = 'Please enter your email.';
    }

    if (empty($mot_de_passe)) {
        $mot_de_passeError = 'Please enter your password.';
    }

    if (empty($adresse)) {
        $adresseError = 'Please enter your address.';
    }

    // Si aucune erreur d'entrée, tenter l'inscription
    if (empty($nomError) && empty($prenomError) && empty($emailError) && empty($mot_de_passeError) && empty($adresseError)) {
        $registerResult = $authController->register($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone);

        if ($registerResult['success']) {
            // Inscription réussie, rediriger vers la page de connexion
            $_SESSION['register_success'] = true; // Définit une variable de session pour le succès
            header("Location: login.php");
            exit();
        } else {
            // Inscription échouée, afficher le message d'erreur
            $registerError = $registerResult['error'];
        }  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body class="bg-gray-100">
    <?php
    session_start(); // Démarrer ou reprendre la session

    // Rediriger vers profile.php si l'utilisateur est déjà connecté
    if (isset($_SESSION['user_id'])) {
        header("Location: profile.php");
        exit();
    }
    
    // Définir la page active pour la classe active de la barre de navigation
    $currentPage = 'register';
    // Inclure la barre de navigation depuis backend/src/
    include 'navbar.php';
    ?>

    <div class="max-w-md mx-auto mt-10 bg-white p-8 border border-gray-300 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Inscription</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700">Last Name:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $nomError; ?></span>
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-gray-700">First Name:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $prenomError; ?></span>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $emailError; ?></span>
            </div>
            
            <div class="mb-4">
                <label for="mot_de_passe" class="block text-gray-700">Password:</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $mot_de_passeError; ?></span>
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-gray-700">Address:</label>
                <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $adresseError; ?></span>
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700">Phone Number:</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span class="text-red-500 text-sm"><?php echo $telephoneError; ?></span>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Register</button>
            </div>
            <span class="text-red-500 text-sm mt-4 block"><?php echo $registerError; ?></span>
        </form>
    </div>

    <script src="../frontend/assets/scripts/register.js"></script>
</body>
</html>
