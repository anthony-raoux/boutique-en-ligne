<?php
require_once 'Database.php';

class User {
    public $id_utilisateur;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $adresse;
    public $telephone;

    public function save() {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $this->nom, $this->prenom, $this->email, $this->mot_de_passe, $this->adresse, $this->telephone);

        return $stmt->execute();
    }

    public static function findByEmail($email) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM Utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_object('User');
        } else {
            return null;
        }
    }
}
?>
