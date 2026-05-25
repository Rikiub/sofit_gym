import Alpine from "alpinejs";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { extractDate } from "@/js/helpers.js";

const PAGE = "trabajadores";

Alpine.data("crudTable", () => (
    crudTableComponent({
        params: {
            page: PAGE,
            action: "getAll",
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
        editDisableFields: ["cedula"],
        transformEditData: (item) => {
            item.fecha_nacimiento = extractDate(item.fecha_nacimiento);
            item.fecha_contratacion = extractDate(item.fecha_contratacion);
            return item;
        }
    })));