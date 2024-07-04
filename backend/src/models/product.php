<!-- 
    Créer un nouveau produit dans la base de données
        +
    Récupérer un produit par son ID
-->

<?php
    require_once 'Model.php';

    class Product extends Model {
        private $id_produit;
        private $nom;
        private $description;
        private $prix;
        private $image;
        private $stock;
        private $id_categorie;
        private $id_tva;
        private $id_promotion;

        public function createProduct($nom, $description, $prix, $image, $stock, $id_categorie, $id_tva, $id_promotion) {
            $sql = 'INSERT INTO Produit (nom, description, prix, image, stock, id_categorie, id_tva, id_promotion) VALUES (:nom, :description, :prix, :image, :stock, :id_categorie, :id_tva, :id_promotion)';
            $params = [
                ':nom' => $nom,
                ':description' => $description,
                ':prix' => $prix,
                ':image' => $image,
                ':stock' => $stock,
                ':id_categorie' => $id_categorie,
                ':id_tva' => $id_tva,
                ':id_promotion' => $id_promotion
            ];
            return $this->execute($sql, $params);
        }

        public function getProductById($id_produit) {
            $sql = 'SELECT * FROM Produit WHERE id_produit = :id_produit';
            $params = [':id_produit' => $id_produit];
            return $this->fetch($sql, $params);
        }

        // Ajoutez d'autres méthodes pour gérer les produits (mise à jour, suppression, etc.)
    }
?>
