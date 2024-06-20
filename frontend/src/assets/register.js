document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    const formData = {
        nom: document.getElementById('nom').value,
        prenom: document.getElementById('prenom').value,
        email: document.getElementById('email').value,
        mot_de_passe: document.getElementById('mot_de_passe').value,
        adresse: document.getElementById('adresse').value,
        telephone: document.getElementById('telephone').value,
    };

    fetch('http://boutique_en_ligne/path/to/authRoutes.php?action=register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inscription réussie');
        } else {
            alert('Erreur lors de l\'inscription : ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
