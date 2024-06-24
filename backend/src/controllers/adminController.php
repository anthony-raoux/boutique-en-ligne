<?php
require_once '../models/Administrator.php';

class AdminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new Administrator();
    }

    public function createAdmin($nom, $prenom, $email, $mot_de_passe) {
        return $this->adminModel->createAdmin($nom, $prenom, $email, $mot_de_passe);
    }

    public function getAdminById($id_admin) {
        return $this->adminModel->getAdminById($id_admin);
    }
}
?>
