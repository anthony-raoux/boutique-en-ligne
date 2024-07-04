<?php
require_once './models/Administrator.php';

class AdminController {
    private $adminModel;
    private $db;

    public function __construct($db) {
        $this->adminModel = new Administrator();
        $this->db = $db;
    }

    public function createAdmin($nom, $prenom, $email, $mot_de_passe) {
        return $this->adminModel->createAdmin($nom, $prenom, $email, $mot_de_passe);
    }

    public function getAdminById($id_admin) {
        return $this->adminModel->getAdminById($id_admin);
    }

    public function getAllReviews() {
        try {
            $query = "SELECT * FROM avis";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function approveReview($id_avis) {
        try {
            $query = "UPDATE avis SET is_approved = TRUE WHERE id_avis = :id_avis";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_avis', $id_avis);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteReview($id_avis) {
        try {
            $query = "DELETE FROM avis WHERE id_avis = :id_avis";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_avis', $id_avis);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>