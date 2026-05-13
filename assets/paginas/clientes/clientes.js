import { crudTableComponent } from "/assets/componentes/crudTable/crudTable.js";
import { modalFormComponent } from "/assets/componentes/modalForm/modalForm.js";
import { extractDate } from "/assets/js/helpers.js";
import { fetchApi } from "/assets/js/api.js";
import Alpine from "alpinejs";

const CLIENTES = {
    endpoint: "clientes",
    id: crypto.randomUUID(),
};

Alpine.data("crudTable", () =>
    crudTableComponent({
        ...CLIENTES,
        columns: [
            "Cedula",
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

Alpine.data("modalForm", () => ({
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
