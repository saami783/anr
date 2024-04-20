/**
 * Ce script est attaché à l'élément select pour les villes dans le formulaire d'inscription.
 * Lorsque l'utilisateur change la sélection de la ville, cet événement déclenche une requête AJAX.
 *
 * @function fetch - Cette fonction envoie une requête HTTP GET à l'URL '/get-streets/' suivie de l'ID de la ville.
 * @param {string} cityId - L'ID de la ville sélectionnée, récupérée depuis la valeur de l'élément select.
 *
 * La réponse attendue est un objet JSON contenant un tableau de rues associées à la ville sélectionnée.
 * En cas de succès :
 * - Les options existantes dans le select des rues sont d'abord supprimées.
 * - Une option de placeholder est ajoutée indiquant de sélectionner une rue.
 * - Des options pour chaque rue sont ensuite créées et ajoutées au select,
 *   permettant ainsi à l'utilisateur de choisir parmi les rues de la ville sélectionnée.
 *
 * En cas d'erreur dans la requête ou la réponse, aucun changement n'est appliqué au select des rues.
 */
// Script générique pour charger dynamiquement les rues basées sur la ville sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.querySelector('.dynamic-city-select');
    const streetSelect = document.querySelector('.dynamic-street-select');

    if (citySelect && streetSelect) {
        citySelect.addEventListener('change', function() {
            const cityId = this.value;

            fetch(`/get-streets/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    streetSelect.innerHTML = '';
                    data.streets.forEach(street => {
                        const option = document.createElement('option');
                        option.value = street.id;
                        option.textContent = street.name;
                        streetSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading streets:', error));
        });
    }
});

