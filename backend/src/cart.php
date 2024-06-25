<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

$cart = $_SESSION['cart'] ?? [];
$products = [];

if ($cart) {
    foreach ($cart as $product_id => $quantity) {
        $result = $productController->getProductById($product_id);
        if ($result['success']) {
            $product = $result['product'];
            $product['quantity'] = $quantity;
            $products[] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <style>
        .cart { margin: 20px auto; width: 80%; max-width: 800px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .cart h1 { font-size: 2rem; margin-bottom: 20px; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .cart-item img { width: 100px; height: auto; border-radius: 8px; }
        .cart-item-details { flex: 1; margin-left: 20px; }
        .cart-item-details h2 { font-size: 1.5rem; margin-bottom: 10px; }
        .cart-item-details p { font-size: 1rem; line-height: 1.5; }
        .cart-item-details .price { font-size: 1.25rem; font-weight: bold; color: #007bff; }
        .cart-item-details .quantity { font-size: 1rem; margin-top: 10px; }
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <div class="cart">
            <h1>Votre Panier</h1>
            <?php if (empty($products)): ?>
                <p>Votre panier est vide.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="cart-item">
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image indisponible" />
                        <?php endif; ?>
                        <div class="cart-item-details">
                            <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
                            <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                            <p class="quantity">Quantité: <?php echo htmlspecialchars($product['quantity']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
