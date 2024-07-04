<!-- 
    Méthodes pour sauvegarder, trouver, mettre à jour
     et supprimer des éléments de ligne de 'COMMANDE' 
     (non implémentées)
-->

<?php
    class LineItem {
        private $id_ligne_commande;
        private $quantite;
        private $prix_unitaire;
        private $id_commande;
        private $id_produit;

        public function __construct($quantite, $prix_unitaire, $id_commande, $id_produit) {
            $this->quantite = $quantite;
            $this->prix_unitaire = $prix_unitaire;
            $this->id_commande = $id_commande;
            $this->id_produit = $id_produit;
        }

        // Getters and Setters
        // Methods for saving, finding, updating, and deleting line items...
    }
?>
