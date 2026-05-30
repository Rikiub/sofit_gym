import { createGrid } from "@/js/grid.js";
import { openModal } from "@/components/modalForm/modalForm.js";
import Alpine from "alpinejs";

/**
 * @param {{
 * params: object,
 * columns: array<string|object>,
 * fieldMap: (item: object) => array<string|int>,
 * gridOptions: object?,
 * id?: string,
 * }}
 */
export function crudTableComponent({
    params,
    columns,
    fieldMap = (item) => item,
    gridOptions = {},
    id: componentId = null,
}) {
    return {
        grid: null,

        init() {
            const { crudButtons = {}, ...restOptions } = gridOptions;
            const query = new URLSearchParams(params);

            this.grid = createGrid({
                columns: columns,
                server: {
                    url: `?${query.toString()}`,
                    then: (data) => data.map((item) => fieldMap(item)),
                },
                crud: {
                    onAdd: () => openModal(this, {
                            id: componentId,
                            mode: "add"
                        }),
                    onEdit: (dataId) => {
                        openModal(this, {
                            id: componentId,
                            dataId,
                            mode: "edit",
                        });
                    },
                    onDelete: (dataId) => {
                        openModal(this, {
                            id: componentId,
                            dataId,
                            mode: "delete",
                        });
                    },
                    ...crudButtons
                },
                ...restOptions,
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
