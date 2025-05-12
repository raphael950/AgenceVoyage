const resultsPerPage = 5;
let currentPage = 1;
let currentSort = '';
let ascending = true;
let sortedResults = [...allResults];

// calculer la durée d’un voyage
sortedResults.forEach(voyage => {
    console.log(voyage);
    if (!voyage.duree) {
        voyage.duree = 0;
        voyage.etapes.forEach(etape => {
            voyage.duree += etape.nb_jours;
        });
    }
});

function renderResults(results, page = 1) {
    const start = (page - 1) * resultsPerPage;
    const end = start + resultsPerPage;
    const paginated = results.slice(start, end);

    const container = document.querySelector('.results');
    container.innerHTML = '';

    if (paginated.length === 0) {
        container.innerHTML = '<p>Aucun résultat trouvé.</p>';
        return;
    }

    paginated.forEach(result => {
        const imageUrl = `assets/voyages/${result.id}/miniature.png`;
        const card = document.createElement('div');
        card.className = 'card';
        const nbEtapes = result.etapes?.length || 0;
        const duree = result.duree || nbEtapes;
        const prix = result.prix !== undefined ? result.prix + ' €' : 'Non précisé';

        card.innerHTML = `
            <img src="${imageUrl}" alt="Image de ${imageUrl}">
            <div class="card-content">
                <h3>${result.titre}</h3>
                <p>${result.texte}</p>
                <p><strong>Pays :</strong> ${result.pays}</p>
                <p><strong>Prix/pers :</strong> min. ${prix}</p>
                <p><strong>Durée :</strong> ${duree} jours</p>
                <p><strong>Étapes :</strong> ${nbEtapes}</p>
                <a href="voyage.php?id=${result.id}">Voir les détails</a>
            </div>
        `;

        container.appendChild(card);
    });

    renderPagination(results.length, page);
}

function renderPagination(totalResults, current) {
    const container = document.querySelector('.pagination');
    container.innerHTML = '';
    const totalPages = Math.ceil(totalResults / resultsPerPage);

    if (current > 1) {
        container.innerHTML += `<a href="#" data-page="${current - 1}">Précédent</a>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        container.innerHTML += `<a href="#" data-page="${i}" style="${i === current ? 'font-weight: bold;' : ''}">${i}</a>`;
    }

    if (current < totalPages) {
        container.innerHTML += `<a href="#" data-page="${current + 1}">Suivant</a>`;
    }

    document.querySelectorAll('.pagination a').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            currentPage = parseInt(a.dataset.page);
            renderResults(sortedResults, currentPage);
        });
    });
}

function sortResults(by) {
    if (currentSort === by) {
        ascending = !ascending;
    } else {
        ascending = true;
        currentSort = by;
    }

    sortedResults.sort((a, b) => {
        let valA, valB;
        if (by === 'prix') {
            valA = a.prix || 0;
            valB = b.prix || 0;
        } else if (by === 'duree') {
            valA = a.duree || 0;
            valB = b.duree || 0;
        } else if (by === 'etapes') {
            valA = a.etapes?.length || 0;
            valB = b.etapes?.length || 0;
        }

        return ascending ? valA - valB : valB - valA;
    });

    currentPage = 1;
    renderResults(sortedResults, currentPage);
}

document.addEventListener('DOMContentLoaded', () => {
    renderResults(sortedResults, currentPage);
});
