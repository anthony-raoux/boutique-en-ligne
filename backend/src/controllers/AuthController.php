<?php
require_once '../models/User.php';

class AuthController {

    public function register($nom, $prenom, $email, $mot_de_passe, $adresse, $telephone) {
        $user = new User();
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->email = $email;
        $user->mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $user->adresse = $adresse;
        $user->telephone = $telephone;

        if ($user->save()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Échec de l\'inscription'];
        }
    }

    public function login($email, $mot_de_passe) {
        $user = User::findByEmail($email);

        if ($user && password_verify($mot_de_passe, $user->mot_de_passe)) {
            // Gérer la connexion (par exemple, générer un token ou une session)
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
        }
    }
}
?>

