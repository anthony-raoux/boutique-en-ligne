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

// Initialisation de la variable $inWishlist
$inWishlist = false;

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    try {
        // Récupération de la wishlist de l'utilisateur
        $wishlist = $wishlistController->getWishlist($_SESSION['user_id']);
        foreach ($wishlist as $item) {
            if ($item['id_produit'] == $product_id) {
                $inWishlist = true;
                break;
            }
        }
    } catch (Exception $e) {
        // Gérer les exceptions si nécessaire
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once 'navbar.php'; ?>
    <header></header>
    <main class="container mx-auto px-4 mt-10">
        <div class="bg-white rounded-lg shadow-md p-6 md:flex md:items-start md:space-x-6">
            <?php if (!empty($product['image'])) : ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" class="w-full md:w-1/2 rounded-lg mb-4 md:mb-0">
            <?php else : ?>
                <img src="placeholder.jpg" alt="Image indisponible" class="w-full md:w-1/2 rounded-lg mb-4 md:mb-0">
            <?php endif; ?>
            <div class="md:w-1/2">
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($product['nom']); ?></h1>
                <p class="text-lg mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="text-2xl font-bold text-blue-600 mb-2">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
                <p class="mb-2">Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                <p class="mb-2">Catégorie: <?php echo htmlspecialchars($product['nom_categorie']); ?></p>

                <form id="add-to-cart-form" action="addToCart.php" method="POST" class="mb-4">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
                    <input type="number" name="quantity" value="1" min="1" max="10" class="border rounded p-2 w-24 mb-2">
                    <button type="submit" class="bg-indigo-500 text-white rounded px-4 py-2">Ajouter au panier</button>
                </form>

                <?php if ($inCart) : ?>
                    <form id="remove-from-cart-form" action="removeFromCart.php" method="POST" class="mb-4">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                        <button type="submit" class="bg-red-500 text-white rounded px-4 py-2">Supprimer du panier</button>
                    </form>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="wishlist-btn mb-4">
                        <?php if ($inWishlist) : ?>
                            <form action="details.php?product_id=<?= $product_id ?>" method="POST">
                                <button type="submit" name="remove_from_wishlist" class="bg-red-500 text-white rounded px-4 py-2">Retirer de la wishlist</button>
                            </form>
                        <?php else : ?>
                            <form action="details.php?product_id=<?= $product_id ?>" method="POST">
    <button type="submit" name="add_to_wishlist" class="bg-yellow-500 text-white rounded px-4 py-2">Ajouter à la wishlist</button>
</form>


                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <p class="mb-4">Veuillez <a href="login.php" class="text-blue-500">vous connecter</a> pour ajouter des produits à votre wishlist.</p>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <form action="details.php?product_id=<?= $id_produit ?>" method="POST" class="mb-4">
                        <textarea name="commentaire" required placeholder="Votre commentaire" class="border rounded p-2 w-full mb-2"></textarea>
                        <label for="note" class="block mb-2">Note :</label>
                        <select name="note" required class="border rounded p-2 w-full mb-2">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <button type="submit" name="submit_review" class="bg-indigo-500 text-white rounded px-4 py-2">Soumettre</button>
                    </form>
                <?php else : ?>
                    <p class="mb-4">Veuillez <a href="login.php" class="text-blue-500">vous connecter</a> pour laisser un commentaire.</p>
                <?php endif; ?>

            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-2xl font-bold mb-4">Commentaires :</h3>
    <?php
    // Requête SQL pour récupérer les commentaires
    $sql = "SELECT commentaire, note, prenom_utilisateur, created_at FROM avis WHERE id_produit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id_produit);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($comments)) :
        foreach ($comments as $row) :
    ?>
            <div class="border p-4 mb-4">
                <p class="text-lg font-semibold">Note : <?php echo htmlspecialchars($row['note']); ?>/5</p>
                <p class="text-sm">Par : <?php echo isset($row['prenom_utilisateur']) ? htmlspecialchars($row['prenom_utilisateur']) : 'Utilisateur anonyme'; ?> le <?php echo htmlspecialchars($row['created_at']); ?></p>
                <p class="text-sm"><?php echo isset($row['commentaire']) ? htmlspecialchars($row['commentaire']) : 'Commentaire non disponible'; ?></p>
            </div>
    <?php
        endforeach;
    else :
    ?>
        <p class="text-lg">Aucun commentaire pour ce produit pour le moment.</p>
    <?php endif; ?>
</div>

    </main>
    <footer></footer>
</body>

</html>
