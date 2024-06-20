<?php
class Order {
    private $id_commande;
    private $date_commande;
    private $total;
    private $id_utilisateur;

    public function __construct($date_commande, $total, $id_utilisateur) {
        $this->date_commande = $date_commande;
        $this->total = $total;
        $this->id_utilisateur = $id_utilisateur;
    }

    // Getters and setters

    public function getId() {
        return $this->id_commande;
    }

    public function getDateCommande() {
        return $this->date_commande;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getIdUtilisateur() {
        return $this->id_utilisateur;
    }

    public function setDateCommande($date_commande) {
        $this->date_commande = $date_commande;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setIdUtilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }
}
