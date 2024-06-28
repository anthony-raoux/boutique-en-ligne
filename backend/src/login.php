<?php
session_start();

if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    // Rediriger vers la page appropriée si déjà connecté
    if (isset($_SESSION['user_id'])) {
        header("Location: profile.php");
    } elseif (isset($_SESSION['admin_id'])) {
        header("Location: dashboard.php");
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
            $_SESSION['admin_prenom'] = $loginResult['prenom']; // Stocker le prénom de l'admin dans la session
            header("Location: dashboard.php");
            exit();
        }

        // Si échec, essayer de se connecter en tant qu'utilisateur normal
        $loginResult = $authController->loginUser($email, $password);

        if ($loginResult['success']) {
            $_SESSION['user_id'] = $loginResult['user_id'];
            $_SESSION['user_prenom'] = $loginResult['prenom']; // Stocker le prénom de l'utilisateur dans la session
            header("Location: profile.php");
            exit();
        }

        // Si les deux échouent, afficher un message d'erreur
        $loginError = $loginResult['error'];
    }

    $error_message = '';
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'not_logged_in') {
            $error_message = 'Vous devez être connecté pour ajouter des produits au panier.';
        }
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
<body>
    <?php
    $currentPage = 'login';
    include 'navbar.php';
    ?>

    <div class="content">
        <h1>Login</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $passwordError; ?></span>
            </div>
            
            <button type="submit">Login</button>
            <span class="error"><?php echo $loginError; ?></span>
        </form>
    </div>

    <script src="../frontend/js/login.js"></script>
</body>
</html>
