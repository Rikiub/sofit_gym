import { Grid, PluginPosition, h } from "gridjs";

/** Crea una tabla de datos adaptada para CRUDs */
export function createGrid(config) {
    config.columns?.push(crudButtons(config.onEdit, config.onDelete));

    const grid = new Grid({
        language: {
            search: {
                placeholder: "Buscar..."
            },
            sort: {
                sortAsc: "Sortear columna ascendentemente",
                sortDesc: "Sortear columna descendentemente"
            },
            pagination: {
                previous: "Anterior",
                next: "Siguiente",
                navigate: (page, pages) => `Pagina ${page} de ${pages}`,
                page: (page) => `Pagina ${page}`,
                showing: "Mostrando",
                results: "Resultados",
                of: "de",
                to: "a"
            },
            loading: "Cargando...",
            noRecordsFound: "Sin resultados",
            error: "Un error ha ocurrido mientras se obtenian los datos"
        },
        sort: true,
        search: true,
        pagination: {
            limit: 20,
            summary: true,
        },
        ...config
    });
    grid.plugin.add({
        id: "add",
        component: () => addButton(config.onAdd),
        position: PluginPosition.Header,
    })
    return grid;
}

function addButton(callback) {
    return h("button", {
        className: "crud-actions-add",
        "data-tooltip": "Crear",
        "data-placement": "left",
        onClick: callback
    }, h("i", { className: "fa-solid fa-square-plus" }));
}

export function crudButtons(onEdit, onDelete) {
    return {
        name: "Acciones",
        width: "150px",
        sort: false,
        data: () => null,
        formatter: (cell, row) => {
            return h("div", { className: "crud-actions" }, [
                h("button", {
                    className: "button-edit",
                    "data-tooltip": "Editar",
                    onClick: () => onEdit(row.cells[0].data),
                }, h("i", { className: "fa-solid fa-pen-to-square" })),
                h("button", {
                    className: "button-delete",
                    "data-tooltip": "Eliminar",
                    onClick: () => onDelete(row.cells[0].data),
                }, h("i", { className: "fa-solid fa-trash-can" }))
            ]);
        }
    }
}