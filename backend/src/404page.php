<!-- 
 Gère l'affichage de la page d'erreur 404
  lorsqu'une page demandée n'est pas trouvée. 
-->
 
 <?php
    include_once 'head.php';
    ?>
        <style>
            .bg404 {
                background: url('../../images/404bg');
                background-repeat: no-repeat;
                background-attachment: fixed;  
                background-size: cover;
            }
        </style>

    <body class="bg404 flex items-center justify-center min-h-screen">
            <div class="text-center">
                <p class="text-base font-semibold text-indigo-600">404</p>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl">Page perdue</h1>
                <p class="mt-6 text-base leading-7 text-gray-300">Désolé, nous n’avons pas trouvé la page que vous recherchez.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="./index.php" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Retours à l'acceuil</a>
                </div>
            </div>