<?php
require_once 'BaseController.php';

class AuthController extends BaseController {

    public function register($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
        try {
            $query = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone) VALUES (:nom, :prenom, :email, :mot_de_passe, :adresse, :telephone)";
            $stmt = $this->db->prepare($query);
    
            // Utilisation de bindParam pour lier les valeurs
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt->bindParam(':mot_de_passe', $hashed_password);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':telephone', $telephone);
    
            // Exécution de la requête
            if ($stmt->execute()) {
                // Succès de l'inscription
                return ['success' => true, 'message' => 'User registered successfully'];
            } else {
                // Échec de l'inscription
                return ['success' => false, 'error' => 'User registration failed'];
            }
        } catch (PDOException $e) {
            // Gestion des exceptions PDO (comme les doublons d'email)
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    

    public function login($email, $mot_de_passe) {
        try {
            $query = "SELECT * FROM Utilisateur WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                return ['success' => true, 'user_id' => $user['id']]; // Login successful
            } else {
                return ['success' => false, 'error' => 'Invalid email or password']; // Login failed
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()]; // Database error
        }
    }
}
?>
