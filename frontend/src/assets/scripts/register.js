document.getElementById('registerForm').addEventListener('submit', async function (e) {
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
            throw new Error('Network response was not ok');
        }

        const result = await response.json();
        console.log('Registration successful:', result);
    } catch (error) {
        console.error('Error:', error);
    }
});
