<?php
    include_once 'head.php';
?>


<body class="bg-stone-800 flex items-center justify-center min-h-screen">
    <div class="max-w-md mx-auto my-10 p-6 rounded-md shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-white">Paiement</h2>

        <form action="#" method="POST">
            <div class="mb-6">
                <label for="cardNumber" class="block text-sm font-medium text-white mb-1">Numéro de Carte</label>
                <input type="text" id="cardNumber" name="cardNumber"
                    class="w-full bg-zinc-900 px-3 py-2 text-sm text-white placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="1234 5678 9012 3456" required>
            </div>

            <div class="mb-6">
                <label for="cardName" class="block text-sm font-medium text-white mb-1">Nom sur la Carte</label>
                <input type="text" id="cardName" name="cardName"
                    class="w-full bg-zinc-900 px-3 py-2 text-sm text-white placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="John Doe" required>
            </div>

            <div class="flex mb-6">
                <div class="w-1/2 mr-2">
                    <label for="expiryDate" class="block text-sm font-medium text-white mb-1">Date d'Expiration</label>
                    <input type="text" id="expiryDate" name="expiryDate"
                        class="w-full bg-zinc-900 px-3 py-2 text-sm text-white placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="MM/YY" required>
                </div>
                <div class="w-1/2 ml-2">
                    <label for="cvv" class="block text-sm font-medium text-white mb-1">CVV</label>
                    <input type="text" id="cvv" name="cvv"
                        class="w-full bg-zinc-900 px-3 py-2 text-sm text-white placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="123" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-zinc-50 text-black py-2 px-4 rounded-md hover:bg-zinc-950 hover:text-white focus:outline-none">Payer</button>
        </form>
    </div>
</body>

</html>
