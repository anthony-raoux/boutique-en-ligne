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

require_once 'head.php';
require_once 'navbar.php';

?>



<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-white">Confirmation de Commande</h1>
        <h2 class="text-xl font-semibold mb-6 text-white">Commande ID: <?php echo $order['id']; ?></h2>
        <ul class="bg-black border border-white rounded-lg p-6">
            <?php foreach ($items as $item): ?>
                <li class="flex justify-between items-center mb-4">
                    <div>
                        <span class="font-medium text-white"><?php echo $item['quantity']; ?> x</span>
                        <span class="font-bold text-white"><?php echo $item['nom']; ?></span>
                        <span class="text-gray-300">- $<?php echo $item['prix']; ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="text-right mt-6">
            <a href="simulate_payment.php?order_id=<?php echo $order['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simuler le Paiement
            </a>
        </div>
    </div>
</body>
