document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');

    // Au chargement de la page, affiche tous les produits disponibles
    displayAllProducts();

    async function displayAllProducts() {
        try {
            const products = await fetchProducts();
            showProducts(products);
        } catch (error) {
            console.error('Erreur lors de la récupération des produits:', error);
        }
    }

    async function fetchProducts() {
        try {
            const response = await fetch('https://localhost/boutique_en_ligne/produits');
            if (response.ok) {
                return await response.json();
            } else {
                console.error('Erreur de réponse du serveur:', response.statusText);
                return [];
            }
        } catch (error) {
            console.error('Erreur de fetch:', error);
            return [];
        }
    }

    function showProducts(products) {
        products.forEach(product => {
            const productItem = document.createElement('div');
            productItem.textContent = `${product.name} - ${product.price} €`; // Exemple : afficher le nom et le prix du produit
            autocompleteList.appendChild(productItem);
        });
    }
});
