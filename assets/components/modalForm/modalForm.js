import { fetchApi } from "@/js/api.js";
import { toIsoDate } from "@/js/dates.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

/**
 * @param {Object} data
 * @param {{
 * mode: "add"|"edit"|"delete",
 * dataId: string|number|null,
 * id: string|number|null,
 * }} detail
 */
export function openModal(data, detail) {
    data.$dispatch("open-modal", detail);
}

/**
 * @typedef {Object} Actions
 * @property {string} onAdd
 * @property {string} onEditFind
 * @property {string} onEdit
 * @property {string} onDelete
 */

/**
 * @param {{
 * page: string,
 * actions: Actions,
 * extraPostBody: object?,
 * elementName: string?,
 * prepareAddData?: Object,
 * transformEditData?: (data: object) => object,
 * editDisableFields?: string[],
 * afterSubmit?: (mode: string) => void,
 * id?: string,
 * }}
 */
export function modalFormComponent(
    {
        page,
        actions,
        extraPostBody = {},
        elementName = "",
        prepareAddData = {},
        transformEditData = (data) => data,
        editDisableFields = [],
        afterSubmit = () => null,
        id: componentId = null,
    },
) {
    return {
        currentDataId: null,
        mode: null,

        page,
        actions,
        elementName,

        loading: false,
        errors: {},

        init() {
            this.modal = new bootstrap.Modal(this.$refs.modal);
        },
        openModal() {
            this.modal.show();
        },
        closeModal() {
            this.modal.hide();
        },

        handleOpenModal({ mode, id = null, dataId = null }) {
            if (id !== componentId) return;
            if (!mode) return console.error("A 'mode' must be provided");

            // On Add
            if (mode === "add") return this.onAdd();

            // On Edit/Delete
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
                this.loading = true;
                let body = null;
                let actionParam = {
                    "add": this.actions.onAdd,
                    "edit": this.actions.onEdit,
                    "delete": this.actions.onDelete,
                }[this.mode];
                let params = { action: actionParam };

                if (this.mode == "edit" || this.mode == "delete") {
                    params = {
                        ...params,
                        id: this.currentDataId,
                    };
                }
                if (this.mode == "edit" || this.mode == "add") {
                    body = FormDataJson.toJson(this.$refs.form, {
                        skipEmpty: true,
                        includeDisabled: true,
                    });
                    body = { ...body, ...extraPostBody };
                }

                try {
                    await fetchApi({ page: this.page, ...params }, {
                        method: "POST",
                        body: body,
                    });
                } finally {
                    this.loading = false;
                }

                this.closeModal();

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

            FormDataJson.fromJson(this.$refs.form, prepareAddData, {
                clearOthers: true,
                includeDisabled: true,
            });

            this.openModal();
        },
        async onEdit(id) {
            this.clearForm();

            this.mode = "edit";
            this.currentDataId = id;

            let data = await fetchApi(
                {
                    page: this.page,
                    action: this.actions.onEditFind,
                    id: this.currentDataId,
                },
            );
            data = transformEditData(data);
            FormDataJson.fromJson(this.$refs.form, data, {
                clearOthers: true,
                includeDisabled: true,
            });

            for (const inputName of editDisableFields) {
                if (this.$refs.form[inputName]) {
                    this.$refs.form[inputName].disabled = true;
                }
            }

            this.openModal();
        },
        async onDelete(id) {
            this.mode = "delete";
            this.currentDataId = id;
            this.openModal();
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
            
            this.errors[input.name] = message;
        },
        /** @param {HTMLInputElement} input */
        clearInputValidity(input) {
            input.setCustomValidity("");
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

        // Validaciones reutilizables

        /** @param {HTMLInputElement} input */
        async validateCedula(input) {
            this.checkValidity(input);

            if (this.mode === "add") {
                let item = null;

                try {
                    item = await fetchApi({
                        page: this.page,
                        action: this.actions.onEditFind,
                        id: input.value,
                    });
                } catch {}

                if (item) {
                    this.setInputValidity(input, false, "La persona ya existe");
                }
            }
        },
    };
}

Alpine.data("modalForm", modalFormComponent);
