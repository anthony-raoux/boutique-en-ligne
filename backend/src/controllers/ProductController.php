<?php
class ProductController {
    private $productService;

    public function __construct($productService) {
        $this->productService = $productService;
    }

    public function getAllProducts() {
        return $this->productService->fetchAllProducts();
    }

    public function getProductById($id) {
        return $this->productService->fetchProductById($id);
    }

    public function createProduct($productData) {
        return $this->productService->createNewProduct($productData);
    }

    // Other methods as needed
}
?>
