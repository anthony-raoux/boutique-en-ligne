<?php
session_start(); // Démarrage de la session (si ce n'est pas déjà fait)
require_once 'config/Database.php';

// Connexion à la base de données
$database = new Database();
$db = $database->connect();

// Requête pour récupérer 3 produits au hasard
$stmt_random = $db->query('SELECT * FROM produits ORDER BY RAND() LIMIT 4');
$random_products = $stmt_random->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer 3 autres produits au hasard (différents de la première requête)
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
    <link rel="stylesheet" href="../frontend/css/styles.css">
  
</head>
<body>
    <?php include_once 'navbar.php'; ?>
    
   <!-- Carousel Section -->
<div id="default-carousel" class="relative w-full" data-carousel="slide">
    <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
        <div class="duration-700 ease-in-out object-center" data-carousel-item>
            <img src="../../images/retro_banner_1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
        </div>
        <div class="duration-700 ease-in-out" data-carousel-item>
            <img src="../../images/retro_banner_2.png" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
        </div>
        <div class="duration-700 ease-in-out" data-carousel-item>
            <img src="../../images/retro_banner_3.png" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
        </div>
        <div class="duration-700 ease-in-out" data-carousel-item>
            <img src="../../images/retro_banner_4.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
        </div>
        <div class="duration-700 ease-in-out object-bottom" data-carousel-item>
            <img src="../../images/retro_banner_5.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover" alt="...">
        </div>
    </div>
    <div class="absolute z-30 flex -translate-x-1/2 bottom-2 left-1/2 space-x-3 rtl:space-x-reverse">
        <button type="button" class="w-3 h-3 rounded-full bg-white" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
        <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
        <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
        <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
        <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
    </div>
    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</div>


    <!-- Brand Section -->
    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-lg font-semibold leading-8 text-black">Retrouvez tout vos marque préférées</h2>
            <div class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="../../images/nintendo_logo.png" alt="Transistor" width="500" height="auto">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="../../images/playstation_logo.png" alt="Reform" width="250" height="auto">
                <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="../../images/xbox_logo.png" alt="Tuple" width="250" height="auto">
                <img class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1" src="../../images/sega_logo.png" alt="SavvyCal" width="250" height="auto">
            </div>
        </div>
    </div>

 <!-- Products Section -->
<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
    <h2 class="text-2xl font-bold tracking-tight text-black">Nos meilleures ventes</h2>
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
                    <div>
                        <h3 class="text-sm text-black">
                            <a href="details.php?product_id=<?= $product['id_produit'] ?>">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                <?= htmlspecialchars($product['nom']) ?>
                            </a>
                        </h3>
                    </div>
                    <p class="text-lg font-bold text-red-600 ml-4"><?= htmlspecialchars($product['prix']) ?> €</p>
                </div>
                <div class="mt-2 text-center">
                    <a href="details.php?product_id=<?= $product['id_produit'] ?>" class="inline-block py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-500">Acheter</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Another Products Section -->
<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
    <h2 class="text-2xl font-bold tracking-tight text-black">Autres produits</h2>
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
                    <div>
                        <h3 class="text-sm text-black">
                            <a href="details.php?product_id=<?= $product['id_produit'] ?>">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                <?= htmlspecialchars($product['nom']) ?>
                            </a>
                        </h3>
                    </div>
                    <p class="text-lg font-bold text-red-600 ml-4"><?= htmlspecialchars($product['prix']) ?> €</p>
                </div>
                <div class="mt-2 text-center">
                    <a href="details.php?product_id=<?= $product['id_produit'] ?>" class="inline-block py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-500">Acheter</a>
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
