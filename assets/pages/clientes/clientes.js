import { crudTableComponent } from "@/components/crudTable/crudTable.js";
import { modalFormComponent } from "@/components/modalForm/modalForm.js";
import { extractDate } from "@/js/helpers.js";
import { fetchApi } from "@/js/api.js";
import Alpine from "alpinejs";
import { h } from "gridjs";

// CLIENTES
const CLIENTES_PAGE = "clientes";
const clientesId = "clientes";

Alpine.data("crudClientes", () =>
    crudTableComponent({
        id: clientesId,
        params: {
            page: CLIENTES_PAGE,
            action: "getClientes",
        },
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

Alpine.data("modalClientes", (isSinglePage = false) => ({
    ...modalFormComponent({
        id: clientesId,
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
                if (mode === "delete") {
                    location.href = `?page=${CLIENTES_PAGE}`;
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

// CLIENTES ITEM
const clientesItemPage = "ClientesItem";

Alpine.data("clienteInfo", () => ({
    id: clientesId,
    cliente: null,

    async init() {
        await this.refresh();
    },

    async handleFormSuccess({ id }) {
        if (id === this.id) {
            await this.refresh();
        }
    },

    async refresh() {
        this.cliente = await fetchApi({
            page: CLIENTES_PAGE,
            action: "findCliente",
            id: new URLSearchParams(location.search).get("id"),
        });
    },

    nombreCompleto() {
        if (!this.cliente) return "Cargando...";
        return `${this.cliente.nombre} ${this.cliente.apellido}`;
    },

    setText(value) {
        return value ?? "Desconocido";
    },

    onlyDate(value) {
        if (!value) return value;

        const date = new Date(value);
        return date.toLocaleDateString("en-US");
    }
}));

// SEGUIMIENTO FISICO
const idSegFisico = "seg_fisico";

Alpine.data("crudSegFisico", () =>
    crudTableComponent({
        id: idSegFisico,
        params: {
            page: clientesItemPage,
            action: "getSegFisicoByCliente",
            id: new URLSearchParams(location.search).get("id")
        },
        columns: [
            {
                name: "ID Seguimiento",
                hidden: true,
            },
            {
                name: "Cedula Cliente",
                hidden: true,
            },
            {
                name: "Fecha",
                formatter: (cell) => new Date(cell).toLocaleDateString("en-US")
            },
            "Altura",
            "Peso",
            "Cintura",
            "Cadera",
            "Pecho",
            "Muslo",
            "Hombros",
        ],
        fieldMap: (item) => [
            item.id_seguimiento,
            item.cedula_cliente,
            item.fecha,
            item.altura_cm,
            item.peso_cm,
            item.cintura_cm,
            item.cadera_cm,
            item.pecho_cm,
            item.muslo_cm,
            item.hombros_cm,
        ],
        gridOptions: {
            search: false,
        },
        crudButtons: {
            onEdit: null
        },
    }));

Alpine.data("modalSegFisico", () => modalFormComponent({
    id: idSegFisico,
    page: clientesItemPage,
    actions: {
        onAdd: "insertSegFisico",
        onEdit: "updateSegFisico",
        onDelete: "deleteSegFisico",
    },
    extraPostBody: {
        cedula_cliente: new URLSearchParams(location.search).get("id"),
    }
}));


// SEGUIMIENTO NUTRICIONAL
const idSegNutricional = "seg_nutricional";

Alpine.data("crudSegNutricional", () =>
    crudTableComponent({
        id: idSegNutricional,
        params: {
            page: clientesItemPage,
            action: "getSegNutricionalByCliente",
            id: new URLSearchParams(location.search).get("id")
        },
        columns: [
            {
                name: "ID Seguimiento",
                hidden: true,
            },
            {
                name: "Cedula Cliente",
                hidden: true,
            },
            {
                name: "Fecha",
                formatter: (cell) => new Date(cell).toLocaleDateString("en-US")
            },
            "Proteinas (g)",
            "Carbohidratos (g)",
            "Grasas (g)",
            "Calorias Diarias",
        ],
        fieldMap: (item) => [
            item.id_seguimiento,
            item.cedula_cliente,
            item.fecha,
            item.proteinas_g,
            item.carbohidratos_g,
            item.grasas_g,
            item.calorias_diarias,
        ],
        gridOptions: {
            search: false,
        },
        crudButtons: {
            onEdit: null
        },
    }));

Alpine.data("modalSegNutricional", () => modalFormComponent({
    id: idSegNutricional,
    page: clientesItemPage,
    actions: {
        onAdd: "insertSegNutricional",
        onEdit: "updateSegNutricional",
        onDelete: "deleteSegNutricional",
    },
    extraPostBody: {
        cedula_cliente: new URLSearchParams(location.search).get("id"),
    }
}));
