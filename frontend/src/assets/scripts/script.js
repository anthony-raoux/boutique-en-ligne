document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const productListing = document.getElementById('productListing');

    // Fetch and display all products initially
    fetchProducts();

    // Fetch products when the category filter changes
    categoryFilter.addEventListener('change', function() {
        const categoryId = categoryFilter.value;
        fetchProducts(categoryId);
    });

    // Function to fetch products and display them
    function fetchProducts(categoryId = 'all') {
        let url = 'backend/fetch_products.php';
        if (categoryId !== 'all') {
            url += `?category_id=${categoryId}`;
        }

        console.log('Fetching products from:', url); // Debug output

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Fetched products:', data); // Debug output
                displayProducts(data.products);
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    // Function to display products in the product listing
    function displayProducts(products) {
        const productHtml = products.map(product => `
            <div class="product">
                <img src="${product.image}" alt="${product.name}">
                <h3>${product.name}</h3>
                <p>${product.price}</p>
                <button onclick="showProductDetails(${product.id})">Details</button>
            </div>
        `).join('');
        productListing.innerHTML = productHtml;
    }

    // Function to show product details dynamically
    window.showProductDetails = function(productId) {
        fetch(`backend/fetch_product_details.php?id=${productId}`)
            .then(response => response.json())
            .then(product => {
                const productDetailsHtml = `
                    <h2>${product.name}</h2>
                    <img src="${product.image}" alt="${product.name}">
                    <p>${product.price}</p>
                    <p>${product.description}</p>
                    <button>Add to Cart</button>
                `;
                document.querySelector('#productDetailsModal .modal-content').innerHTML = productDetailsHtml;
                document.getElementById('productDetailsModal').style.display = 'block';
            })
            .catch(error => console.error('Error fetching product details:', error));
    }

    // Function to close product details modal
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('productDetailsModal').style.display = 'none';
    });
});
