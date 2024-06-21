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

        const result = await response.json();
        
        if (result.success) {
            console.log('Utilisateur enregistré avec succès:', result.message);
            // Affichez un message de succès à l'utilisateur
            alert('Votre compte a bien été créé !');
            // Redirigez l'utilisateur vers une autre page, par exemple :
            // window.location.href = '/boutique_en_ligne/frontend/welcome.php';
        } else {
            console.error('Erreur lors de l\'inscription:', result.error);
            // Affichez un message d'erreur à l'utilisateur si nécessaire
            alert('Erreur lors de l\'inscription: ' + result.error);
        }

    } catch (error) {
        console.error('Erreur lors de la requête fetch:', error);
        // Affichez un message d'erreur générique à l'utilisateur
        alert('Erreur lors de la requête fetch, veuillez réessayer.');
    }
});
