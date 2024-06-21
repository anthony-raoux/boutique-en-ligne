<?php
require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController {

    public function register($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
        try {
            $query = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone) VALUES (:nom, :prenom, :email, :mot_de_passe, :adresse, :telephone)";
            $stmt = $this->db->prepare($query);

            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $hashed_password);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':telephone', $telephone);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'User registered successfully'];
            } else {
                return ['success' => false, 'error' => 'User registration failed: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
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
            if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                return ['success' => true, 'user_id' => $user['id_utilisateur']];
            } else {
                return ['success' => false, 'error' => 'Invalid email or password'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getProfile($id_utilisateur) {
        try {
            $query = "SELECT * FROM Utilisateur WHERE id_utilisateur = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_utilisateur);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateProfile($id_utilisateur, $nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
        try {
            // Check if email already exists
            $query = "SELECT * FROM Utilisateur WHERE email = :email AND id_utilisateur != :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id_utilisateur);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'error' => 'Email already in use by another account'];
            }

            $query = "UPDATE Utilisateur SET nom = :nom, prenom = :prenom, email = :email, adresse = :adresse, telephone = :telephone WHERE id_utilisateur = :id";

            if ($mot_de_passe) {
                $query = "UPDATE Utilisateur SET nom = :nom, prenom = :prenom, email = :email, mot_de_passe = :mot_de_passe, adresse = :adresse, telephone = :telephone WHERE id_utilisateur = :id";
                $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id', $id_utilisateur);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            if ($mot_de_passe) {
                $stmt->bindParam(':mot_de_passe', $hashed_password);
            }
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':telephone', $telephone);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Profile updated successfully'];
            } else {
                return ['success' => false, 'error' => 'Profile update failed: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }


    public function getUserById($id_utilisateur) {
        try {
            $query = "SELECT * FROM Utilisateur WHERE id_utilisateur = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_utilisateur);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
