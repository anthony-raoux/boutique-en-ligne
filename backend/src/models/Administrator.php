<!-- 
    Créer un nouvel administrateur dans la base de données et
     récupérer un administrateur par son ID.
-->

<?php
    require_once 'Model.php';

    class Administrator extends Model {
        private $id_admin;
        private $nom;
        private $prenom;
        private $email;
        private $mot_de_passe;

        public function createAdmin($nom, $prenom, $email, $mot_de_passe) {
            $sql = 'INSERT INTO Administrateur (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)';
            $params = [
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':mot_de_passe' => password_hash($mot_de_passe, PASSWORD_DEFAULT)
            ];
            return $this->execute($sql, $params);
        }

        public function getAdminById($id_admin) {
            $sql = 'SELECT * FROM Administrateur WHERE id_admin = :id_admin';
            $params = [':id_admin' => $id_admin];
            return $this->fetch($sql, $params);
        }

        // Ajoutez d'autres méthodes pour gérer les administrateurs (mise à jour, suppression, etc.)
    }
?>
