<!-- Gére la connexion à une base de données MySQL et exécuter des requêtes SQL à partir de méthodes dédiées -->

<?php
    class Database {
        private $host = 'localhost';
        private $db_name = 'boutique_en_ligne';
        private $username = 'root';
        private $password = '';
        private $conn;

        public function connect() {
            $this->conn = null;
            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }
            return $this->conn;
        }

        public function query($sql, $params = []) {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        }

        public function fetch($sql, $params = []) {
            $stmt = $this->query($sql, $params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function fetchAll($sql, $params = []) {
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function execute($sql, $params = []) {
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        }
    }
?>
