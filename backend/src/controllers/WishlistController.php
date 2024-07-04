<?php
require_once './config/Database.php';

class WishlistController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addToWishlist($id_utilisateur, $id_produit) {
        try {
            $query = "INSERT INTO wishlist (id_utilisateur, id_produit, date_ajout) VALUES (:id_utilisateur, :id_produit, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':id_produit', $id_produit);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeFromWishlist($id_utilisateur, $id_produit) {
        try {
            $query = "DELETE FROM wishlist WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':id_produit', $id_produit);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getWishlist($id_utilisateur) {
        try {
            $query = "SELECT p.* FROM produits p JOIN wishlist w ON p.id_produit = w.id_produit WHERE w.id_utilisateur = :id_utilisateur";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>