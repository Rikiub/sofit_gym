import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import esLocale from '@fullcalendar/core/locales/es';

/**
 * @param {HTMLElement} element
 * @param {{
 * onAdd () => void,
 * [key: string]
 * }} options
 * @returns {Calendar}
 */
export function createCalendar(
    element,
    options,
) {
    const calendar = new Calendar(element, {
        plugins: [dayGridPlugin, bootstrap5Plugin],
        themeSystem: 'bootstrap5',
        locale: esLocale,
        headerToolbar: {
            left: 'title',
            center: '',
            right: "prev,next addEvent today",
        },
        datesSet: () => {
            // Colocar iconos de Font Awesome
            const prevIcon = element.querySelector('.fc-prev-button span');
            if (prevIcon) prevIcon.className = 'fa-solid fa-chevron-left';

            const nextIcon = element.querySelector('.fc-next-button span');
            if (nextIcon) nextIcon.className = 'fa-solid fa-chevron-right';

            const prevYearIcon = element.querySelector('.fc-prevYear-button span');
            if (prevYearIcon) prevYearIcon.className = 'fa-solid fa-angles-left';

            const nextYearIcon = element.querySelector('.fc-nextYear-button span');
            if (nextYearIcon) nextYearIcon.className = 'fa-solid fa-angles-right';
        },
        eventClassNames: (info) => {
            const start = new Date(info.event.start).getDate();
            const now = new Date().getDate();

            // Si el evento ya ha pasado, difuminarlo
            if (start < now) {
                return ['opacity-50', 'fc-event-past']; 
            }
            
            return [];
        },
        customButtons: {
            addEvent: {
                text: 'Agregar evento',
                hint: 'Crea un nuevo evento',
                click: options.onAdd,
            },
        },
        ...options,
    });
    calendar.render();
    return calendar;
}
