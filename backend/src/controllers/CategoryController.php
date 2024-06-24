<?php
require_once 'BaseController.php';

class CategoryController extends BaseController {

    public function getAllCategories() {
        try {
            $query = "SELECT id, name FROM category";
            $stmt = $this->db->query($query);
            
            if ($stmt) {
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $categories;
            } else {
                // Handle error if query fails
                return [];
            }
        } catch (PDOException $e) {
            // Handle PDO exceptions
            // Log the error, return empty array, etc.
            return [];
        }
    }

    // Add other category-related methods here
}
?>
