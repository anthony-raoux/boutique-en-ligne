<?php
session_start();

require_once 'controllers/ProductController.php';

$productController = new ProductController();

// Récupérer tous les produits
$result = $productController->getAllProducts();
$products = $result['products'] ?? [];  // Récupérer les produits ou initialiser à un tableau vide

// Debugging: Check the result of getAllProducts
if (!$result['success']) {
    echo "Erreur : " . $result['error'];
}

// Message d'erreur ou de succès lors des opérations
$message = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Assurez-vous de mettre le bon chemin vers votre fichier CSS -->
    <style>
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        .product {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            width: 300px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .product p {
            font-size: 1rem;
            line-height: 1.5;
        }
        .product .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff; /* Couleur du prix à ajuster selon votre thème */
        }
        .filter {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php require_once 'navbar.php'; ?>
    <header>
        <!-- Inclure ici le code de votre en-tête (Header) si nécessaire -->
    </header>
    
    <main>
        <h1>Shop</h1>

        <!-- Formulaire de filtrage par catégorie -->
        <form id="categoryFilterForm">
            <label for="category">Filtrer par catégorie :</label>
            <select id="category" name="category">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id_categorie']); ?>"><?php echo htmlspecialchars($category['nom']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        
        <div class="products">
            <?php if (empty($products)): ?>
                <p>Aucun produit disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <?php if (!empty($product['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image indisponible" /> <!-- Remplacez placeholder.jpg par votre image par défaut -->
                        <?php endif; ?>
                        <h2><?php echo htmlspecialchars($product['nom'] ?? 'Nom indisponible'); ?></h2>
                        <p><?php echo htmlspecialchars($product['description'] ?? 'Description indisponible'); ?></p>
                        <p class="price">Prix: <?php echo htmlspecialchars($product['prix'] ?? 'Prix indisponible'); ?> €</p>
                        <p>Stock: <?php echo htmlspecialchars($product['stock'] ?? 'Stock indisponible'); ?></p>
                        <!-- Ajoutez ici d'autres informations sur le produit si nécessaire -->
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <!-- Inclure ici le code de votre pied de page (Footer) si nécessaire -->
    </footer>

    <script>
        document.getElementById('categoryFilterForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const categoryId = document.getElementById('category').value;
            fetchProducts(categoryId);
        });

        function fetchProducts(categoryId) {
            let url = 'fetch_products.php';
            if (categoryId !== '') {
                url += '?category=' + encodeURIComponent(categoryId);
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const productsContainer = document.querySelector('.products');
                        productsContainer.innerHTML = '';
                        data.products.forEach(product => {
                            const productHTML = `
                                <div class="product">
                                    <img src="data:image/jpeg;base64,${product.image}" alt="${product.nom}" />
                                    <h2>${product.nom}</h2>
                                    <p>${product.description}</p>
                                    <p class="price">Prix: ${product.prix} €</p>
                                    <p>Stock: ${product.stock}</p>
                                </div>
                            `;
                            productsContainer.innerHTML += productHTML;
                        });
                    } else {
                        console.error('Erreur lors du chargement des produits : ' + data.error);
                    }
                })
                .catch(error => console.error('Erreur fetch : ' + error));
        }
    </script>
</body>
</html>
