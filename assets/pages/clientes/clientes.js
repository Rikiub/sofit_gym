import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";
import { extractDate } from "@/js/helpers.js";
import { fetchApi } from "@/js/api.js";
import Alpine from "alpinejs";
import { h } from "gridjs";

const CLIENTES_PAGE = "clientes";

Alpine.data("crudTableClientes", () =>
    crudTableComponent({
        page: CLIENTES_PAGE,
        action: "getClientes",
        columns: [
            {
                name: "Cedula",
                formatter: (cell, row) => {
                    const cedula = row.cells[0].data;
                    return h(
                        "a",
                        { href: `?page=clientesItem&id=${cedula}` },
                        cedula,
                    );
                },
            },
            "Nombre",
            "Apellido",
            "Correo",
            "Telefono",
        ],
        fieldMap: (item) => [
            item.cedula,
            item.nombre,
            item.apellido,
            item.correo,
            item.telefono,
        ],
    }));

Alpine.data("modalFormClientes", (isSinglePage = false) => ({
    ...modalFormComponent({
        page: CLIENTES_PAGE,
        actions: {
            onAdd: "insertCliente",
            onEditFind: "findCliente",
            onEdit: "updateCliente",
            onDelete: "deleteCliente",
        },
        transformEditData: (data) => {
            data.fecha_nacimiento = extractDate(data.fecha_nacimiento);
            data.membresia.fecha_inicio = extractDate(
                data.membresia.fecha_inicio,
            );
            data.membresia.fecha_fin = extractDate(data.membresia.fecha_fin);
            return data;
        },
        editDisableFields: ["cedula"],
        afterSubmit: (mode) => {
            if (isSinglePage) {
                if (mode === "edit") return location.reload();
                if (mode === "delete") {
                    location.href = `?pagina=${CLIENTES_PAGE}`;
                    return;
                }
            }
        },
    }),

    /** @param {HTMLInputElement} input */
    async validateCedula(input) {
        this.checkValidity(input);

        if (this.mode === "add") {
            let cliente = null;

            try {
                cliente = await fetchApi({
                    page: this.page,
                    action: this.actions.onEditFind,
                    id: input.value,
                });
            } catch {}

            if (cliente) {
                this.setInputValidity(input, false, "El cliente ya existe");
            }
        }
    },
}));
