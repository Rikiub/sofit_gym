import { fetchApi } from "/assets/js/api.js";
import { extractDate } from "/assets/js/helpers.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

/**
 * @param {{
 * endpoint: string,
 * transformEditData?: (data: object) => object,
 * editDisableFields?: array<string>,
 * afterSubmit?: (mode: string) => void,
 * id?: string,
 * }}
 */
export function modalFormComponent(
    {
        endpoint,
        transformEditData = (data) => data,
        editDisableFields = [],
        afterSubmit = () => null,
        id: componentId = null,
    },
) {
    return {
        currentDataId: null,
        mode: null,

        loading: false,
        errors: {},

        handleOpenModal({ mode, id = null, dataId = null }) {
            if (!mode) return console.error("A 'mode' must be provided");

            // On Add
            if (mode === "add") return this.onAdd();

            // On Edit/Delete
            if (id !== componentId) return;
            if (!dataId) {
                return console.error("A 'dataId' must be provided");
            }

            if (mode === "edit") return this.onEdit(dataId);
            if (mode === "delete") return this.onDelete(dataId);
            return console.error(
                "'mode' must be one of: 'add', 'edit', 'delete'",
            );
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

            if (this.mode === "delete" || valid) {
                let body = null;
                let url = `/${endpoint}`;
                this.loading = true;

                if (this.mode == "edit" || this.mode == "delete") {
                    url = `${url}/${this.currentDataId}`;
                }
                if (this.mode == "edit" || this.mode == "add") {
                    body = FormDataJson.toJson(this.$refs.form, {
                        skipEmpty: true,
                    });
                }

                const method = {
                    "add": "POST",
                    "edit": "PUT",
                    "delete": "DELETE",
                }[this.mode];

                await fetchApi(url, { method, body: body });

                this.loading = false;
                this.$refs.modal.close();

                this.$dispatch("form-success", {
                    id: componentId,
                    action: "refresh",
                });
                afterSubmit(this.mode);
            } else {
                console.log("Invalid input, form submit canceled");
            }
        },

        async onAdd() {
            this.clearForm();
            this.mode = "add";
            this.$refs.modal.showModal();
        },
        async onEdit(id) {
            this.clearForm();

            this.mode = "edit";
            this.currentDataId = id;

            let data = await fetchApi(`/${endpoint}/${this.currentDataId}`);
            data = transformEditData(data);
            FormDataJson.fromJson(this.$refs.form, data, { clearOthers: true, includeDisabled: true });

            for (const inputName of editDisableFields) {
                if (this.$refs.form[inputName]) {
                    this.$refs.form[inputName].disabled = true;
                }
            }

            this.$refs.modal.showModal();
        },
        async onDelete(id) {
            this.mode = "delete";
            this.currentDataId = id;
            this.$refs.modal.showModal();
        },

        /** @param {HTMLInputElement} input */
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
            message = valid ? "" : message ?? input.validationMessage;
            valid
                ? input.setCustomValidity("")
                : input.setCustomValidity(message);

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

            for (const inputName of editDisableFields) {
                if (this.$refs.form[inputName]) {
                    this.$refs.form[inputName].disabled = false;
                }
            }

            for (const input of this.$refs.form.elements) {
                this.clearInputValidity(input);
            }
        },
    };
}

Alpine.data("modalForm", modalFormComponent);
