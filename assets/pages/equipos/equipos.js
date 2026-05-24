import Alpine from "alpinejs";
import { fetchApi } from "@/js/api.js";
import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";

Alpine.data("crudEquipos", () => (
    crudTableComponent({
        params: {
            page: "equipos",
            action: "getAllEquipos",
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
            onAdd: "insertEquipo",
            onEdit: "updateEquipo",
            onEditFind: "findEquipo",
            onDelete: "deleteEquipo",
        },
        elementName: "Equipo",
        editDisableFields: ["codigo"],
    })))