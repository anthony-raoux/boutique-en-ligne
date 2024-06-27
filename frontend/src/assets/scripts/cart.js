// cart.js

// Sélection de l'élément span contenant le nombre de produits dans la navbar
const cartItemCountElement = document.getElementById('cartItemCount');

// Fonction pour mettre à jour le nombre de produits dans la navbar
function updateCartItemCount(count) {
    cartItemCountElement.textContent = count;
}

// Fonction pour supprimer un produit du panier via AJAX
function removeFromCart(productId) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId }),
    })
    .then(response => {
        if (response.ok) {
            // Mettre à jour le nombre de produits dans la navbar après suppression
            fetchCartItemCount();
        } else {
            console.error('Erreur lors de la suppression du produit du panier.');
        }
    })
    .catch(error => console.error('Erreur lors de la suppression du produit du panier :', error));
}

// Fonction pour récupérer le nombre de produits dans le panier via AJAX
function fetchCartItemCount() {
    fetch('fetch_cart_item_count.php')
    .then(response => response.json())
    .then(data => {
        updateCartItemCount(data.cartItemCount);
    })
    .catch(error => console.error('Erreur lors de la récupération du nombre de produits dans le panier :', error));
}

// Appel initial pour afficher le nombre de produits au chargement de la page
fetchCartItemCount();
