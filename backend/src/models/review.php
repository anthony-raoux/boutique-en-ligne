<?php
class Review {
    private $id_avis;
    private $commentaire;
    private $note;
    private $id_utilisateur;
    private $id_produit;

    public function __construct($commentaire, $note, $id_utilisateur, $id_produit) {
        $this->commentaire = $commentaire;
        $this->note = $note;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_produit = $id_produit;
    }

    // Getters and Setters
    // Methods for saving, finding, updating, and deleting reviews...
}
?>
