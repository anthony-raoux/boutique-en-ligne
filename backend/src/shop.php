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
            <div class="product" data-id="<?php echo $product['id_produit']; ?>">
                <?php if (!empty($product['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                <?php else: ?>
                    <img src="placeholder.jpg" alt="Image indisponible" />
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($product['nom'] ?? 'Nom indisponible'); ?></h2>
                <p><?php echo htmlspecialchars($product['description'] ?? 'Description indisponible'); ?></p>
                <p class="price">Prix: <?php echo htmlspecialchars($product['prix'] ?? 'Prix indisponible'); ?> €</p>
                <p>Stock: <?php echo htmlspecialchars($product['stock'] ?? 'Stock indisponible'); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    </main>
    
    <footer>
        <!-- Inclure ici le code de votre pied de page (Footer) si nécessaire -->
    </footer>

    <script>
    // Sélectionnez tous les éléments avec la classe 'product'
    const products = document.querySelectorAll('.product');

    // Ajoutez un gestionnaire d'événement de clic à chaque produit
    products.forEach(product => {
        product.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            fetchProductDetails(productId);
        });
    });

    function fetchProductDetails(productId) {
    let url = 'fetch_product_details.php?product_id=' + encodeURIComponent(productId);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showProductDetails(data.product);
            } else {
                console.error('Erreur lors de la récupération des détails du produit : ' + data.error);
            }
        })
        .catch(error => {
            console.error('Erreur fetch : ' + error);
            // Handle specific types of errors if needed
        });
}



    // Fonction pour afficher les détails du produit
    function showProductDetails(product) {
        // Exemple : Affichez les détails dans une modale
        alert(`Nom: ${product.nom}\nDescription: ${product.description}\nPrix: ${product.prix} €\nStock: ${product.stock}`);
    }
</script>


</body>
</html>
