<?php
session_start();
require_once __DIR__ . '/config/Database.php'; // Assurez-vous que le chemin vers config.php est correct

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <h1>Payment Successful!</h1>
    <p>Your payment was completed successfully.</p>
    <a href="index.php">Go back to shop</a>
</body>
</html>