import { API_PREFIX, fetchApi } from "/assets/js/api.js";
import { createGrid } from "/assets/js/grid.js";
import FormDataJson from "form-data-json";

/** @param {{
     * endpoint: string,
     * columns: array<string>,
     * dataRowMapper: (item: object) => object,
     * transformEditData: (data: object) => object,
 * }} options
 */
export function crudTableComponent(options) {
    const {
        endpoint,
        columns,
        dataRowMapper = (item) => item,
        transformEditData = (data) => data,
        serverUrl = `${API_PREFIX}/${endpoint}`,
    } = options;

    return {
        method: "POST",
        id: "",

        loading: false,
        errors: {},

        init() {
            this.grid = createGrid({
                columns,
                server: {
                    url: serverUrl,
                    then: data => data.map(dataRowMapper),
                },
                onAdd: this.onAdd.bind(this),
                onEdit: this.onEdit.bind(this),
                onDelete: this.onDelete.bind(this),
            });
            this.grid.render(this.$refs.table);
        },

        async handleSubmit() {
            let valid = true;

            /** @type {HTMLFormElement} */
            const form = this.$refs.form;

            /** Validar formulario */
            for (const input of form.elements) {
                if (input.checkValidity()) {
                    this.setInputValidity(input, true);
                } else {
                    this.setInputValidity(input, false);
                    valid = false;
                }
            }

            if (this.method === "DELETE" || valid) {
                let body = null;
                let url = `/${endpoint}`;
                this.loading = true;

                if (this.method == "PUT" || this.method == "DELETE") {
                    url = `${url}/${this.id}`;
                }
                if (this.method == "PUT" || this.method == "POST") {
                    body = FormDataJson.toJson(this.$refs.form, { skipEmpty: true });
                }

                await fetchApi(url, { method: this.method, body: body });

                this.loading = false;
                this.$refs.modal.close();
                this.grid.forceRender();
            } else {
                console.log("Invalid input, POST canceled");
            }
        },

        async onAdd() {
            this.clearForm();
            this.method = "POST";
            this.$refs.modal.showModal();
        },
        async onEdit(id) {
            this.clearForm();

            this.method = "PUT";
            this.id = id;

            let data = await fetchApi(`/${endpoint}/${this.id}`);
            data = transformEditData(data);

            FormDataJson.fromJson(this.$refs.form, data, { clearOthers: true });
            this.$refs.modal.showModal();
        },
        async onDelete(id) {
            this.method = "DELETE";
            this.id = id;
            this.$refs.modal.showModal();
        },

        checkValidity(input) {
            this.clearInputValidity(input);

            if (input.checkValidity()) {
                this.setInputValidity(input, true);
            } else {
                this.setInputValidity(input, false);
            }
        },

        /** @param {HTMLInputElement} input
         * @param {boolean} valid
         * @param {string?} message
         */
        setInputValidity(input, valid, message = null) {
            message = valid
                ? ""
                : message ?? input.validationMessage;
            valid ? input.setCustomValidity("") : input.setCustomValidity(message);

            input.setAttribute("aria-invalid", !valid);
            this.errors[input.name] = message;
        },
        /** @param {HTMLInputElement} input */
        clearInputValidity(input) {
            input.setCustomValidity("");
            input.removeAttribute("aria-invalid");
            this.errors[input.name] = "";
        },
        clearForm() {
            this.$refs.form.reset();

            for (const input of this.$refs.form.elements) {
                this.clearInputValidity(input);
            }
        },
    };
}