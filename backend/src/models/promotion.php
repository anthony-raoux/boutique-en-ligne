<?php
class Promotion {
    private $id_promotion;
    private $description;
    private $discount_percentage;
    private $start_date;
    private $end_date;

    public function __construct($description, $discount_percentage, $start_date, $end_date) {
        $this->description = $description;
        $this->discount_percentage = $discount_percentage;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    // Getters and Setters
    // Methods for saving, finding, updating, and deleting promotions...
}
?>
