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
            const currentDayDate = new Date(currentDayDateString);
            
            const dayReservations = reservations.filter(r => {
                const reservationStartDate = new Date(r.startDate);
                const reservationEndDate = new Date(r.endDate);
                
                // Set times to midnight for date comparison only
                const resStartDay = new Date(reservationStartDate.getFullYear(), reservationStartDate.getMonth(), reservationStartDate.getDate());
                const resEndDay = new Date(reservationEndDate.getFullYear(), reservationEndDate.getMonth(), reservationEndDate.getDate());
                const currentGridDay = new Date(currentDayDate.getFullYear(), currentDayDate.getMonth(), currentDayDate.getDate());

                // Check if the current day falls within the reservation period
                const isWithinReservationPeriod = (currentGridDay >= resStartDay && currentGridDay <= resEndDay);

                if (!isWithinReservationPeriod) {
                    return false;
                }

                // If it's the start day of the reservation, apply startTime constraint
                if (currentGridDay.getTime() === resStartDay.getTime() && currentGridDay.getTime() === resEndDay.getTime()) {
                    // Single day reservation
                    return r.startTime <= time && r.endTime > time; // Use > for end time to include the last hour block
                } else if (currentGridDay.getTime() === resStartDay.getTime()) {
                    // Start day of a multi-day reservation
                    return r.startTime <= time;
                } else if (currentGridDay.getTime() === resEndDay.getTime()) {
                    // End day of a multi-day reservation
                    return r.endTime > time; // Use > for end time to include the last hour block
                } else {
                    // Any full day in between start and end date
                    return true;
                }
            });

            html += '<div class="calendar-cell">';
            dayReservations.forEach(reservation => {
                html += `
                    <div class="reservation ${reservation.status}" onclick="showReservationDetails(
                        '${reservation.user}',
                        '${reservation.status}',
                        ${reservation.quantity},
                        '${reservation.startDate} ${reservation.startTime}',
                        '${reservation.endDate} ${reservation.endTime}'
                    )">
                        <div class="reservation-title">${reservation.user}</div>
                        <div class="reservation-user">Nombre réservé : ${reservation.quantity}</div>
                    </div>
                `;
            });
            html += '</div>';
        });
    });

    html += '</div>';

    return html;
}

// Les autres fonctions (loadReservations, showReservationDetails, previousWeek, nextWeek, goToCurrentWeek, Initialisation) restent les mêmes
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