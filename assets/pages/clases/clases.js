import Alpine from "alpinejs";
import { modalFormComponent, openModal } from "@/components/modalForm/modalForm.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { extractDate, extractDateTimeLocal, extractTime } from "@/js/helpers.js";
import { createCalendar } from "@/js/calendar.js";
import { fetchApi } from "@/js/api.js";
import { toIsoDateTime } from "@/js/dates.js";
import { format } from "date-fns";

const PAGE = "clases";

Alpine.data("crudTable", () => (
    crudTableComponent({
        id: PAGE,
        params: {
            page: PAGE,
            action: "getAll",
        },
        columns: [
            {
                id: "id_clase",
                hidden: true,
            },
            {
                id: "cedula_trabajador",
                name: "Instructor",
            },
            "Nombre",
            {
                id: "descripcion",
                name: "Descripción",
            },
            "Rol",
        ],
    })));

Alpine.data("modalForm", () => (
    modalFormComponent({
        id: PAGE,
        page: PAGE,
        actions: {
            onAdd: "insert",
            onEdit: "update",
            onEditFind: "find",
            onDelete: "delete",
        },
        elementName: "Clase",
        prepareAddData: {
            fecha_inicio: toIsoDateTime(new Date()),
        },
        transformEditData: (item) => {
            item.fecha_inicio = toIsoDateTime(item.fecha_inicio);
            item.fecha_fin = toIsoDateTime(item.fecha_fin);
            return item;
        }
    })));

Alpine.data("calendar", () => ({
    calendar: null,
    popover: null,
    popoverTimeout: null,
    popoverEventId: null,

    init() {
        this.calendar = createCalendar(this.$el, {
            onAdd: () => openModal(this, { mode: "add", id: PAGE }),
            events: "?page=clases&action=getAll",
            eventDataTransform: (item) => ({
                id: item.id_clase,
                title: item.nombre,
                start: item.fecha_inicio,
                end: item.fecha_fin,
                extendedProps: {
                    ...item,
                    descripcion: item.descripcion || "Sin descripción",
                }
            }),
            eventDidMount: (info) => this.bindEventHover(info),
        });

        // El click global se registra una sola vez aquí
        document.addEventListener('click', (e) => {
            if (this.popover && !e.target.closest('.popover') && !e.target.closest('.fc-event')) {
                this.destroyPopover();
            }
        });
    },

    // Escuchadores del elemento del calendario
    bindEventHover(info) {
        const el = info.el;
        el.addEventListener('mouseenter', () => this.showPopover(info));
        el.addEventListener('mouseleave', () => this.startHideTimeout());
        el.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.showPopover(info); 
        });
    },

    destroyPopover() {
        if (this.popover) {
            this.popover.dispose();
            this.popover = null;
            this.popoverEventId = null;
        }
        if (this.popoverTimeout) {
            clearTimeout(this.popoverTimeout);
            this.popoverTimeout = null;
        }
    },

    startHideTimeout() {
        if (this.popoverTimeout) clearTimeout(this.popoverTimeout);
        this.popoverTimeout = setTimeout(() => this.destroyPopover(), 200);
    },

    showPopover(info) {
        const event = info.event;

        if (this.popoverTimeout) clearTimeout(this.popoverTimeout);
        if (this.popoverEventId === event.id) return;
        this.popoverEventId = event.id;

        // Si hay un popover abierto de otra clase, lo destruimos inmediatamente
        if (this.popover) this.destroyPopover();

        // Inicializar Popover
        this.popover = new bootstrap.Popover(info.el, {
            title: `<span class="fw-bold text-secondary small">${event.title}</span>`,
            content: this.getPopoverTemplate(event.extendedProps),
            html: true,
            trigger: 'manual',
            placement: 'auto',
            container: 'body',
            sanitize: false
        });

        this.popover.show();

        // Configurar acciones internas cuando el popover aparece en el DOM
        info.el.addEventListener('shown.bs.popover', () => {
            const openPopoverEl = document.querySelector('.popover:last-child');
            if (!openPopoverEl) return;

            // Mantener abierto si el mouse entra al popover
            openPopoverEl.addEventListener('mouseenter', () => {
                if (this.popoverTimeout) clearTimeout(this.popoverTimeout);
            });
            openPopoverEl.addEventListener('mouseleave', () => this.startHideTimeout());

            // Cliks de acciones
            openPopoverEl.querySelector('.btn-ctx-edit').onclick = () => {
                openModal(this, { mode: "edit", dataId: event.id, id: PAGE });
                this.destroyPopover();
            };

            openPopoverEl.querySelector('.btn-ctx-delete').onclick = () => {
                openModal(this, { mode: "delete", dataId: event.id, id: PAGE });
                this.destroyPopover();
            };
        }, { once: true });
    },

    // Plantilla HTML del Popover
    getPopoverTemplate(props) {
        const startTime = format(props.fecha_inicio, 'hh:mm a');
        const endTime = format(props.fecha_fin, 'hh:mm a');

        return `
            <div class="ctx-popover-card p-1" style="min-width: 250px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-secondary fw-semibold">
                        <i class="fa-solid fa-clock text-muted me-1"></i> ${startTime} / ${endTime}
                    </span>

                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-users text-primary me-1"></i> 
                        <strong>${props.cupos_ocupados || 0}</strong>/${props.capacidad_maxima || '∞'}
                    </span>
                </div>

                <div class="p-2 rounded mb-3 text-muted border-start border-primary border-3" 
                    style="background-color: #f8f9fa; font-size: 0.8rem; line-height: 1.4;">
                    ${props.descripcion || '<em>Sin descripción disponible.</em>'}
                </div>

                <hr class="my-2 opacity-10">

                <div class="d-flex gap-2 mt-2">
                    <button class="btn-ctx-edit btn btn-sm btn-light border text-primary flex-grow-1 fw-semibold d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-pen me-1" style="font-size: 0.75rem;"></i> Editar
                    </button>

                    <button class="btn-ctx-delete btn btn-sm btn-outline-danger flex-grow-1 fw-semibold d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-trash me-1" style="font-size: 0.75rem;"></i> Eliminar
                    </button>
                </div>
            </div>
        `;
    },

    handleFormSucess({ id = null }) {
        if (id === PAGE) {
            this.calendar.refetchEvents();
        }
    },
}));