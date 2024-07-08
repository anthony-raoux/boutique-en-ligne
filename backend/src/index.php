<?php
session_start(); // Démarrage de la session (si ce n'est pas déjà fait)
require_once 'config/Database.php';

// Connexion à la base de données
$database = new Database();
$db = $database->connect();

// Requête pour récupérer 4 produits au hasard
$stmt_random = $db->query('SELECT * FROM produits ORDER BY RAND() LIMIT 4');
$random_products = $stmt_random->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer 4 autres produits au hasard (différents de la première requête)
$stmt_random2 = $db->query('SELECT * FROM produits ORDER BY RAND() LIMIT 4');
$random_products2 = $stmt_random2->fetchAll(PDO::FETCH_ASSOC);

// Example: Check if user is logged in (you need to implement your own login logic)
$loggedIn = isset($_SESSION['user_id']); // Adjust according to your session management

// Set current page for navbar active class
$currentPage = 'home';
// Include the navbar from backend/src/
include_once 'head.php';
include_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
   <style>
    .logo-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
}

.logo-container {
    display: flex;
    width: calc(250px * 8); /* Adjust according to the number of logos */
    animation: scroll 20s linear infinite;
}

.logo {
    flex: 0 0 150px; /* Adjust the width of each logo */
    max-height: 12vh;
    object-contain: contain;
}

@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

   </style>
</head>
<body class="bg-gray-900 text-white">
    <?php include_once 'navbar.php'; ?>
    
    <!-- Carousel Section -->
    <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner.jpeg" class="absolute block w-full h-full object-cover" alt="Retro Banner 1">
            </div>
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner_1.jpg" class="absolute block w-full h-full object-cover" alt="Retro Banner 1">
            </div>
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner_2.png" class="absolute block w-full h-full object-cover" alt="Retro Banner 2">
            </div>
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner_3.jpg" class="absolute block w-full h-full object-cover" alt="Retro Banner 3">
            </div>
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner_6.jpeg" class="absolute block w-full h-full object-cover" alt="Retro Banner 4">
            </div>
            <div class="duration-700 ease-in-out" data-carousel-item>
                <img src="../../images/retro_banner_5.jpg" class="absolute block w-full h-full object-cover" alt="Retro Banner 5">
            </div>
        </div>
        <div class="absolute z-30 flex space-x-3 bottom-2 left-1/2 transform -translate-x-1/2">
            <button type="button" class="w-3 h-3 rounded-full bg-white" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 6" data-carousel-slide-to="5"></button>
        </div>
        <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1L1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 9l4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

  <!-- Brand Section -->
<div class="py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <h2 class="text-center text-lg font-semibold leading-8 text-gray-300">Retrouvez toutes vos marques préférées</h2>
        <div class="logo-wrapper">
            <div class="logo-container">
                <img class="logo" src="../../images/nintendo_logo.png" alt="Nintendo Logo">
                <img class="logo" src="../../images/playstation_logo.png" alt="Playstation Logo">
                <img class="logo" src="../../images/xbox_logo.png" alt="Xbox Logo">
                <img class="logo" src="../../images/sega_logo.png" alt="Sega Logo">
                <!-- Repeat the logos to create a seamless scrolling effect -->
                <img class="logo" src="../../images/nintendo_logo.png" alt="Nintendo Logo">
                <img class="logo" src="../../images/playstation_logo.png" alt="Playstation Logo">
                <img class="logo" src="../../images/xbox_logo.png" alt="Xbox Logo">
                <img class="logo" src="../../images/sega_logo.png" alt="Sega Logo">
            </div>
        </div>
    </div>
</div>


    <!-- Products Section -->
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-300">Nos meilleures ventes</h2>
        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php foreach ($random_products as $product): ?>
                <div class="group relative">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <?php 
                            $imageData = $product['image']; 
                            $imageType = pathinfo($imageData, PATHINFO_EXTENSION);
                            $imageBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        ?>
                        <img src="<?= $imageBase64 ?>" alt="<?= htmlspecialchars($product['nom']) ?>" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <h3 class="text-sm text-gray-300">
                            <a href="details.php?product_id=<?= $product['id_produit'] ?>">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                <?= htmlspecialchars($product['nom']) ?>
                            </a>
                        </h3>
                        <p class="text-lg font-bold text-white whitespace-nowrap"><?= htmlspecialchars($product['prix']) ?> €</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Another Products Section -->
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-300">Autres produits</h2>
        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php foreach ($random_products2 as $product): ?>
                <div class="group relative">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <?php 
                            $imageData = $product['image']; 
                            $imageType = pathinfo($imageData, PATHINFO_EXTENSION);
                            $imageBase64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        ?>
                        <img src="<?= $imageBase64 ?>" alt="<?= htmlspecialchars($product['nom']) ?>" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <h3 class="text-sm text-gray-300">
                            <a href="details.php?product_id=<?= $product['id_produit'] ?>">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                <?= htmlspecialchars($product['nom']) ?>
                            </a>
                        </h3>
                        <p class="text-lg font-bold text-white whitespace-nowrap"><?= htmlspecialchars($product['prix']) ?> €</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('[data-carousel="slide"]');
            const items = carousel.querySelectorAll('[data-carousel-item]');
            const prevButton = carousel.querySelector('[data-carousel-prev]');
            const nextButton = carousel.querySelector('[data-carousel-next]');
            const indicators = carousel.querySelectorAll('[data-carousel-slide-to]');

            let currentIndex = 0;

            function updateCarousel() {
                items.forEach((item, index) => {
                    item.classList.toggle('hidden', index !== currentIndex);
                });

                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('bg-white', index === currentIndex);
                    indicator.classList.toggle('bg-white/30', index !== currentIndex);
                });
            }

            function showNextSlide() {
                currentIndex = (currentIndex + 1) % items.length;
                updateCarousel();
            }

            function showPreviousSlide() {
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                updateCarousel();
            }

            function goToSlide(index) {
                currentIndex = index;
                updateCarousel();
            }

            prevButton.addEventListener('click', showPreviousSlide);
            nextButton.addEventListener('click', showNextSlide);

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => goToSlide(index));
            });

            setInterval(showNextSlide, 4500);

            updateCarousel();
        });
    </script>

    <?php include_once 'footer.php'; ?>
</body>
</html>