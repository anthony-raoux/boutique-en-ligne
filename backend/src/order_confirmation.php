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
            <button id="simulatePaymentBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simuler le Paiement
            </button>
        </div>
    </div>
</body>


<!-- Modal -->
<div id="simulatePaymentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    
    <div class="modal-container bg-stone-900 text-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <!-- Modal content -->
        <div class="modal-content py-4 text-left px-6">
            <!-- Title -->
            <div class="modal-header pb-3 d-flex">
                <h3 class="text-lg font-bold">Simuler le Paiement</h3>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <p>Confirmation de paiement pour la commande #<?php echo $order['id']; ?>.</p>
                <form id="simulatePaymentForm" action="simulate_payment.php" method="post">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="flex flex-row mt-5 gap-5 justify-center">
                        <button type="submit" class="bg-white text-black px-4 py-2 rounded border hover:bg-slate-200 basis-1/2">
                            Confirmer le Paiement
                        </button>
                        <button id="closeModal" class="modal-close px-2 border border-white px-4 py-2 rounded basis-1/4">Annler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var simulatePaymentBtn = document.getElementById('simulatePaymentBtn');
        var simulatePaymentModal = document.getElementById('simulatePaymentModal');
        var closeModal = document.getElementById('closeModal');

        simulatePaymentBtn.addEventListener('click', function() {
            simulatePaymentModal.classList.remove('hidden');
        });

        closeModal.addEventListener('click', function() {
            simulatePaymentModal.classList.add('hidden');
        });
    });
</script>
