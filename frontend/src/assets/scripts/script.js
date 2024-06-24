// scripts.js

// Function to fetch all products and display them
function fetchProducts() {
   
     fetch('backend/fetch_products.php')
        .then(response => response.json())
         .then(products => {
             // Handle response and populate product-list div
             const productHtml = products.map(product => `
                 <div class="product">
                     <img src="${product.image}" alt="${product.name}">
                     <h3>${product.name}</h3>
                     <p>${product.price}</p>
                     <button onclick="showProductDetails(${product.id})">Details</button>
                 </div>
             `).join('');
             document.querySelector('.product-list').innerHTML = productHtml;
         })
         .catch(error => console.error('Error fetching products:', error));

    // Mock data for demonstration
    const products = [
        { id: 1, name: 'Product 1', image: 'product1.jpg', price: '$100', description: 'Description of Product 1' },
        { id: 2, name: 'Product 2', image: 'product2.jpg', price: '$120', description: 'Description of Product 2' }
    ];

    let productHtml = '';
    products.forEach(product => {
        productHtml += `
            <div class="product">
                <img src="${product.image}" alt="${product.name}">
                <h3>${product.name}</h3>
                <p>${product.price}</p>
                <button onclick="showProductDetails(${product.id})">Details</button>
            </div>
        `;
    });

    // Update product-list with generated HTML
    document.querySelector('.product-list').innerHTML = productHtml;
}

// Function to show product details dynamically
function showProductDetails(productId) {
   
     fetch(`backend/fetch_product_details.php?id=${productId}`)
    then(response => response.json())
         .then(product => {
             // Populate productDetailsModal with response
             const productDetailsHtml = `
                 <h2>${product.name}</h2>
             <img src="${product.image}" alt="${product.name}">
             <p>${product.price}</p>
                 <p>${product.description}</p>
                 <button>Add to Cart</button>
             `;
             document.querySelector('#productDetailsModal .modal-content').innerHTML = productDetailsHtml;
             // Display modal
             document.getElementById('productDetailsModal').style.display = 'block';
         })
         .catch(error => console.error('Error fetching product details:', error));

    // Mock data for demonstration
    const product = { id: 1, name: 'Product 1', image: 'product1.jpg', price: '$100', description: 'Description of Product 1' };

    const productDetailsHtml = `
        <h2>${product.name}</h2>
        <img src="${product.image}" alt="${product.name}">
        <p>${product.price}</p>
        <p>${product.description}</p>
        <button>Add to Cart</button>
    `;

    // Update productDetailsModal with generated HTML
    document.querySelector('#productDetailsModal .modal-content').innerHTML = productDetailsHtml;
    // Display modal
    document.getElementById('productDetailsModal').style.display = 'block';
}

// Function to close product details modal
function closeModal() {
    document.getElementById('productDetailsModal').style.display = 'none';
}

// Event listener for close button in modal
document.querySelector('.close').addEventListener('click', closeModal);

// Initial function call to fetch and display products
fetchProducts();
