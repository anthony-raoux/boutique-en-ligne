<!-- 
    Méthodes pour sauvegarder, trouver, mettre à jour
    et supprimer des taxes
    (non implémentées). 
-->

<?php
    class Tax {
        private $id_tva;
        private $description;
        private $rate;

        public function __construct($description, $rate) {
            $this->description = $description;
            $this->rate = $rate;
        }

        // Getters and Setters
        // Methods for saving, finding, updating, and deleting taxes...
    }
?>
