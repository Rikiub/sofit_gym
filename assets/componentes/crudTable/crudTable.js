import { API_PREFIX, fetchApi } from "/assets/js/api.js";
import { createGrid } from "/assets/js/grid.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

const MODAL_EVENT = "open-modal";

/**
 * @param {{
 * endpoint: string,
 * columns: array<string|object>,
 * fieldMap: (item: object) => array<string|int>,
 * gridOptions: object,
 * id?: string,
 * }}
 */
export function crudTableComponent({
    endpoint,
    columns,
    fieldMap = (item) => item,
    gridOptions,
    id: componentId = null,
}) {
    return {
        grid: null,

        init() {
            const serverUrl = `${API_PREFIX}/${endpoint}`;

            this.grid = createGrid({
                columns: columns,
                server: {
                    url: serverUrl,
                    then: (data) => data.map((item) => fieldMap(item)),
                },
                crud: {
                    onAdd: () => this.$dispatch(modalEvent, { mode: "add" }),
                    onEdit: (dataId) => {
                        this.$dispatch(MODAL_EVENT, {
                            id: componentId,
                            dataId,
                            mode: "edit",
                        });
                    },
                    onDelete: (dataId) => {
                        this.$dispatch(MODAL_EVENT, {
                            id: componentId,
                            dataId,
                            mode: "delete",
                        });
                    },
                },
                ...gridOptions,
            });
            this.grid.render(this.$refs.table);
        },

        handleFormSuccess({ id = null, action = null }) {
            if (id !== componentId) return;
            this.refreshGrid();
        },

        refreshGrid() {
            this.grid.forceRender();
        },
    };
}

Alpine.data("crudTable", crudTableComponent);
