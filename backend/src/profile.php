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

<?php
        require_once 'head.php'; 
        require_once 'navbar.php'; 
?>

<main class="flex items-center justify-center min-h-screen">
    <div class="p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-8 text-white text-center">Profile</h1>
        <p class="text-white text-center mb-4">Welcome, <?php echo htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']); ?></p>
        
        <!-- Formulaire de mise à jour du profil -->
        <form method="post" class="space-y-4">
            <input type="hidden" name="id_utilisateur" value="<?php echo $user['id_utilisateur']; ?>">
            
            <div class="relative z-0 w-full mb-8 group">
                <label for="nom" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nom:</label>
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
            </div>

            <div class="relative z-0 w-full mb-8 group">
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
                <label for="prenom" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Prenom:</label>
            </div>
            
            <div class="relative z-0 w-full mb-8 group">
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
                <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email:</label>
            </div>
            
            <div class="relative z-0 w-full mb-8 group">
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="password" id="mot_de_passe" name="mot_de_passe" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
                <label for="mot_de_passe" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nouveau mot de passe:</label>
            </div>

            <div class="relative z-0 w-full mb-8 group">
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($user['adresse']); ?>" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
                <label for="adresse" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Adresse:</label>
            </div>

            <div class="relative z-0 w-full mb-6">
                <input class="block py-2.5 px-0 w-full text-sm text-stone-100 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-white peer px-1" type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>" class="block w-full py-2.5 px-4 text-sm text-stone-100 bg-transparent border-b-2 border-gray-300 focus:outline-none focus:border-white">
                <label for="telephone" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-100 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telephone:</label>
            </div>
            
            <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">Mettre à jour le profile</button>
        </form>
    </div>




   <!-- Affichage de l'historique des achats -->
   <!-- <div class="historique">
        <h2 class='text-white'>Historique des Achats</h2>
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
    </div> -->
