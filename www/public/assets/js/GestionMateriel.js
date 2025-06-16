// Selection de la valeur de l'input type matériel dans enregistrer matériel
document.getElementById('materialType').addEventListener('change', function() {
    const typeId = this.value;
    if (typeId) {
        fetch('/gestion-materiel/get-cards', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ type_id: typeId })
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('cardsContainer').innerHTML = html;
            attachSaveListeners(); // très important
        });
    } else {
        document.getElementById('cardsContainer').innerHTML = "";
    }
});


function attachSaveListeners() {
    document.querySelectorAll('.materialForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const reference = this.querySelector('input[name="reference"]').value;
            const typeId = this.querySelector('input[name="type_id"]').value;
            const etat = this.querySelector('select[name="etat"]').value;

            fetch('/gestion-materiel/save-card', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reference, idTypeMateriel: typeId, etat: etat })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.card-item').remove();
                    if (document.querySelectorAll('.card-item').length === 0) {
                        document.getElementById('cardsContainer').innerHTML = '<div class="alert alert-success">Tous les matériels de ce type ont été enregistrés.</div>';
                    }
                } else {
                    alert("Erreur : " + data.error);
                }
            });
        });
    });
}

document.getElementById('filterType').addEventListener('change', function() {
    const typeId = this.value;
    if (typeId) {
        fetch('/gestion-materiel/get-material-list', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ type_id: typeId })
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('materialsList').innerHTML = html;
        });
    } else {
        document.getElementById('materialsList').innerHTML = "";
    }
});