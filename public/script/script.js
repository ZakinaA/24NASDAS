document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('search-bar');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchQuery = this.value;

            // Si le champ de recherche est vide, on réaffiche tous les cours
            if (searchQuery.length < 3) {
                fetch('/24NASDAS/public/cours/lister/json') 
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la récupération des cours');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const coursList = document.getElementById('cours-list');
                        coursList.innerHTML = '';  // Vider la liste des cours existants

                        if (data.cours.length > 0) {
                            data.cours.forEach(c => {
                                const card = document.createElement('a');
                                card.href = '24NASDAS/public/cours/' + c.id;
                                card.classList.add('card-link');
                                card.innerHTML = `
                                    <div class="card">
                                        <img src="/24NASDAS/public/images/${c.cheminImage}" alt="Image du cours" class="card-img"> 
                                        <h3>${c.libelle}</h3>
                                    </div>
                                `;
                                coursList.appendChild(card);
                            });
                        } else {
                            coursList.innerHTML = '<p>Aucun cours trouvé.</p>';
                        }
                    })
                    .catch(error => {
                    });
                return;
            }

            // Sinon, effectuer la recherche si la requête a au moins 3 caractères
            fetch('/24NASDAS/public/search/cours?query=' + encodeURIComponent(searchQuery))
                .then(response => {
                    // Vérifie si le statut HTTP est correct
                    if (!response.ok) {
                        throw new Error('Erreur lors de la récupération des cours');
                    }
                    return response.json();
                })
                .then(data => {
                    const coursList = document.getElementById('cours-list');
                    coursList.innerHTML = '';  // Vider la liste des cours existants

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    if (data.cours.length > 0) {
                        data.cours.forEach(c => {
                            const card = document.createElement('a');
                            card.href = '/24NASDAS/public/cours/consulter/' + c.id;
                            card.classList.add('card-link');
                            card.innerHTML = `
                                <div class="card">
                                        <img src="/24NASDAS/public/images/${c.cheminImage}" alt="Image du cours" class="card-img"> 
                                    <h3>${c.libelle}</h3>
                                </div>
                            `;
                            coursList.appendChild(card);
                        });
                    } else {
                        coursList.innerHTML = '<p>Aucun cours trouvé.</p>';
                    }
                })
                .catch(error => {
                });
        });
    }
});
