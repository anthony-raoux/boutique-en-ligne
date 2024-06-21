<?php
// Start or resume session
session_start();

// Check if user is already logged in, redirect to profile.php if true
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

// Include necessary files
require_once './controllers/AuthController.php';
$authController = new AuthController();

// Initialize variables for form input and error messages
$email = $password = '';
$emailError = $passwordError = '';
$loginError = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars($_POST['password']);

    // Validate email
    if (empty($email)) {
        $emailError = 'Please enter your email.';
    }

    // Validate password
    if (empty($password)) {
        $passwordError = 'Please enter your password.';
    }

    // If no input errors, attempt login
    if (empty($emailError) && empty($passwordError)) {
        // Attempt login
        $loginResult = $authController->login($email, $password);
        var_dump($loginResult); // Debug output

        if ($loginResult['success']) {
            // Login successful, set session and redirect
            $_SESSION['user_id'] = $loginResult['user_id'];
            header("Location: profile.php");
            exit();
        } else {
            // Login failed, handle error message
            $loginError = $loginResult['error'];
        }
    }
}

// Check for register success message
$registerSuccessMessage = '';
if (isset($_SESSION['register_success'])) {
    $registerSuccessMessage = 'Votre compte a bien été créé !'; // Message de succès
    unset($_SESSION['register_success']); // Supprimer la variable de session après l'affichage
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
    // Set current page for navbar active class
    $currentPage = 'login';
    // Include the navbar from backend/src/
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
