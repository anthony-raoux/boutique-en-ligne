<!-- Créer, authentifie, obtient et maj le profil d'un utilisateur -->

<?php
    require_once '../config/Database.php';

    class User {
        private $conn;

        public function __construct() {
            $database = new Database();
            $this->conn = $database->getConnection();
        }

        public function createUser($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
            $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
            $query = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssss", $nom, $prenom, $email, $hashed_password, $adresse, $telephone);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'User registered successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'User registration failed.'];
            }
        }

        public function authenticateUser($email, $mot_de_passe) {
            $query = "SELECT * FROM Utilisateur WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                return ['status' => 'success', 'message' => 'User authenticated successfully.', 'user' => $user];
            } else {
                return ['status' => 'error', 'message' => 'Authentication failed.'];
            }
        }

        public function getUserProfile($id_utilisateur) {
            $query = "SELECT * FROM Utilisateur WHERE id_utilisateur = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_utilisateur);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function updateUserProfile($id_utilisateur, $nom, $prenom, $email, $adresse, $telephone) {
            $query = "UPDATE Utilisateur SET nom = ?, prenom = ?, email = ?, adresse = ?, telephone = ? WHERE id_utilisateur = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssi", $nom, $prenom, $email, $adresse, $telephone, $id_utilisateur);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'User profile updated successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'User profile update failed.'];
            }
        }
    }
?>
