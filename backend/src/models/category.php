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
}
?>
