document.getElementById('updateProfileForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const id_utilisateur = document.getElementById('id_utilisateur').value;
    const nom = document.getElementById('nom').value;
    const prenom = document.getElementById('prenom').value;
    const email = document.getElementById('email').value;
    const mot_de_passe = document.getElementById('mot_de_passe').value;
    const adresse = document.getElementById('adresse').value;
    const telephone = document.getElementById('telephone').value;

    fetch('http://localhost/backend/src/authRoutes.php?action=updateProfile', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_utilisateur: id_utilisateur,
            nom: nom,
            prenom: prenom,
            email: email,
            mot_de_passe: mot_de_passe,
            adresse: adresse,
            telephone: telephone
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully');
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const userId = 1; // Remplacez par l'ID utilisateur actuel, par exemple récupéré via une session

    fetch(`http://localhost/backend/src/profile.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('id_utilisateur').value = user.id_utilisateur;
                document.getElementById('nom').value = user.nom;
                document.getElementById('prenom').value = user.prenom;
                document.getElementById('email').value = user.email;
                document.getElementById('adresse').value = user.adresse;
                document.getElementById('telephone').value = user.telephone;
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
