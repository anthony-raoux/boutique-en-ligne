<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Vérifier si le produit existe dans le panier
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Supprimer le produit du panier
        unset($_SESSION['cart'][$product_id]);
    }

    // Rediriger vers la page de détails du produit après la suppression
    header('Location: details.php?product_id=' . $product_id);
    exit;
} else {
    // Redirection en cas de tentative d'accès direct à ce fichier sans POST
    header('Location: index.php');
    exit;
}
?>
