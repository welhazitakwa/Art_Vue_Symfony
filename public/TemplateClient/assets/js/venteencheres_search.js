// public/TemplateClient/js/venteencheres_search.js

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = searchInput.value;

        fetch(`/venteencheres/search?q=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'affichage des résultats
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    });

    function displaySearchResults(results) {
        // Effacer les résultats précédents
        searchResults.innerHTML = '';

        // Afficher les nouveaux résultats
        results.forEach(result => {
            const resultItem = document.createElement('div');
            resultItem.textContent = `ID: ${result.id}, Date de début: ${result.datedebut}, Date de fin: ${result.datefin}, Prix de départ: ${result.prixdepart} DT, Statut: ${result.statue ? 'En cours' : 'Terminée'}`;
            searchResults.appendChild(resultItem);
        });
    }
});
