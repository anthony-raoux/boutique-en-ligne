document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');

    searchInput.addEventListener('input', function() {
        const inputValue = this.value.trim();

        if (inputValue.length === 0) {
            suggestionsList.innerHTML = '';
            suggestionsList.style.display = 'none';
            return;
        }

        fetchSuggestions(inputValue)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    });

    function fetchSuggestions(query) {
        return fetch('search.php?q=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response;
            });
    }

    function displaySuggestions(suggestions) {
        if (suggestions.length === 0) {
            suggestionsList.innerHTML = '<li>No suggestions found</li>';
        } else {
            const items = suggestions.map(item => `<li>${item}</li>`).join('');
            suggestionsList.innerHTML = items;
        }
        suggestionsList.style.display = 'block';
    }
});
