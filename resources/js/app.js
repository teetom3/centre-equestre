import './bootstrap';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'fr',
        selectable: true,
        dateClick: function(info) {
            selectDate(info.dateStr);
        }
    });

    calendar.render();
});

let selectedDate = null;

function selectDate(dateStr) {
    selectedDate = dateStr;
    document.getElementById('selected-date').textContent = new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('selected-date-input').value = selectedDate;
    document.getElementById('prestations-section').style.display = 'block';
}

function ajouterPrestation() {
    const prestationsList = document.getElementById('prestations-list');
    const newPrestationSelect = document.createElement('div');
    newPrestationSelect.classList.add('form-group');
    newPrestationSelect.innerHTML = `
        <select name="prestations[]" class="form-control mb-2">
            <option value="">-- Choisir une prestation --</option>
            ${prestations.map(prestation => `<option value="${prestation.id}">${prestation.nom}</option>`).join('')}
        </select>
    `;
    prestationsList.appendChild(newPrestationSelect);
}

