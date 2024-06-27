<?php
session_start();
require_once __DIR__ . '/../controllers/SimulatePaymentController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$controller = new SimulatePaymentController();
$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->completePayment($order_id);
    header('Location: payment_success.php');
    exit();
}

$order = $controller->getOrder($order_id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simulate Payment</title>
</head>
<body>
<h1>Simulate Payment for Order #<?php echo $order['id']; ?></h1>
<form method="post">
    <button type="submit">Complete Payment</button>
</form>
</body>
</html>
