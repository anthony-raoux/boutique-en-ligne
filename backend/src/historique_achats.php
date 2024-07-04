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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Achats</title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* Fond blanc */
            color: #3fff;/* Texte noir par défaut */
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #fff; /* Titre en noir */
        }

        p {
            color: #333; /* Texte des paragraphes en noir */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 20px;
        }

        ul li ul {
            margin-top: 10px;
        }

        a {
            color: #007bff; /* Couleur des liens */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <h1>Historique des Achats</h1>
    
    <?php if (empty($orders)) : ?>
        <p>Aucun achat trouvé.</p>
    <?php else : ?>
        <ul>
            <?php foreach ($orders as $order) : ?>
                <li>
                    Order #<?php echo $order['id']; ?> - Status: <?php echo $order['payment_status']; ?>
                    <ul>
                        <?php foreach ($order['items'] as $item) : ?>
                            <li>
                                <?php echo $item['quantity'] . ' x ' . $item['name'] . ' - $' . $item['price']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="index.php">Retourner à la boutique</a>
</body>
</html>
