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

// Simuler le paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vider le panier après le paiement simulé
    $_SESSION['cart'] = [];
    $paymentSuccess = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer à la caisse</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <style>
        .checkout { margin: 20px auto; width: 80%; max-width: 800px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .checkout h1 { font-size: 2rem; margin-bottom: 20px; }
        .checkout-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .checkout-item img { width: 100px; height: auto; border-radius: 8px; }
        .checkout-item-details { flex: 1; margin-left: 20px; }
        .checkout-item-details h2 { font-size: 1.5rem; margin-bottom: 10px; }
        .checkout-item-details p { font-size: 1rem; line-height: 1.5; }
        .checkout-item-details .price { font-size: 1.25rem; font-weight: bold; color: #007bff; }
        .checkout-item-details .quantity { font-size: 1rem; margin-top: 10px; }
        .payment-success { text-align: center; margin-top: 20px; color: green; }
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <div class="checkout">
            <h1>Passer à la caisse</h1>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="checkout-item">
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image indisponible" />
                        <?php endif; ?>
                        <div class="checkout-item-details">
                            <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
                            <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                            <p class="quantity">Quantité: <?php echo htmlspecialchars($product['quantity']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <form method="POST" action="checkout.php">
                    <button type="submit">Payer</button>
                </form>
            <?php else: ?>
                <p>Votre panier est vide.</p>
            <?php endif; ?>
            <?php if (isset($paymentSuccess) && $paymentSuccess): ?>
                <div class="payment-success">
                    <p>Paiement réussi ! Merci pour votre achat.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
