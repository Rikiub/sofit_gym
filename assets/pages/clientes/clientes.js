import { crudTableComponent } from "/assets/components/crudTable/crudTable.js";
import { modalFormComponent } from "/assets/components/modalForm/modalForm.js";
import { extractDate } from "/assets/js/helpers.js";
import { fetchApi } from "/assets/js/api.js";
import Alpine from "alpinejs";
import { h } from "gridjs";

const CLIENTES = {
    endpoint: "clientes",
    id: null,
};

Alpine.data("crudTableClientes", () =>
    crudTableComponent({
        ...CLIENTES,
        columns: [
            {
                name: "Cedula",
                formatter: (cell, row) => {
                    const cedula = row.cells[0].data;
                    return h("a", { href: `/clientes/${cedula}` }, cedula);
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
        ...CLIENTES,
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
                    location.href = "/clientes";
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
                cliente = await fetchApi(`/${this.endpoint}/${input.value}`);
            } catch {}

            if (cliente) {
                this.setInputValidity(input, false, "El cliente ya existe");
            }
        }
    },
}));
