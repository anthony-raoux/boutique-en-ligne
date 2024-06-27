<?php
session_start(); // Assurez-vous que la session est démarrée

require_once './controllers/AuthController.php';
require_once './controllers/HistoriqueAchatsController.php'; // Inclure le contrôleur de l'historique des achats

$authController = new AuthController();
$historiqueController = new HistoriqueAchatsController();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérez les informations de l'utilisateur
$user = $authController->getProfile($user_id);
if (!$user) {
    echo "Error fetching user profile.";
    exit;
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];

    $result = $authController->updateProfile($user_id, $nom, $prenom, $email, $mot_de_passe, $adresse, $telephone);

    if ($result['success']) {
        echo "<div style='color: green;'>Profile updated successfully.</div>";
    } else {
        echo "<div style='color: red;'>Error: " . $result['error'] . "</div>";
    }
}

// Récupérez l'historique des achats de l'utilisateur
$orders = $historiqueController->getOrderHistory($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <h1>Profile</h1>
    <p>Welcome, <?php echo $user['prenom'] . ' ' . $user['nom']; ?></p>
    
    <!-- Formulaire de mise à jour du profil -->
    <form method="post">
        <input type="hidden" name="id_utilisateur" value="<?php echo $user['id_utilisateur']; ?>">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>
        </div>
        <div>
            <label for="prenom">Prenom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div>
            <label for="mot_de_passe">Nouveau mot de passe:</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe">
        </div>
        <div>
            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" value="<?php echo $user['adresse']; ?>" required>
        </div>
        <div>
            <label for="telephone">Telephone:</label>
            <input type="text" id="telephone" name="telephone" value="<?php echo $user['telephone']; ?>" required>
        </div>
        <button type="submit">Update Profile</button>
    </form>

    <!-- Affichage de l'historique des achats -->
    <h2>Historique des Achats</h2>
    <ul>
    <?php foreach ($orders as $order): ?>
    <li>Order #<?php echo $order['id']; ?> - Status: <?php echo $order['payment_status']; ?>
        <?php if (isset($order['items']) && !empty($order['items'])): ?>
            <ul>
                <?php foreach ($order['items'] as $item): ?>
                    <li>
                        <?php echo $item['quantity'] . ' x ' . $item['name'] . ' - $' . $item['price']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <ul>
                <li>No items found.</li>
            </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
                
    </ul>

    <a href="historique_achats.php">Voir l'historique complet des achats</a>
</body>
</html>
