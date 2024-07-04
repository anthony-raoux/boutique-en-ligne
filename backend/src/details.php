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
            <div class="data w-full lg:pr-8 pr-0 xl:justify-start justify-center flex items-center max-lg:pb-10 xl:my-2 lg:my-5 my-0">
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
                    <div class="flex gap-5">
                        <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 18.3332C14.5833 18.3332 18.3333 14.5832 18.3333 9.99984C18.3333 5.4165 14.5833 1.6665 10 1.6665C5.41667 1.6665 1.66667 5.4165 1.66667 9.99984C1.66667 14.5832 5.41667 18.3332 10 18.3332Z" stroke="black" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 6.6665V10.8332L13.3333 12.4998" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Acheter
                        </button>
                        <button type="submit" class="w-full border border-white text-white py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">Se connecter</button>
                    </div>
                    
                    <!-- <div class="flex gap-3">
                        <button type="submit" class="py-4 px-8 lg:text-base text-sm font-medium text-center bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:outline-none focus:ring-indigo-300 text-white rounded-full flex items-center gap-3 transition-all">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 18.3332C14.5833 18.3332 18.3333 14.5832 18.3333 9.99984C18.3333 5.4165 14.5833 1.6665 10 1.6665C5.41667 1.6665 1.66667 5.4165 1.66667 9.99984C1.66667 14.5832 5.41667 18.3332 10 18.3332Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 6.6665V10.8332L13.3333 12.4998" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Acheter
                        </button>

                        <a class="py-4 px-8 lg:text-base text-sm font-medium text-center border border-gray-300 hover:border-gray-400 text-gray-500 rounded-full flex items-center gap-3 transition-all onclick="addToCart(<?php echo $product['id_produit']; ?>)">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.1663 2.5H17.4997C17.7217 2.5 17.9337 2.5878 18.0892 2.7433C18.2447 2.89886 18.3325 3.11087 18.3325 3.33283V6.66616" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.5 11.6662V16.6662C17.5 16.8882 17.4122 17.1002 17.2567 17.2557C17.1011 17.4112 16.8891 17.499 16.6672 17.499H3.33283C3.11087 17.499 2.89886 17.4112 2.7433 17.2557C2.5878 17.1002 2.5 16.8882 2.5 16.6662V11.6662" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.5 6.66683V3.3335C2.5 3.11154 2.5878 2.89953 2.7433 2.744C2.89886 2.58845 3.11087 2.50066 3.33283 2.50066H6.66616" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.3332 5.8335C13.3332 7.23624 12.7355 8.5611 11.7353 9.5613C10.7351 10.5615 9.41028 11.1592 7.99984 11.1592C6.58941 11.1592 5.26455 10.5615 4.26435 9.5613C3.26415 8.5611 2.6665 7.23624 2.6665 5.8335L7.99984 1.66683L13.3332 5.8335Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Ajouter au panier
                        </a>
                    </div> -->
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
