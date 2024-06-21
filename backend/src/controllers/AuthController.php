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
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'User registered successfully']);
            } else {
                // Échec de l'inscription
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'User registration failed']);
            }
        } catch (PDOException $e) {
            // Gestion des exceptions PDO (comme les doublons d'email)
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    
    

    public function login($email, $mot_de_passe) {
        $query = "SELECT * FROM Utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return ['status' => 'success', 'message' => 'Login successful'];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password'];
        }
    }
}
?>
