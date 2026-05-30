import Alpine from "alpinejs";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { toIsoDate } from "@/js/dates.js";

const PAGE = "trabajadores";

Alpine.data("crudTable", () => (
    crudTableComponent({
        params: {
            page: PAGE,
            action: "query",
        },
        columns: [
            "Cedula",
            "Nombre",
            "Apellido",
            {
                name: "Salario",
                formatter: (cell, row) => `\$${cell}`,
            },
            "Rol",
        ],
    })));

Alpine.data("modalForm", () => (
    modalFormComponent({
        page: PAGE,
        actions: {
            onAdd: "insert",
            onEdit: "update",
            onEditFind: "find",
            onDelete: "delete",
        },
        editDisableFields: ["cedula", "fecha_contratacion"],
        transformEditData: (item) => {
            item.fecha_nacimiento = toIsoDate(item.fecha_nacimiento);
            item.fecha_contratacion = toIsoDate(item.fecha_contratacion);
            return item;
        }
    })));