<?php
    session_start();

    require_once 'controllers/ProductController.php';

    $productController = new ProductController();

    $product_id = $_GET['product_id'] ?? '';

    if (!$product_id) {
        echo "Produit non trouvé.";
        exit;
    }

    $result = $productController->getProductById($product_id);
    $product = $result['product'] ?? null;

    if (!$product) {
        echo "Produit non trouvé.";
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

?>

<?php 
    include_once 'head.php';
    include_once 'navbar.php';
?>
    <section class="relative py-10">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="img">
                <div class="img-box h-full">
                    <?php if (!empty($product['image'])): ?>
                        <img class="rounded-lg mx-auto max-w-full h-auto lg:max-h-full" src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                    <?php else: ?>
                        <img src="../../images/placeholder.jpg" alt="Image indisponible" class="mx-auto max-w-full h-auto lg:max-h-full">
                    <?php endif; ?>
                </div>
            </div>
            <div class="data lg:pr-8">
                <div class="max-w-xl mx-auto">
                    <p class="text-lg font-medium leading-8 text-indigo-600 mb-4">Tout les produits&nbsp; /&nbsp; <?php echo htmlspecialchars($product['nom_categorie']); ?></p>
                    <h2 class="font-manrope font-bold text-3xl leading-10 text-white mb-2 capitalize"><?php echo htmlspecialchars($product['nom']); ?></h2>
                        <div class="flex flex-col sm:flex-row sm:items-center mb-6">
                            <h6 class="font-manrope font-semibold text-2xl leading-9 text-white pr-5">
                                <?php echo htmlspecialchars($product['prix']); ?> €
                            </h6>
                            <!-- <p class="text-white">Stock: <?php echo htmlspecialchars($product['stock']); ?></p> -->
                        </div>
                        <p class="text-gray-300 text-base font-normal mb-5">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </p>
                        <div>
                            <form class="flex flex-row gap-5" id="add-to-cart-form" action="addToCart.php" method="POST" class="mb-4">
                                <button type="submit" class="border border-white text-white hover:bg-dark w-full rounded px-4 py-2">Ajouter au panier</button>
                                <div>
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="10" class="bg-black text-white border border-white rounded p-3">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produit ajouté au panier!');
                } else {
                    alert('Erreur: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }

        function removeFromCart(productId) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
</body>
</html>
