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
