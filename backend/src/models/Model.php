<!-- 
    Classe Model servant de modèle de base pour 
    les autres classes de modèle.

    ----------------------------------------------------------------
    
    Interagie avec la base de données via des méthodes comme query, 
    fetch, fetchAll, execute.
-->

<?php
    require_once 'Database.php';

    class Model {
        protected $db;

        public function __construct() {
            $this->db = new Database();
        }

        protected function query($sql, $params = []) {
            return $this->db->query($sql, $params);
        }

        protected function fetch($sql, $params = []) {
            return $this->db->fetch($sql, $params);
        }

        protected function fetchAll($sql, $params = []) {
            return $this->db->fetchAll($sql, $params);
        }

        protected function execute($sql, $params = []) {
            return $this->db->execute($sql, $params);
        }
    }
?>
