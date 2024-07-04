document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = {
        email: formData.get('email'),
        mot_de_passe: formData.get('mot_de_passe')
    };

    try {
        const response = await fetch('http://localhost/boutique_en_ligne/backend/src/routes/authRoutes.php?action=login', {
            method: 'POST',
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
