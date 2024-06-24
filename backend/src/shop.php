<?php
require_once 'controllers/CategoryController.php';

$categoryController = new CategoryController();
$categories = $categoryController->getAllCategories();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom styles specific to the shop page */
    </style>
</head>
<body>
    <!-- Include the navigation bar -->
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Shop</h1>

        <!-- Filters -->
        <div class="filters">
            <h3>Filter by Category:</h3>
            <select id="categoryFilter">
                <option value="all">All Categories</option>
                <!-- Populate options dynamically using PHP -->
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Product Listing -->
        <div id="productListing">
            <!-- Products will be dynamically loaded here -->
        </div>

        <!-- Product Details Modal -->
        <div id="productDetailsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Detailed product information will be dynamically loaded here -->
            </div>
        </div>
    </div>

    <!-- Include JavaScript at the end of body to ensure DOM is fully loaded -->
    <script src="scripts.js"></script>
</body>
</html>

