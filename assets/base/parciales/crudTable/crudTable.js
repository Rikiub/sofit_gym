import { API_PREFIX, fetchApi } from "/assets/js/api.js";
import { createGrid } from "/assets/js/grid.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

/** @param {{
     * endpoint: string,
     * columns: array<sttring>,
     * fieldMap: array<string>,
 * }} options
 */
export function crudTableComponent(options) {
    return {
        endpoint: options.endpoint,
        columns: options.columns ?? [],
        fieldMap: options.fieldMap ?? [],
        grid: null,

        init() {
            const hasNested = (obj, path) => path.split('.').reduce((o, k) => o?.[k], obj) !== undefined;
            const serverUrl = `${API_PREFIX}/${this.endpoint}`
            const modalEvent = "open-modal";

            this.grid = createGrid({
                columns: this.columns,
                server: {
                    url: serverUrl,
                    then: data => data.map(item => {
                        let data = [];

                        for (const field of this.fieldMap) {
                            if (item[field]) {
                                data.push(item[field]);
                            }
                        }

                        return data;
                    }),
                },
                onAdd: () => this.$dispatch(modalEvent, { method: "POST" }),
                onEdit: (id) => this.$dispatch(modalEvent, { method: "PUT", id }),
                onDelete: (id) => this.$dispatch(modalEvent, { method: "DELETE", id }),
            });
            this.grid.render(this.$refs.table);
        },

        refreshGrid() {
            this.grid.forceRender();
        }
    };
}

Alpine.data("crudTable", crudTableComponent);