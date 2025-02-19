<?php
require_once __DIR__ . '/BaseController.php';

class ProductController extends BaseController {
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->conn = $this->db;
    }

    // Méthode pour récupérer tous les produits ou filtrer par catégorie
    public function getAllProducts($category = '') {
        try {
            if ($category) {
                $query = "SELECT p.*, c.nom AS nom_categorie 
                          FROM produits p
                          LEFT JOIN category c ON p.id_categorie = c.id_categorie
                          WHERE p.id_categorie = :category";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':category', $category, PDO::PARAM_INT);
            } else {
                $query = "SELECT p.*, c.nom AS nom_categorie 
                          FROM produits p
                          LEFT JOIN category c ON p.id_categorie = c.id_categorie";
                $stmt = $this->conn->prepare($query);
            }
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$products) {
                $products = [];
            }

            return ['success' => true, 'products' => $products];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getCategories() {
        try {
            $sql = "SELECT * FROM category";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return ['success' => true, 'categories' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function addCategory($nom, $id_parent_categorie = null) {
        try {
            $query = "INSERT INTO category (nom, id_parent_categorie) VALUES (:nom, :id_parent_categorie)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':id_parent_categorie', $id_parent_categorie, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Catégorie ajoutée avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de l\'ajout de la catégorie: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteCategory($id_categorie) {
        try {
            $query = "DELETE FROM category WHERE id_categorie = :id_categorie";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_categorie', $id_categorie);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Catégorie supprimée avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de la suppression de la catégorie: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateCategory($id_categorie, $nom, $id_parent_categorie = null) {
        try {
            $query = "UPDATE category SET nom = :nom, id_parent_categorie = :id_parent_categorie WHERE id_categorie = :id_categorie";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':id_parent_categorie', $id_parent_categorie, PDO::PARAM_INT);
            $stmt->bindParam(':id_categorie', $id_categorie);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Catégorie mise à jour avec succès'];
            } else {
                return ['success' => false, 'error' => 'Échec de la mise à jour de la catégorie: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getProductById($productId) {
        try {
            $query = "SELECT p.*, c.nom AS nom_categorie FROM produits p LEFT JOIN category c ON p.id_categorie = c.id_categorie WHERE p.id_produit = :id_produit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_produit', $productId, PDO::PARAM_INT);
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

    public function searchProducts($query) {
        $stmt = $this->conn->prepare("SELECT id_produit, nom FROM produits WHERE nom LIKE ?");
        $stmt->execute(["%$query%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }
    

    public function addProduct($name, $description, $price, $imageTmpName, $stock, $categoryId) {
        try {
            $query = "INSERT INTO produits (nom, description, prix, image, stock, id_categorie) VALUES (:nom, :description, :prix, :image, :stock, :id_categorie)";
            $stmt = $this->conn->prepare($query);

            // Read image file as binary data
            $imageData = file_get_contents($imageTmpName);

            $stmt->bindParam(':nom', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':prix', $price);
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
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
            $stmt = $this->conn->prepare($query);
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

    public function updateProduct($productId, $nom, $description, $prix, $imageTmpName, $stock, $id_categorie) {
        try {
            $query = "UPDATE produits SET nom = :nom, description = :description, prix = :prix, stock = :stock, id_categorie = :id_categorie";
            if ($imageTmpName !== null) {
                $query .= ", image = :image";
            }
            $query .= " WHERE id_produit = :id_produit";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':id_categorie', $id_categorie);
            $stmt->bindParam(':id_produit', $productId);

            // Bind image if provided
            if ($imageTmpName !== null) {
                $imageData = file_get_contents($imageTmpName);
                $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
            }

            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Échec de la mise à jour du produit: ' . implode(", ", $stmt->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>