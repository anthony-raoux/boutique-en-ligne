<?php
session_start();
require_once __DIR__ . '/./controllers/CartDetailController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../src/login.php');
    exit();
}

$controller = new CartDetailController();
$items = $controller->getCartDetails($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Detail</title>
</head>
<body>
<?php require_once 'navbar.php'; ?>
<h1>Your Cart</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li><?php echo $item['quantity'] . ' x ' . $item['nom'] . ' - $' . $item['prix']; ?></li>
    <?php endforeach; ?>
</ul>
<a href="checkout.php">Checkout</a>
</body>
</html>
