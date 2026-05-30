import Alpine from "alpinejs";
import { fetchApi } from "@/js/api.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";

Alpine.data("crudEquipos", () => (
    crudTableComponent({
        params: {
            page: "equipos",
            action: "query",
        },
        columns: [
            "Codigo",
            "Nombre",
            "Tipo",
            "Estado",
            "Ubicación",
        ],
    })))

Alpine.data("modalEquipos", () => (
    modalFormComponent({
        page: "equipos",
        actions: {
            onAdd: "insert",
            onEdit: "update",
            onEditFind: "find",
            onDelete: "delete",
        },
        elementName: "Equipo",
        editDisableFields: ["codigo"],
    })))