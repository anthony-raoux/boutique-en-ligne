<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/AdminController.php';

$database = new Database();
$db = $database->connect();
$adminController = new AdminController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $adminController->approveReview($_POST['id_avis']);
    } elseif (isset($_POST['delete'])) {
        $adminController->deleteReview($_POST['id_avis']);
    }
}

$reviews = $adminController->getAllReviews();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modération des avis</title>
    <style>
        /* styles.css */

/* Reset des marges et paddings par défaut */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Style général du corps de la page */
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    padding: 20px;
}

/* Style du conteneur principal */
.content {
    max-width: 1200px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Style du titre principal */
h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

/* Style du tableau des avis */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

/* Style des boutons dans le tableau */
table button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
}

table button:hover {
    background-color: #0056b3;
}

/* Style des actions dans la dernière colonne du tableau */
table td form {
    display: inline;
    margin-right: 10px;
}

/* Responsive pour les petits écrans */
@media (max-width: 768px) {
    table th,
    table td {
        padding: 8px;
    }

    table button {
        padding: 6px 10px;
    }
}

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Modération des avis</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Commentaire</th>
                    <th>Note</th>
                    <th>Utilisateur</th>
                    <th>Produit</th>
                    <th>Approuvé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['id_avis']); ?></td>
                        <td><?php echo htmlspecialchars($review['commentaire']); ?></td>
                        <td><?php echo htmlspecialchars($review['note']); ?></td>
                        <td><?php echo htmlspecialchars($review['prenom_utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($review['id_produit']); ?></td>
                        <td><?php echo $review['is_approved'] ? 'Oui' : 'Non'; ?></td>
                        <td>
                            <?php if (!$review['is_approved']): ?>
                                <form action="admin_reviews.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_avis" value="<?php echo $review['id_avis']; ?>">
                                    <button type="submit" name="approve">Approuver</button>
                                </form>
                            <?php endif; ?>
                            <form action="admin_reviews.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_avis" value="<?php echo $review['id_avis']; ?>">
                                <button type="submit" name="delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../frontend/js/admin_reviews.js"></script>
</body>
</html>
