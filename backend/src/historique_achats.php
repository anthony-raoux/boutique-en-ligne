<?php
require_once './controllers/HistoriqueAchatsController.php';

// Vérifiez si la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Instanciation du contrôleur d'historique des achats
$controller = new HistoriqueAchatsController();

// Récupérez l'historique des achats de l'utilisateur
$user_id = $_SESSION['user_id'];
$orders = $controller->getOrderHistory($user_id);
require_once 'navbar.php';
?>

<section class="py-8 antialiased md:py-16">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Historique des Achats</h2>

        <?php if (empty($orders)) : ?>
            <div class="flex flex-col items-center justify-center mt-6">
                <img src="../../images/historique_achat.png" alt="Historique vide" class="mb-4">
                <p class="text-gray-500 dark:text-gray-400">Aucun achat trouvé.</p>
            </div>
        <?php else : ?>
            <ul class="mt-6 divide-y divide-gray-200 overflow-hidden rounded-lg bg-black border border-white-500">
                <?php foreach ($orders as $order) : ?>
                    <li class="space-y-4 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Order #<?php echo $order['id']; ?></h3>
                            <span class="text-sm font-medium text-gray-500 dark:text-green-400">Status: <?php echo $order['payment_status']; ?></span>
                        </div>
                        <ul class="space-y-2">
                            <?php foreach ($order['items'] as $item) : ?>
                                <li class="flex items-center justify-between gap-4">
                                    <span class="min-w-0 flex-1 font-medium text-gray-900 dark:text-white"><?php echo $item['quantity'] . ' x ' . $item['name']; ?></span>
                                    <span class="text-xl font-bold leading-tight text-gray-900 dark:text-white">$<?php echo $item['price']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
