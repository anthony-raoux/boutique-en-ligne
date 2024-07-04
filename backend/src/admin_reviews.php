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
    <link rel="stylesheet" href="../frontend/css/styles.css">
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
