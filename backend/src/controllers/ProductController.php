<?php
require_once __DIR__ . '/BaseController.php';

class ProductController extends BaseController {

    public function addProduct($name, $description, $price, $image, $stock, $categoryId) {
        try {
            $query = "INSERT INTO produits (nom, description, prix, image, stock, id_categorie) 
                      VALUES (:nom, :description, :prix, :image, :stock, :id_categorie)";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':nom', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':prix', $price);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':id_categorie', $categoryId);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Produit ajouté avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de l\'ajout du produit: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteProduct($productId) {
        try {
            $query = "DELETE FROM produits WHERE id_produit = :id_produit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_produit', $productId);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Produit supprimé avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de la suppression du produit: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateProduct($productId, $name, $description, $price, $image, $stock, $categoryId) {
        try {
            $query = "UPDATE produits SET nom = :nom, description = :description, prix = :prix, image = :image, stock = :stock, id_categorie = :id_categorie WHERE id_produit = :id_produit";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':id_produit', $productId);
            $stmt->bindParam(':nom', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':prix', $price);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':id_categorie', $categoryId);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Produit mis à jour avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de la mise à jour du produit: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getAllProducts() {
        try {
            $query = "SELECT p.*, c.nom AS nom_categorie 
                      FROM produits p
                      LEFT JOIN category c ON p.id_categorie = c.id_categorie";
            $stmt = $this->db->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'products' => $products];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getProductById($productId) {
        try {
            $query = "SELECT p.*, c.nom AS nom_categorie 
                      FROM produits p
                      LEFT JOIN category c ON p.id_categorie = c.id_categorie
                      WHERE p.id_produit = :id_produit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_produit', $productId);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                return ['success' => true, 'product' => $product];
            } else {
                return ['success' => false, 'error' => 'Produit non trouvé'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>
