<!-- Initialise la connexion à la base de données dans son constructeur -->

<?php
    require_once __DIR__ . '/../config/Database.php';

    class BaseController {
        protected $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->connect();
        }
    }
?>
