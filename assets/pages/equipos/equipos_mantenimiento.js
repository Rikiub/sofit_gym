import Alpine from "alpinejs";
import { fetchApi } from "@/js/api.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";
import { extractDate } from "@/js/helpers.js";

const PAGE_EQUIPOS = "equipos";

Alpine.data("mainData", () => ({
    equipos: {},

    async init() {
        this.equipos = await fetchApi({
            page: PAGE_EQUIPOS,
            action: "getAllEquipos",
        });
    }
}));

const PAGE_MANTENIMIENTO = "equiposMantenimiento";

Alpine.data("crudMantenimiento", () => (
    crudTableComponent({
        params: {
            page: PAGE_MANTENIMIENTO,
            action: "getAll",
        },
        columns: [
            {
                name: "id",
                hidden: true,
            },
            "Codigo Equipo",
            {
                name: "Fecha",
                formatter: (cell) => new Date(cell).toLocaleDateString("en-US")
            },
            "Tipo",
            "Descripción",
            "Costo",
            "Tecnico",
        ],
        fieldMap: (item) => [
            item.id,
            item.codigo_equipo,
            item.fecha,
            item.tipo,
            item.descripcion,
            item.costo,
            item.tecnico,
        ],
    })))

Alpine.data("modalMantenimiento", () => (
    modalFormComponent({
        page: PAGE_MANTENIMIENTO,
        actions: {
            onAdd: "insert",
            onEdit: "update",
            onEditFind: "find",
            onDelete: "delete",
        },
        transformEditData: (item) => {
            item.fecha = extractDate(item.fecha);
            return item;
        }
    })))