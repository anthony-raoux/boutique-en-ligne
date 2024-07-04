<!-- 
    Méthodes CRUD pour sauvegarder, trouver, mettre à jour
     et supprimer des catégories
-->

<?php
    class Category {
        private $id_categorie;
        private $nom;
        private $id_parent_categorie;

        public function __construct($nom, $id_parent_categorie = null) {
            $this->nom = $nom;
            $this->id_parent_categorie = $id_parent_categorie;
        }

        // Getters and Setters
        // Methods for saving, finding, updating, and deleting categories...
        
        // Method to get all categories from the database
            public static function getAllCategories($db) {
                $stmt = $db->prepare("SELECT * FROM categories");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    }
?>
