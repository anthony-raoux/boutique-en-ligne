// modal.js

// Fonction pour ouvrir un modal
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

// Fonction pour fermer un modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Ajout des événements aux boutons
document.addEventListener('DOMContentLoaded', () => {
    // Bouton pour ouvrir le Modal 1
    const openModal1Btn = document.getElementById('openModal1');
    if (openModal1Btn) {
        openModal1Btn.addEventListener('click', () => openModal('modal1'));
    }

    // Bouton pour ouvrir le Modal 2
    const openModal2Btn = document.getElementById('openModal2');
    if (openModal2Btn) {
        openModal2Btn.addEventListener('click', () => openModal('modal2'));
    }

    // Bouton pour fermer le Modal 1
    const closeModal1Btn = document.getElementById('closeModal1');
    if (closeModal1Btn) {
        closeModal1Btn.addEventListener('click', () => closeModal('modal1'));
    }

    // Bouton pour fermer le Modal 2
    const closeModal2Btn = document.getElementById('closeModal2');
    if (closeModal2Btn) {
        closeModal2Btn.addEventListener('click', () => closeModal('modal2'));
    }
});
