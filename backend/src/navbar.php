<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Démarrer la session si elle n'est pas déjà démarrée
    }

    // Vérifier si l'utilisateur est connecté en tant qu'utilisateur normal ou administrateur
    $loggedIn = isset($_SESSION['user_id']);
    $adminLoggedIn = isset($_SESSION['admin_id']);

    // Déterminer la page actuelle
    $currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');

    // Calculer le nombre d'articles dans le panier
    $cartItemCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

    // Fonction pour vérifier si une page est active
    function isActivePage($pageName, $currentPage) {
        return $currentPage === $pageName ? 'active' : '';
    }

    include_once 'head.php';
?>


    <!-- Banner 1 - Utilisateur pas connectées -->
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
                <!-- Contenu de la bannière pour les administrateurs connectés -->
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
                <!-- Contenu de la bannière pour les utilisateurs connectés -->
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
                <!-- Contenu de la bannière pour les utilisateurs non connectés -->
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


<?php if ($adminLoggedIn): ?>
<header class=" bg-black sticky top-12 z-10 border-b border-gray-200">
<?php else: ?>
<header class=" bg-black sticky top-0 z-10 border-b border-gray-200">
<?php endif; ?>
    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div>
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center flex-end">
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
                        <a href="./shop.php" class="flex items-center text-sm font-medium text-slate-100 hover:text-gray-800">Tout les produits</a>
                        <a href="./admin_reviews.php" class="flex items-center text-sm font-medium text-slate-100 hover:text-gray-800">Modérer les commentaires</a>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center flex-end">
                <div class="flex lg:ml-6">
                    <a id="open-search-modal" href="#" class="p-2 text-slate-100 hover:text-gray-500">
                        <span class="sr-only">Search</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </a>
                </div>
                <div class="ml-4 flow-root lg:ml-6">
                    <a href="./cart_detail.php" class="group -m-2 flex items-center p-2">
                        <svg class="h-6 w-6 flex-shrink-0 text-slate-100 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        (<?= $cartItemCount; ?>)
                        <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800"></span>
                        <span class="sr-only">items in cart, view bag</span>
                    </a>
                </div>

                <?php if ($loggedIn) : ?>
                    <div class="ml-auto flex items-center">
                        <div class="relative ml-8">
                            <div>
                                <button id="user-menu-button" type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-expanded="false" aria-haspopup="true">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                </button>
                            </div>
                            <div id="user-menu" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <a href="./profile.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Profile
                                </a>
                                <a href="./wishlist" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                    Wishlist
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    Paramètre
                                </a>
                                <a href="./logout.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                    Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6 ml-5">
                        <?php if ($adminLoggedIn) : ?>
                            <div class="relative ml-8">
                                <div>
                                    <button id="user-menu-button" type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-expanded="false" aria-haspopup="true">
                                        <span class="absolute -inset-1.5"></span>
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    </button>
                                </div>
                                <div id="user-menu" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                    <a href="./profile.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 01.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 01.5 0Z" />
                                        </svg>
                                        Commande
                                    </a>
                                    <a href="./logout.php" class="block px-4 py-2 text-sm text-slate-100 hover:bg-black flex items-center gap-2" role="menuitem" tabindex="-1" id="user-menu-item-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                        </svg>
                                        Déconnexion
                                    </a>
                                </div>
                            </div>
                        <?php else : ?>
                            <a href="./login.php" class="text-sm font-medium text-slate-100 hover:text-gray-800">Connexion</a>
                            <span class="h-6 w-px bg-gray-200" aria-hidden="true"></span>
                            <a href="./register.php" class="text-sm font-medium text-slate-100 hover:text-gray-800">Inscription</a>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden lg:hidden">
            <div class="pt-2 pb-3 space-y-1 bg-black"> <!-- Fond du menu mobile sombre -->
                <a href="./index.php" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-base font-medium text-gray-300 hover:text-gray-100 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-gray-100">Accueil</a>
                <?php if ($adminLoggedIn) : ?>
                    <a href="./dashboard.php" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-300 hover:text-gray-100 hover:bg-gray-700 focus:bg-gray-700 focus:text-gray-100">Dashboard</a>
                <?php endif; ?>
                <a href="./shop.php" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-300 hover:text-gray-100 hover:bg-gray-700 focus:bg-gray-700 focus:text-gray-100">Tous les produits</a>
            </div>
        </div>
    </div>
</nav>

    </header>


  <body class="bg-stone-800">



<!-- Modal de barre de recherche -->
<div id="search-modal" class="fixed inset-0 z-50 mt-32 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <!-- Formulaire de recherche avec icône loupe -->
        <!-- Formulaire de recherche avec icône loupe -->
<!-- Formulaire de recherche avec icône loupe -->
<form action="#" method="GET" class="flex items-center bg-black text-end rounded-lg border border-gray-300 flex-col p-4">
    <div class="relative flex w-full">
        <input type="text" id="search-input" class="bg-black text-white text-sm rounded-lg pl-10 pr-3.5 py-2.5 w-full" placeholder="Rechercher...">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
    </div>
    <div id="suggestions-list" class="mt-3 w-full bg-gray-800 text-white rounded-lg shadow-lg">
        <p class="text-gray-300 p-2 text-center">Aucune résultat</p>
    </div>
</form>
         <!-- Bouton pour fermer le modal -->
         <button id="close-search-modal" class="absolute top-0 right-0 m-3 text-gray-600 hover:text-gray-800 focus:outline-none">
            <svg class="h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>



<!-- Script pour ouvrir/fermer le modal -->
<script>
    const openModalButton = document.getElementById('open-search-modal');
    const closeModalButton = document.getElementById('close-search-modal');
    const searchModal = document.getElementById('search-modal');

    // Ouvrir le modal au clic sur le bouton
    openModalButton.addEventListener('click', function() {
        searchModal.classList.remove('hidden');
    });

    // Fermer le modal au clic sur le bouton de fermeture
    closeModalButton.addEventListener('click', function() {
        searchModal.classList.add('hidden');
    });

    // Fermer le modal si l'utilisateur clique en dehors du modal
    searchModal.addEventListener('click', function(event) {
        if (event.target === searchModal) {
            searchModal.classList.add('hidden');
        }
    });
</script>


<!-- Gère la visibilité du modal de recherche sur le body -->
  <script>
    document.getElementById('burger-menu-button').addEventListener('click', function () {
        var menu = document.getElementById('mobile-menu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });
</script>

<script>
    // Code Js pour le dropdown dans "navbar.php"
    document.addEventListener('DOMContentLoaded', function () {
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
    });
</script>


<!-- Bar de recherche -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');

    // Afficher le message par défaut avant que l'utilisateur commence à taper
    const defaultMsg = '<p class="text-gray-500 p-2 text-center"> Cherchez la consoles / accessoires de votre reve...</p>';
    suggestionsList.innerHTML = defaultMsg;

    searchInput.addEventListener('input', function() {
        const inputValue = this.value.trim();

        if (inputValue.length === 0) {
            suggestionsList.innerHTML = defaultMsg;
            return;
        }

        fetchSuggestions(inputValue)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Désolé, aucune suggestion trouvée:', error);
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
            suggestionsList.innerHTML = '<p class="text-gray-500 p-2 text-center">Aucune suggestion trouvée</p>';
        } else {
            const items = suggestions.map(item => `
                <button type="button" class="block w-full text-left px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white" data-product-id="${item.id_produit}">
                    <div class="flex justify-between items-center">
                        <span>${item.nom}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                    </div>
                </button>
            `).join('');
            suggestionsList.innerHTML = items;
        }

        suggestionsList.querySelectorAll('button').forEach(suggestion => {
            suggestion.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                redirectToDetail(productId);
            });
        });
    }

    function redirectToDetail(productId) {
        window.location.href = `details.php?product_id=${productId}`;
    }
});
</script>




