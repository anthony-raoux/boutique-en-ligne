<?php
session_start();
require_once __DIR__ . '/../controllers/OrderConfirmationController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$controller = new OrderConfirmationController();
$orderDetails = $controller->getOrderDetails($_GET['order_id']);
$order = $orderDetails['order'];
$items = $orderDetails['items'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
<h1>Order Confirmation</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li><?php echo $item['quantity'] . ' x ' . $item['name'] . ' - $' . $item['price']; ?></li>
    <?php endforeach; ?>
</ul>
<a href="simulate_payment.php?order_id=<?php echo $order['id']; ?>">Simulate Payment</a>
</body>
</html>
