<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

require_once __DIR__ . '/controllers/BaseController.php';
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
        $loginResult = $authController->login($email, $password);

        if ($loginResult['success']) {
            $_SESSION['user_id'] = $loginResult['user_id'];
            header("Location: profile.php");
            exit();
        } else {
            $loginError = $loginResult['error'];
        }
    }
}

$registerSuccessMessage = '';
if (isset($_SESSION['register_success'])) {
    $registerSuccessMessage = 'Votre compte a bien été créé !';
    unset($_SESSION['register_success']);
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

        <?php if (!empty($registerSuccessMessage)) : ?>
            <div class="success-message"><?php echo $registerSuccessMessage; ?></div>
        <?php endif; ?>

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

    <script src="../frontend/js/script.js"></script>
</body>
</html>
