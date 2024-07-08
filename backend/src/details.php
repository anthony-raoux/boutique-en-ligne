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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mx-auto max-md:px-2">
            <div class="img">
                <div class="img-box h-full max-lg:mx-auto">
                    <?php if (!empty($product['image'])): ?>
                        <img class="rounded-lg" src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="max-lg:mx-auto lg:ml-auto h-full">
                    <?php else: ?>
                        <img src="../../images/placeholder.jpg" alt="Image indisponible" class="max-lg:mx-auto lg:ml-auto h-full">
                    <?php endif; ?>
                </div>
            </div>
            <div class="data w-full lg:pr-8 pr-0 xl:justify-start justify-center flex  max-lg:pb-10 xl:my-2 lg:my-5 my-0">
                <div class="data w-full max-w-xl">
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
                        <div class="flex flex-col gap-y-5">
                            <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">
                                Acheter
                            </button>
                            <div class="flex gap-x-5">
                                <button type="submit" class="w-full bg-stone-800 border border-white text-white py-2 px-4 rounded-md hover:bg-stone-900 hover:text-white focus:outline-none"  onclick="addToCart(<?php echo $product['id_produit']; ?>)">
                                    Ajouter au panier
                                </button>
                                <button type="submit" class=" bg-stone-800 border border-white text-white py-2 px-4 rounded-md focus:outline-none hover:bg-stone-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <form id="add-to-cart-form" action="addToCart.php" method="POST" class="mb-4">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                    <input type="number" name="quantity" value="1" min="1" max="10" class="border rounded p-2 w-24 mb-2">
                    <button type="submit" class="bg-indigo-500 text-white rounded px-4 py-2">Ajouter au panier</button>
                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    include_once 'footer.php';
?>

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
