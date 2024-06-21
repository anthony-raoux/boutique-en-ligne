<?php
require_once 'BaseController.php';

class AuthController extends BaseController {

    public function register($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
        $query = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone) VALUES (:nom, :prenom, :email, :mot_de_passe, :adresse, :telephone)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', password_hash($mot_de_passe, PASSWORD_DEFAULT));
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':telephone', $telephone);

        if($stmt->execute()) {
            return ['status' => 'success', 'message' => 'User registered successfully'];
        } else {
            return ['status' => 'error', 'message' => 'User registration failed'];
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
