<?php
    // Informations de connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "boutique_en_ligne";

try {
    // Connexion à la base de données MySQL via PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Données de l'administrateur à insérer
    $nom = "yes";
    $prenom = "yes";
    $email = "yes@yes.com";
    $mot_de_passe = "yes"; // Le mot de passe en clair

    // Hashage du mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparation de la requête SQL d'insertion
    $query = "INSERT INTO administrateur (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $hashed_password);

    // Exécution de la requête
    $stmt->execute();

    echo "Admin ajouté avec succès.";
} catch(PDOException $e) {
    echo "Erreur lors de l'ajout de l'admin: " . $e->getMessage();
}

// Fermeture de la connexion
$conn = null;

include_once 'head.php';
?>



<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Inscription Admin</h2>
        <form action="add_admin.php" method="post">
            <div class="mb-4">
                <label for="nom" class="block text-gray-700">Nom</label>
                <input type="text" id="nom" name="nom" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <div class="mb-4">
                <label for="prenom" class="block text-gray-700">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <div class="mb-6">
                <label for="mot_de_passe" class="block text-gray-700">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700">Inscrire</button>
        </form>
    </div>
</body>


