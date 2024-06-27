<?php
session_start();
require_once __DIR__ . '/../controllers/HistoriqueAchatsController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$controller = new HistoriqueAchatsController();
$orders = $controller->getOrderHistory($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Historique des Achats</title>
</head>
<body>
<h1>Historique des Achats</h1>
<ul>
    <?php foreach ($orders as $order): ?>
        <li>Order #<?php echo $order['id']; ?> - Status: <?php echo $order['payment_status']; ?>
            <ul>
                <?php foreach ($order['items'] as $item): ?>
                    <li><?php echo $item['quantity'] . ' x ' . $item['name'] . ' - $' . $item['price']; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
<a href="index.php">Retourner à la boutique</a>
</body>
</html>
