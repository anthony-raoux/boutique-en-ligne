<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modals Page</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <!-- Modal 1 -->
    <div id="modal1" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Contenu du Modal 1</h2>
            <p>Ceci est le contenu du premier modal.</p>
            <button id="closeModal1" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Fermer</button>
        </div>
    </div>

    <!-- Modal 2 -->
    <div id="modal2" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Contenu du Modal 2</h2>
            <p>Ceci est le contenu du deuxième modal.</p>
            <button id="closeModal2" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Fermer</button>
        </div>
    </div>

</body>
</html>
