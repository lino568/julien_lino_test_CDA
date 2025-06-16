document.addEventListener('DOMContentLoaded', function () {
    const dateDebutReservation = document.getElementById('dateDebutReservation');
    const heureDebutReservation = document.getElementById('heureDebutReservation');
    const dateFinReservation = document.getElementById('dateFinReservation');
    const heureFinReservation = document.getElementById('heureFinReservation');
    const materiel = document.getElementById('materiel');
    const boutonDisponibilite = document.getElementById('disponibilite');
    const submitButton = document.querySelector('button[type="submit"]');

    function checkFormCompletion() {

        if (dateDebutReservation.value == "" ||
            heureDebutReservation.value == "" ||
            dateFinReservation.value == "" ||
            heureFinReservation.value == "" ||
            materiel.value == "") {
            boutonDisponibilite.disabled = true;
            submitButton.disabled = true;
        } else {
            boutonDisponibilite.disabled = false;
            submitButton.disabled = false;
        }

    }

    // Ajoute des écouteurs d'événements pour chaque champ
    dateDebutReservation.addEventListener('input', checkFormCompletion);
    heureDebutReservation.addEventListener('input', checkFormCompletion);
    dateFinReservation.addEventListener('input', checkFormCompletion);
    heureFinReservation.addEventListener('input', checkFormCompletion);
    materiel.addEventListener('change', checkFormCompletion);

    // Appel initial pour désactiver le bouton si nécessaire au chargement
    checkFormCompletion();
});

function verifyDisponibility() {

    const dateDebutReservation = document.getElementById('dateDebutReservation').value;
    const heureDebutReservation = document.getElementById('heureDebutReservation').value;
    const dateFinReservation = document.getElementById('dateFinReservation').value;
    const heureFinReservation = document.getElementById('heureFinReservation').value;
    const materiel = document.getElementById('materiel').value;
    const quantite = document.getElementById('quantite').value;
    const affichageMessage = document.getElementById('disponibility');

    fetch('/API/verify-disponibility-material', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            dateDebutReservation,
            heureDebutReservation,
            dateFinReservation,
            heureFinReservation,
            materiel,
            quantite
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            affichageMessage.innerHTML = '<div class="alert alert-success text-center" role="alert">Le matériel est disponible</div>';
        } else {
            affichageMessage.innerHTML = '<div class="text-center mb-3">' + data.error + '</div>';
        }
    })
}
