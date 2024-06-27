<?php
session_start(); // Assurez-vous que la session est démarrée

require_once './controllers/HistoriqueAchatsController.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$controller = new HistoriqueAchatsController();
$orders = $controller->getOrderHistory($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historique des Achats</title>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <h1>Historique des Achats</h1>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>Order #<?php echo $order['order_id']; ?> - Status: <?php echo $order['payment_status']; ?>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li><?php echo $item['quantity'] . ' x ' . $item['nom'] . ' - $' . $item['prix']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Retourner à la boutique</a>
</body>
</html>
