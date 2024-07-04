document.addEventListener('DOMContentLoaded', function () {
    const banner = document.getElementById('banner');
    const dismissButton = document.getElementById('dismiss-button');

    // Fade in effect
    setTimeout(() => {
        banner.classList.remove('opacity-0');
        banner.classList.add('opacity-100');
    }, 100); // delay for the initial appearance

    // Fade out effect on button click
    dismissButton.addEventListener('click', function () {
        banner.classList.remove('opacity-100');
        banner.classList.add('opacity-0');

        // Optionally, remove the banner from the DOM after the fade out effect
        setTimeout(() => {
            banner.style.display = 'none';
        }, 500); // matches the duration-500 class
    });
});