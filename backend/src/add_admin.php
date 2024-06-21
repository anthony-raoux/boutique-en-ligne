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
    $nom = "admin1";
    $prenom = "test";
    $email = "admin01@hotmail.com";
    $mot_de_passe = "uwu"; // Le mot de passe en clair

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
?>
