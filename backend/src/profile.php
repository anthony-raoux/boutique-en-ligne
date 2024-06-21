<?php
// Start or resume session
session_start();

// Redirect to login.php if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include necessary files
require_once './controllers/AuthController.php';
$authController = new AuthController();

// Fetch user details based on session user_id
$user = $authController->getUserById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php
    // Set current page for navbar active class
    $currentPage = 'profile';
    // Include the navbar from backend/src/
    include 'navbar.php';
    ?>

    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
        
        <div class="profile-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['adresse']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['telephone']); ?></p>
            <!-- Add more details as needed -->
        </div>
        
        <a href="logout.php">Logout</a>
    </div>

    <script src="../frontend/js/script.js"></script>
</body>
</html>
