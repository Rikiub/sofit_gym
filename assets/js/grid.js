import { Grid, h } from "gridjs";

/** Crear y obtener una instancia Grid
 * @param {object} config 
 */
export function createGrid(config) {
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
    return grid;
}

export function crudButtons(onModificar, onEliminar) {
    return {
        name: "Acciones",
        formatter: (cell, row) => {
            return h("div", { className: "actions" }, [
                h("button", {
                    className: "boton-edit",
                    onClick: () => onModificar(row.cells[0].data),
                }, "Editar"),
                h("button", {
                    className: "boton-delete",
                    onClick: () => onEliminar(row.cells[0].data),
                }, "Eliminar")
            ]);
        }
    }
}