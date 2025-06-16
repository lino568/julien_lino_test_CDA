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
    const times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

    let html = '<div class="calendar-grid">';
    html += '<div class="day-header"></div>';

    const currentWeekDaysDates = [];
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        currentWeekDaysDates.push(date.toISOString().split('T')[0]);
        html += `
            <div class="day-header">
                <div class="day-name">${day}</div>
                <div class="day-number">${date.getDate()}</div>
            </div>
        `;
    });

    times.forEach(time => {
        html += `<div class="time-slot">${time}</div>`;

        currentWeekDaysDates.forEach(currentDayDateString => {
            // Filtre les réservations de cette journée et de cette heure
            const dayReservations = reservations.filter(r =>
                (new Date(r.startDate) <= new Date(currentDayDateString) && new Date(r.endDate) >= new Date(currentDayDateString)) &&
                r.startTime <= time && r.endTime >= time
            );

            html += '<div class="calendar-cell">';
            dayReservations.forEach(reservation => {
                html += `
                    <div class="reservation ${reservation.status}" onclick="showReservationDetails(
                        '${reservation.user}',  // Utiliser 'user' comme titre
                        '${reservation.status}',
                        ${reservation.quantity},
                        '${reservation.startDate} ${reservation.startTime}', 
                        '${reservation.endDate} ${reservation.endTime}' // Afficher la plage horaire complète
                    )">
                        <div class="reservation-title">${reservation.user}</div> <!-- Utilisation de 'user' comme titre -->
                        <div class="reservation-user">Nombre réservé : ${reservation.quantity}</div>
                    </div>
                `;
            });
            html += '</div>';
        });
    });

    html += '</div>';

    // Légende
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


function showReservationDetails(title, status, quantity, startDate, endDate) {
    const statusText = {
        'en_cours': 'Confirmé',
        'pending': 'En attente',
        'annulé': 'Annulé'
    };

    // Formatte les dates pour l'affichage
    const displayStartDate = new Date(startDate).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const displayEndDate = new Date(endDate).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Formatte les heures pour l'affichage
    const displayStartTime = new Date('1970-01-01T' + startDate.split(' ')[1]).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    const displayEndTime = new Date('1970-01-01T' + endDate.split(' ')[1]).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

    alert(`Détails de la réservation:\n\nProfesseur: ${title}\nQuantité: ${quantity}\nStatut: ${statusText[status]}\nDate: ${displayStartDate} ${displayStartTime} - ${displayEndDate} ${displayEndTime}`);
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