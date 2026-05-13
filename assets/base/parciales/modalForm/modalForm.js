import { fetchApi } from "../../../js/api.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

/** @param {{
     * endpoint: string,
     * transformEditData: (data: object) => object,
 * }} options
 */
export function modalFormComponent(options) {
    let transformEditData = options.transformEditData;
    if (!transformEditData) {
        transformEditData = (data) => data;
    };

    return {
        endpoint: options.endpoint,
        transformEditData: transformEditData,
        method: "POST",
        id: "",

        loading: false,
        errors: {},

        handleEvent(detail) {
            if (detail.method === "POST") {
                this.onAdd();
            } else if (detail.method === "PUT") {
                this.onEdit(detail.id);
            } else if (detail.method === "DELETE") {
                this.onDelete(detail.id);
            } else {
                console.log("A 'method' must be provided");
            }
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
                let url = `/${this.endpoint}`;
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
                this.$dispatch("form-success");
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

            let data = await fetchApi(`/${this.endpoint}/${this.id}`);
            data = this.transformEditData(data);

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

Alpine.data("modalForm", modalFormComponent);