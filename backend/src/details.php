<?php
session_start();

require_once 'controllers/ProductController.php';
require_once 'controllers/WishlistController.php';
require_once 'config/Database.php';

$productController = new ProductController();
$database = new Database();
$conn = $database->connect();
$wishlistController = new WishlistController($conn);

$product_id = $_GET['product_id'] ?? '';
if (!$product_id) {
    echo "ID de produit manquant.";
    exit;
}

$product = $productController->getProductById($product_id);
$product = $product['product'] ?? null;
if (!$product) {
    echo "Produit non trouvé ou erreur de récupération.";
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$inCart = array_key_exists($product_id, $_SESSION['cart']);

$id_produit = $product_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_review'])) {
        $commentaire = $_POST['commentaire'];
        $note = intval($_POST['note']);
        $id_utilisateur = $_SESSION['user_id'];
        $prenom_utilisateur = isset($_SESSION['user_prenom']) ? $_SESSION['user_prenom'] : 'Utilisateur anonyme';

        $sql = "INSERT INTO avis (commentaire, note, id_utilisateur, prenom_utilisateur, id_produit, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $commentaire);
        $stmt->bindParam(2, $note);
        $stmt->bindParam(3, $id_utilisateur);
        $stmt->bindParam(4, $prenom_utilisateur);
        $stmt->bindParam(5, $id_produit);
        $stmt->execute();
    } elseif (isset($_POST['add_to_wishlist'])) {
        $wishlistController->addToWishlist($_SESSION['user_id'], $product_id);
    } elseif (isset($_POST['remove_from_wishlist'])) {
        $wishlistController->removeFromWishlist($_SESSION['user_id'], $product_id);
    }
}

$inWishlist = false;
$wishlist = $wishlistController->getWishlist($_SESSION['user_id']);
foreach ($wishlist as $item) {
    if ($item['id_produit'] == $product_id) {
        $inWishlist = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <style>
        .product-details { margin: 20px auto; width: 80%; max-width: 800px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .product-details img { width: 100%; height: auto; border-radius: 8px; }
        .product-details h1 { font-size: 2rem; margin-bottom: 10px; }
        .product-details p { font-size: 1rem; line-height: 1.5; }
        .product-details .price { font-size: 1.5rem; font-weight: bold; color: #007bff; }
        .product-details .add-to-cart { margin-top: 20px; }
        .wishlist-btn { margin-top: 20px; }
        .reviews { margin-top: 40px; }
        .review { border-top: 1px solid #ccc; padding: 10px 0; }
    </style>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    <header></header>
    <main>
        <div class="product-details">
            <?php if (!empty($product['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
            <?php else: ?>
                <img src="placeholder.jpg" alt="Image indisponible" />
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($product['nom']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
            <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
            <p>Catégorie: <?php echo htmlspecialchars($product['nom_categorie']); ?></p>

            <form id="add-to-cart-form" action="addToCart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                <input type="number" name="quantity" value="1" min="1" max="10">
                <button type="submit">Ajouter au panier</button>
            </form>

            <?php if ($inCart) : ?>
                <form id="remove-from-cart-form" action="removeFromCart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                    <button type="submit">Supprimer du panier</button>
                </form>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="wishlist-btn">
                    <?php if ($inWishlist): ?>
                        <form action="details.php?product_id=<?= $product_id ?>" method="POST">
                            <button type="submit" name="remove_from_wishlist">Retirer de la wishlist</button>
                        </form>
                    <?php else: ?>
                        <form action="details.php?product_id=<?= $product_id ?>" method="POST">
                            <button type="submit" name="add_to_wishlist">Ajouter à la wishlist</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>Veuillez <a href="login.php">vous connecter</a> pour ajouter des produits à votre wishlist.</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="details.php?product_id=<?= $id_produit ?>" method="POST">
                    <textarea name="commentaire" required placeholder="Votre commentaire"></textarea>
                    <label for="note">Note :</label>
                    <select name="note" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <button type="submit" name="submit_review">Soumettre</button>
                </form>
            <?php else: ?>
                <p>Veuillez <a href="login.php">vous connecter</a> pour laisser un commentaire.</p>
            <?php endif; ?>

            <div class="reviews">
                <h3>Commentaires :</h3>
                <?php
                $sql = "SELECT commentaire, note, prenom_utilisateur, created_at FROM avis WHERE id_produit = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $id_produit);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $row) {
                    echo '<div class="review">';
                    echo '<p>Note : ' . htmlspecialchars($row['note']) . '/5</p>';
                    echo '<p>Par : ' . (isset($row['prenom_utilisateur']) ? htmlspecialchars($row['prenom_utilisateur']) : 'Utilisateur anonyme') . ' le ' . htmlspecialchars($row['created_at']) . '</p>';
                    echo '<p>' . (isset($row['commentaire']) ? htmlspecialchars($row['commentaire']) : 'Commentaire non disponible') . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>
    <footer></footer>
</body>
</html>
