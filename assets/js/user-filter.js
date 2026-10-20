function filtrer(type) {
    const lignes = document.querySelectorAll('tbody tr[data-type]');

    // L'attribut data-see-all vaut '1' pour ROLE_DIRECTION et ROLE_ADMIN
    const meta = document.getElementById('current-user-meta');
    const voitTout = meta && meta.dataset.seeAll === '1';

    // Collecte uniquement les boutons présents dans le DOM (Administration peut être absent)
    const ids = ['btn-Tous', 'btn-Medecin', 'btn-Infirmier', 'btn-Administration'];
    const boutons = ids.map(id => document.getElementById(id)).filter(Boolean);

    // Réinitialise tous les boutons
    boutons.forEach(btn => {
        btn.classList.remove('active', 'btn-primary');
        btn.classList.add('btn-outline-primary');
    });

    // Active le bouton cliqué
    const actif = document.getElementById('btn-' + type);
    if (actif) {
        actif.classList.add('active', 'btn-primary');
        actif.classList.remove('btn-outline-primary');
    }

    // Filtre les lignes
    lignes.forEach(ligne => {
        const dataType = ligne.dataset.type;

        if (type === 'Tous') {
            // Direction et Admin voient tout ; les autres voient seulement Médecin + Infirmier
            ligne.style.display = (voitTout || dataType !== 'Administration') ? '' : 'none';
        } else {
            ligne.style.display = (dataType === type) ? '' : 'none';
        }
    });
}

// Applique l'état initial au chargement de la page
document.addEventListener('DOMContentLoaded', () => filtrer('Tous'));

