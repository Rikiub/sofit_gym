import { crudTableComponent } from "/assets/base/parciales/crudTable/crudTable.js";
import { modalFormComponent } from "/assets/base/parciales/modalForm/modalForm.js";
import { fetchApi } from "/assets/js/api.js";
import Alpine from "alpinejs";

const ENDPOINT = "clientes";

Alpine.data("crudClientes", () => ({
    ...crudTableComponent({
        endpoint: ENDPOINT,
        columns: [
            "Cedula",
            "Nombre",
            "Apellido",
            "Correo",
            "Telefono",
        ],
        fieldMap: ["cedula", "nombre", "apellido", "correo", "telefono", "direccion"],
        transformEditData: (data) => {
            const onlyDate = (value) => value?.split('T')[0];
            data.fecha_nacimiento = onlyDate(data.fecha_nacimiento);
            data.membresia.fecha_inicio = onlyDate(data.membresia.fecha_inicio);
            data.membresia.fecha_fin = onlyDate(data.membresia.fecha_fin);
            return data;
        },
    }),
    ...modalFormComponent(),
    async validarCedula(input) {
        this.checkValidity(input);

        if (this.method === "POST") {
            let cliente = null;

            try {
                cliente = await fetchApi(`/${ENDPOINT}/${input.value}`);
            } catch { }

            if (cliente) this.setInputValidity(input, false, "El cliente ya existe");
        }
    }
}));

Alpine.data("customForm", () => ({
    ...modalFormComponent({ endpoint: ENDPOINT }),
    async validarCedula(input) {
        this.checkValidity(input);

        if (this.method === "POST") {
            let cliente = null;

            try {
                cliente = await fetchApi(`/${ENDPOINT}/${input.value}`);
            } catch { }

            if (cliente) this.setInputValidity(input, false, "El cliente ya existe");
        }
    }
}));
