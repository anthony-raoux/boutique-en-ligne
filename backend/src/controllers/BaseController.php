<?php
require_once './config/Database.php';

class BaseController {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
}
?>
