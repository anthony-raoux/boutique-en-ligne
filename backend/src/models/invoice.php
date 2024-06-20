<?php
class Invoice {
    private $id_facture;
    private $numero_facture;
    private $date_facture;
    private $total_HT;
    private $total_TTC;
    private $id_commande;

    public function __construct($numero_facture, $date_facture, $total_HT, $total_TTC, $id_commande) {
        $this->numero_facture = $numero_facture;
        $this->date_facture = $date_facture;
        $this->total_HT = $total_HT;
        $this->total_TTC = $total_TTC;
        $this->id_commande = $id_commande;
    }

    // Getters and Setters
    // Methods for saving, finding, updating, and deleting invoices...
}
?>
