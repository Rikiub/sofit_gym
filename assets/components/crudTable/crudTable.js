import { fetchApi } from "@/js/api.js";
import { createGrid } from "@/js/grid.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

const MODAL_EVENT = "open-modal";

/**
 * @param {{
 * page: string,
 * action: string,
 * columns: array<string|object>,
 * fieldMap: (item: object) => array<string|int>,
 * gridOptions: object,
 * id?: string,
 * }}
 */
export function crudTableComponent({
    page,
    action,
    columns,
    fieldMap = (item) => item,
    gridOptions,
    id: componentId = null,
}) {
    return {
        grid: null,

        init() {
            const query = new URLSearchParams({
                page: page,
                action: action,
            });

            this.grid = createGrid({
                columns: columns,
                server: {
                    url: `?${query.toString()}`,
                    then: (data) => data.map((item) => fieldMap(item)),
                },
                crud: {
                    onAdd: () => this.$dispatch(MODAL_EVENT, { mode: "add" }),
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
