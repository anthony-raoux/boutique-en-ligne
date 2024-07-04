<?php
session_start();

if (isset($_SESSION['user_id']) || isset($_SESSION['id'])) {
    // Rediriger vers la page appropriée si déjà connecté
    if (isset($_SESSION['user_id'])) {
        header("Location: profile.php");
    } elseif (isset($_SESSION['id'])) {
        // header("Location: dashboard.php");
    }
    exit();
}

require_once __DIR__ . '/controllers/AuthController.php';
$authController = new AuthController();

$email = $password = '';
$emailError = $passwordError = '';
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars($_POST['password']);

    if (empty($email)) {
        $emailError = 'Please enter your email.';
    }

    if (empty($password)) {
        $passwordError = 'Please enter your password.';
    }

    if (empty($emailError) && empty($passwordError)) {
        // Essayer de se connecter en tant qu'administrateur
        $loginResult = $authController->loginAdmin($email, $password);

        if ($loginResult['success']) {
            $_SESSION['admin_id'] = $loginResult['admin_id'];
            header("Location: dashboard.php");
            exit();
        }

        // Si échec, essayer de se connecter en tant qu'utilisateur normal
        $loginResult = $authController->loginUser($email, $password);

        if ($loginResult['success']) {
            $_SESSION['user_id'] = $loginResult['user_id'];
            header("Location: index.php");
            exit();
        }

        // Si les deux échouent, afficher un message d'erreur
        $loginError = $loginResult['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<main class="bg-stone-800 flex items-center justify-center min-h-screen">
    <?php
    $currentPage = 'login';
    include 'head.php';
    ?>

    <div class="p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-8 text-white text-center">App</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h1 class="text-2xl font-bold mb-8 text-white text-center">Connexion</h1>

            <div class="relative z-0 w-full mb-8 group">
                <input type="email" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " value="<?php echo htmlspecialchars($email); ?>" />
                <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                <span class="text-red-500 text-xs mt-1"><?php echo $emailError; ?></span>
            </div>

            <div class="relative z-0 w-full mb-8 group">
                <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" placeholder=" " />
                <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mot de passe</label>
                <span class="text-red-500 text-xs mt-1"><?php echo $passwordError; ?></span>
            </div>
            
            <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">Se connecter</button>
            <span class="text-red-500 text-xs mt-2 block"><?php echo $loginError; ?></span>
        </form>
        
        <p class="text-center text-gray-400">Vous n'avez pas de compte ? <br> <a class="hover:underline underline-offset-8" href="register.php">Inscrivez-vous <span class="text-gray-200">ici</span>.</a></p>
    </div>
</main>

<script src="../frontend/js/script.js"></script>
</html>
