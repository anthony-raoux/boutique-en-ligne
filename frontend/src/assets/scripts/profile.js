async function loadUserProfile(userId) {
    try {
        const response = await fetch(`http://localhost/mon_projet/backend/src/routes/authRoutes.php?action=profile&id=${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const user = await response.json();
        document.getElementById('nom').value = user.nom;
        document.getElementById('prenom').value = user.prenom;
        document.getElementById('email').value = user.email;
        document.getElementById('adresse').value = user.adresse;
        document.getElementById('telephone').value = user.telephone;
    } catch (error) {
        console.error('Error:', error);
    }
}

document.getElementById('profileForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = {
        id_utilisateur: formData.get('id_utilisateur'),
        nom: formData.get('nom'),
        prenom: formData.get('prenom'),
        email: formData.get('email'),
        adresse: formData.get('adresse'),
        telephone: formData.get('telephone')
    };

    try {
        const response = await fetch('http://localhost/boutique_en_ligne/backend/src/routes/authRoutes.php?action=updateProfile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const result = await response.json();
        console.log('Success:', result);
        // Handle success, e.g., display a success message or redirect
    } catch (error) {
        console.error('Error:', error);
        // Handle error, e.g., display an error message
    }
});
