document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        nom: formData.get('nom'),
        prenom: formData.get('prenom'),
        email: formData.get('email'),
        mot_de_passe: formData.get('mot_de_passe'),
        adresse: formData.get('adresse'),
        telephone: formData.get('telephone')
    };

    try {
        const response = await fetch('http://localhost/boutique_en_ligne/backend/src/routes/authRoutes.php?action=register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Erreur HTTP: ' + response.status);
        }

        // Analyser la réponse JSON uniquement si le statut est OK
        const result = await response.json();
        if (result.success) {
            console.log('Utilisateur enregistré avec succès:', result.message);
            // Afficher le message de confirmation
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML = '<p>Inscription réussie !</p>';
            // Vous pouvez également rediriger l'utilisateur vers une autre page ici si nécessaire
        } else {
            console.error('Erreur lors de l\'inscription:', result.error);
            // Affichage d'un message d'erreur si nécessaire
        }

    } catch (error) {
        console.error('Erreur lors de la requête fetch:', error);
        // Gérer d'autres erreurs, par exemple si la requête échoue complètement
    }
});
