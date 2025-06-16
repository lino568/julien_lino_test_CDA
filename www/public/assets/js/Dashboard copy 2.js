// Données simulées pour la démonstration
const mockReservations = {
    'ordinateurs': [
        {
            date: '2025-06-09', // Lundi, 9 juin 2025
            time: '09:00',
            title: 'Formation PHP',
            user: 'M. Dubois',
            status: 'confirmed',
            quantity: 8
        },
        {
            date: '2025-06-10', // Mardi, 10 juin 2025
            time: '14:00',
            title: 'Cours JavaScript',
            user: 'Mme Martin',
            status: 'pending',
            quantity: 12
        },
        {
            date: '2025-06-11', // Mercredi, 11 juin 2025
            time: '10:00',
            title: 'TP Base de données',
            user: 'M. Bernard',
            status: 'confirmed',
            quantity: 10
        },
        {
            date: '2025-06-13', // Vendredi, 13 juin 2025
            time: '09:00',
            title: 'Examen final',
            user: 'Mme Leroy',
            status: 'confirmed',
            quantity: 15
        }
    ],
    'projecteurs': [
        {
            date: '2025-06-09', // Lundi, 9 juin 2025
            time: '10:00',
            title: 'Conférence',
            user: 'M. Durand',
            status: 'confirmed',
            quantity: 2
        },
        {
            date: '2025-06-12', // Jeudi, 12 juin 2025
            time: '14:00',
            title: 'Présentation',
            user: 'Mme Petit',
            status: 'cancelled',
            quantity: 1
        }
    ]
};

let currentWeekStart = new Date();
currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Lundi

function formatDate(date) {
    const options = { day: 'numeric', month: 'long' };
    return date.toLocaleDateString('fr-FR', options);
}

function updateWeekDisplay() {
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);

    document.getElementById('weekStart').textContent = formatDate(currentWeekStart);
    document.getElementById('weekEnd').textContent = formatDate(weekEnd) + ' ' + weekEnd.getFullYear();
}

function generateCalendarGrid(reservations = []) {
    const days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    // const dayKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']; // Plus nécessaire
    const times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

    let html = '<div class="calendar-grid">';

    // En-tête vide pour la colonne des heures
    html += '<div class="day-header"></div>';

    // Stocke les dates exactes pour chaque jour de la semaine affichée
    const currentWeekDaysDates = [];
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        currentWeekDaysDates.push(date.toISOString().split('T')[0]); // Format YYYY-MM-DD
        html += `
            <div class="day-header">
                <div class="day-name">${day}</div>
                <div class="day-number">${date.getDate()}</div>
            </div>
        `;
    });

    // Grille des créneaux horaires
    times.forEach(time => {
        html += `<div class="time-slot">${time}</div>`;

        currentWeekDaysDates.forEach(currentDayDateString => { // Itère sur les dates exactes
            const dayReservations = reservations.filter(r =>
                r.date === currentDayDateString && r.time === time
            );
            html += '<div class="calendar-cell">';

            dayReservations.forEach(reservation => {
                // Vous pouvez passer la date exacte aux détails si nécessaire
                html += `
                    <div class="reservation ${reservation.status}" onclick="showReservationDetails(
                        '${reservation.title}',
                        '${reservation.user}',
                        '${reservation.status}',
                        ${reservation.quantity},
                        '${reservation.date}' // Ajoutez la date ici
                    )">
                        <div class="reservation-title">${reservation.title}</div>
                        <div class="reservation-user">${reservation.user} (${reservation.quantity})</div>
                    </div>
                `;
            });

            html += '</div>';
        });
    });

    html += '</div>';

    // Légende (inchangée)
    html += `
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(45deg, var(--greta-success), #58d68d);"></div>
                <span>Confirmé</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(45deg, var(--greta-warning), #f7dc6f);"></div>
                <span>En attente</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(45deg, var(--greta-accent), #ec7063);"></div>
                <span>Annulé</span>
            </div>
        </div>
    `;

    return html;
}

function loadReservations() {
    const materialSelect = document.getElementById('materialSelect');
    const selectedMaterial = materialSelect.value;
    const calendarContent = document.getElementById('calendarContent');

    if (!selectedMaterial) {
        calendarContent.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-laptop"></i>
                <h4>Sélectionnez un matériel</h4>
                <p>Choisissez un équipement dans la liste ci-dessus pour voir ses réservations.</p>
            </div>
        `;
        return;
    }

    fetch('/API/dashboard/get-reservations', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ selectedMaterial })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendarContent.innerHTML = generateCalendarGrid(data.reservations);
            } else {
                calendarContent.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h4>Aucune réservation</h4>
                        <p>Aucune réservation trouvée pour ce matériel cette semaine.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des réservations :', error);
            calendarContent.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Erreur de connexion</h4>
                    <p>Impossible de récupérer les réservations. Veuillez réessayer plus tard.</p>
                </div>
            `;
        });
}


function showReservationDetails(title, user, status, quantity, date) {
    const statusText = {
        'confirmed': 'Confirmé',
        'pending': 'En attente',
        'cancelled': 'Annulé'
    };

    // Formatte la date pour l'affichage si nécessaire
    const displayDate = new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    alert(`Détails de la réservation:\n\nFormation: ${title}\nProfesseur: ${user}\nQuantité: ${quantity}\nStatut: ${statusText[status]}\nDate: ${displayDate}`);
}

function previousWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    updateWeekDisplay();
    loadReservations();
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    updateWeekDisplay();
    loadReservations();
}

function goToCurrentWeek() {
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1);
    updateWeekDisplay();
    loadReservations();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function () {
    updateWeekDisplay();

});