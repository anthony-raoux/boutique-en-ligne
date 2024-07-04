<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'controllers/ProductController.php';

$loggedIn = isset($_SESSION['user_id']);
$adminLoggedIn = isset($_SESSION['admin_id']);
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
$cartItemCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

function isActivePage($pageName, $currentPage) {
    return $currentPage === $pageName ? 'active' : '';
}

include_once 'head.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de produits</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .suggestions {
            position: absolute;
            z-index: 10;
            width: 100%;
            background-color: #fff;
            list-style-type: none;
            padding: 0;
            margin: 0;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            display: none;
            /* Hidden initially */
            top: calc(100% + 10px); /* Position below search input */
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out; /* Smooth transition */
            transform: translateY(0); /* Initially hidden */
        }

        .suggestions.visible {
            display: block;
            opacity: 1;
            transform: translateY(0); /* Display below search input */
        }

        .suggestions li {
            padding: 0.5rem;
            cursor: pointer;
        }

        .suggestions li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class="bg-stone-800">
    <!-- Banner -->
    <?php if ($adminLoggedIn): ?>
    <div id="banner" class="sticky top-0 z-10 d-flex isolate gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 opacity-100 transition-opacity duration-500">
    <?php else: ?>
    <div id="banner" class="relative isolate flex items-center gap-x-6 overflow-hidden bg-gray-50 px-6 py-2.5 sm:px-3.5 sm:before:flex-1 opacity-100 transition-opacity duration-500">
    <?php endif; ?>
        <div class="absolute left-[max(-7rem,calc(50%-52rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl" aria-hidden="true">
            <div class="aspect-[577/310] w-[36.0625rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)"></div>
        </div>
        <div class="absolute left-[max(45rem,calc(50%+8rem))] top-1/2 -z-10 -translate-y-1/2 transform-gpu blur-2xl" aria-hidden="true">
            <div class="aspect-[577/310] w-[36.0625rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)"></div>
        </div>
        <div>
            <?php if ($adminLoggedIn): ?>
                <div class="flex items-center gap-x-4 gap-y-2">
                    <p class="text-sm leading-6 text-gray-900">
                        Portail <strong class="font-semibold">administrateur</strong>
                    </p>
                    <a href="./dashboard.php" class="flex-none rounded-full bg-gray-900 px-3.5 py-1 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                        Dashboard 
                        <span aria-hidden="true">
                            &rarr;
                        </span>
                    </a>
                </div>
            <?php elseif ($loggedIn): ?>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <p class="text-sm leading-6 text-gray-900">
                        <strong class="font-semibold">Soldes</strong>
                        <svg viewBox="0 0 2 2" class="mx-2 inline h-0.5 w-0.5 fill-current" aria-hidden="true"><circle cx="1" cy="1" r="1" /></svg>
                        Profitez des offres exclusives pour nos membres !
                    </p>
                    <a href="./shop.php" class="flex-none rounded-full bg-gray-900 px-3.5 py-1 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                        Voir les offres 
                        <span aria-hidden="true">
                            &rarr;
                        </span>
                    </a>
                </div>
            <?php else: ?>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <p class="text-sm leading-6 text-gray-900">
                        <strong class="font-semibold">Soldes</strong>
                        <svg viewBox="0 0 2 2" class="mx-2 inline h-0.5 w-0.5 fill-current" aria-hidden="true"><circle cx="1" cy="1" r="1" /></svg>
                        Inscrivez-vous pour en profitez !
                    </p>
                    <a href="./register.php" class="flex-none rounded-full bg-gray-900 px-3.5 py-1 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                        Inscription 
                        <span aria-hidden="true">
                            &rarr;
                        </span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex flex-1 justify-end">
            <?php if ($adminLoggedIn): ?>
            <?php else: ?>
                <button type="button" class="-m-3 p-3 focus-visible:outline-offset-[-4px]" id="dismiss-button">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            <?php endif; ?>
        </div>
    </div>

<!-- Navigation -->
<?php
$adminLoggedIn = true; // Replace with your actual logic for admin login
$loggedIn = true; // Replace with your actual logic for user login
$cartItemCount = 3; // Replace with the actual count of items in the cart
?>

<header class="bg-black sticky top-12 z-10 border-b border-gray-200 <?php echo $adminLoggedIn ? 'border-gray-500' : ''; ?>">
    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <button id="burger-menu-button" type="button" class="relative rounded-md bg-white p-2 lg:hidden">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="ml-4 flex lg:ml-0">
                    <a href="./index.php">
                        <span class="sr-only">Your Company</span>
                        <img class="h-8 w-auto" src="../../images/retro-pac-man.png" alt="">
                    </a>
                </div>

                <div class="hidden lg:ml-8 lg:block lg:self-stretch">
                    <div class="flex h-full space-x-8">
                        <a href="./index.php" class="flex items-center text-sm font-medium text-slate-100 hover:text-gray-800">Accueil</a>
                        <a href="./shop.php" class="flex items-center text-sm font-medium text-slate-100 hover:text-gray-800">Tous les produits</a>

                        <!-- Condition pour afficher le lien Admin Review -->
                        <?php if ($adminLoggedIn && $loggedIn): ?>
                            <a href="./admin_reviews.php" class="flex items-center text-sm font-medium text-slate-100 hover:text-gray-800">Modérer les commentaires</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


            <div class="ml-auto flex items-center">
                <div class="search-container ml-auto lg:ml-0 lg:mr-auto">
                    <input type="text" id="search-input" placeholder="Rechercher des produits..." class="search-input">
                    <ul class="suggestions" id="suggestions-list"></ul>
                </div>

                <a href="./cart_detail.php" class="group -m-2 flex items-center p-2 ml-4">
                    <svg class="h-6 w-6 flex-shrink-0 text-slate-100 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    (<?= $cartItemCount; ?>)
                    <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800"></span>
                    <span class="sr-only">items in cart, view bag</span>
                </a>

                <?php if ($loggedIn) : ?>
                    <div class="relative ml-8">
                        <button id="user-menu-button" type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-expanded="false" aria-haspopup="true">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="./profile.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Profile
                            </a>
                            <a href="./wishlist.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 01.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 01.5 0Z" />
                                </svg>
                                Wishlist
                            </a>
                            <a href="./logout.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5l3-3-3-3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12h-9" />
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <a href="./login.php" class="ml-auto flex items-center gap-2 text-base font-medium text-slate-100 hover:text-gray-800">
                        <span>Se connecter</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>


  <body class="bg-stone-800">


</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');

    searchInput.addEventListener('input', function() {
        const inputValue = this.value.trim();

        if (inputValue.length === 0) {
            suggestionsList.innerHTML = '';
            suggestionsList.style.display = 'none';
            return;
        }

        fetchSuggestions(inputValue)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    });

    function fetchSuggestions(query) {
        return fetch('search.php?q=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response;
            });
    }

    function displaySuggestions(suggestions) {
        if (suggestions.length === 0) {
            suggestionsList.innerHTML = '<li>No suggestions found</li>';
        } else {
            const items = suggestions.map(item => `<li data-product-id="${item.id_produit}">${item.nom}</li>`).join('');
            suggestionsList.innerHTML = items;
        }
        suggestionsList.style.display = 'block';

        suggestionsList.querySelectorAll('li').forEach(suggestion => {
            suggestion.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                redirectToDetail(productId);
            });
        });
    }

    function redirectToDetail(productId) {
        window.location.href = `details.php?product_id=${productId}`;
    }

    // Code Js pour le dropdown dans "navbar.php"
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    userMenuButton.addEventListener('click', function () {
        userMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });

    document.getElementById('burger-menu-button').addEventListener('click', function () {
        var menu = document.getElementById('mobile-menu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });

    const openModalButton = document.getElementById('open-search-modal');
    const closeModalButton = document.getElementById('close-search-modal');
    const searchModal = document.getElementById('search-modal');

    openModalButton.addEventListener('click', function() {
        searchModal.classList.remove('hidden');
    });

    closeModalButton.addEventListener('click', function() {
        searchModal.classList.add('hidden');
    });

    searchModal.addEventListener('click', function(event) {
        if (event.target === searchModal) {
            searchModal.classList.add('hidden');
        }
    });
});
</script>
</html>