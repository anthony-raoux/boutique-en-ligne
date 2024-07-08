<?php
session_start();
require_once __DIR__ . '/controllers/OrderConfirmationController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les détails de la commande à partir de l'URL (via $_GET)
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "ID de commande manquant.";
    exit;
}

$controller = new OrderConfirmationController();
$orderDetails = $controller->getOrderDetails($order_id);

if (!$orderDetails) {
    echo "Commande non trouvée.";
    exit;
}

$order = $orderDetails['order'];
$items = $orderDetails['items'];

// Débogage : Afficher le contenu de $items pour vérifier les clés
// var_dump($items);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        /* styles.css */

body {
    background-color: #fff; /* White background */
    color: #fff; /* White text */
    font-family: Arial, sans-serif; /* Example font family */
    margin: 0;
    padding: 0;
}

h1, h2, h3 {
    color: #fff; /* White text */
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin-bottom: 10px;
}

a {
    color: #fff; /* White link color */
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <h1>Order Confirmation</h1>
    <h2>Order ID: <?php echo $order['id']; ?></h2>
    <ul>
        <?php foreach ($items as $item): ?>
            <li><?php echo $item['quantity'] . ' x ' . $item['nom'] . ' - $' . $item['prix']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="simulate_payment.php?order_id=<?php echo $order['id']; ?>">Simulate Payment</a>
</body>
</html>