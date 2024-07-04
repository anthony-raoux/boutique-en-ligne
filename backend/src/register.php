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
        $nomError = 'Veuillez entrer votre nom de famille.';
    }

    if (empty($prenom)) {
        $prenomError = 'Veuillez entrer votre prénom.';
    }

    if (empty($email)) {
        $emailError = 'Veuillez entrer votre email.';
    }

    if (empty($mot_de_passe)) {
        $mot_de_passeError = 'Veuillez entrer votre mot de passe.';
    }

    if (empty($adresse)) {
        $adresseError = 'Veuillez entrer votre addresse.';
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
    <body class="bg-stone-800 flex items-center justify-center min-h-screen">
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
            include 'head.php';
        ?>

<div class="p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-8 text-white text-center">App</h1>
        
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h1 class="text-2xl font-bold mb-8 text-white text-center">Inscrivez-vous</h1>
        
                <div class="flex gap-5">
                    <!-- Nom de famille Input -->
                    <div class="relative z-0 w-full mb-8 group w-1/2">
                        <input type="text" name="nom" id="nom" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($nom); ?>" />
                        <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-white-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom de famille</label>
                        <span class="text-red-500 text-xs mt-1"><?php echo $nomError; ?></span>
                    </div>

                    <!-- Prénom Input -->
                    <div class="relative z-0 w-full mb-8 group w-1/2">
                        <input type="text" name="prenom" id="prenom" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($prenom); ?>" />
                        <label for="prenom" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Prénom</label>
                        <span class="text-red-500 text-xs mt-1"><?php echo $prenomError; ?></span>
                    </div>
                </div>
                
                <!-- EMail Input -->
                <div class="relative z-0 w-full mb-8 group">
                    <input type="email" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($email); ?>" />
                    <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                    <span class="text-red-500 text-xs mt-1"><?php echo $emailError; ?></span>
                </div>

                <!-- Mot de passe Input -->
                <div class="relative z-0 w-full mb-8 group">
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " />
                    <label for="mot_de_passe" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        Mot de passe
                    </label>
                    <span class="text-red-500 text-xs mt-1"><?php echo $mot_de_passeError; ?></span>
                </div>

                <div class="flex gap-5">
                    <!-- Adresse Input -->
                    <div class="relative z-0 w-full mb-8 group w-1/2">
                        <input type="text" name="adresse" id="adresse" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($adresse); ?>" />
                        <label for="adresse" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Addresse</label>
                        <span class="text-red-500 text-xs mt-1"><?php echo $adresseError; ?></span>
                    </div>

                    <!-- Numéros de téléphone Input -->
                    <div class="relative z-0 w-full mb-10 group w-1/2">
                        <input type="tel" name="telephone" id="telephone" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($telephone); ?>" />
                        <label for="telephone" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Numéros de tléphone</label>
                        <span class="text-red-500 text-xs mt-1"><?php echo $telephoneError; ?></span>
                    </div>
                </div>
                
                <!-- Bouton de validation -->
                <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none ">S'inscrire</button>
                <span class="text-red-500 text-xs mt-2 block"><?php echo $registerError; ?></span>
            </form>

                <p class="text-start text-gray-400 text-sm"><a class="hover:underline underline-offset-8" href="login.php">Vous avez oublié votre mot de passe ?<span class="text-gray-200"></span>.</a></p>

                <!-- Lien vers la page de connexion -->
                <p class="text-center text-gray-400">Vous aves déja un compte ? <br> <a class="hover:underline underline-offset-8" href="login.php">Connectez-vous <span class="text-gray-200">ici</span>.</a></p>
        </div>
 
