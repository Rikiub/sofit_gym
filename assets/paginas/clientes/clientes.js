import { createGrid } from "/assets/js/grid.js";
import FormDataJson from "form-data-json";
import Alpine from "alpinejs";

document.addEventListener("alpine:init", () => {
    Alpine.data("crud", () => ({
        endpoint: "/api/clientes",
        method: "PUT",
        id: "",

        init() {
            this.grid = createGrid({
                columns: [
                    "Cedula",
                    "Nombre",
                    "Apellido",
                    "Correo",
                    "Telefono",
                ],
                server: {
                    url: this.endpoint,
                    then: data => data.map(item => [
                        item.cedula,
                        item.nombre,
                        item.apellido,
                        item.correo,
                        item.telefono,
                        item.direccion,
                    ]),
                },
                onAdd: this.onAdd.bind(this),
                onEdit: this.onEdit.bind(this),
                onDelete: this.onDelete.bind(this),
            });
            this.grid.render(this.$refs.table);
        },

        async handleSubmit() {
            let body = null;
            let url = "";

            if (this.method == "PUT" || this.method == "DELETE") {
                url = `/${this.id}`;
            }
            if (this.method == "PUT" || this.method == "POST") {
                body = JSON.stringify(
                    FormDataJson.toJson(this.$refs.form, { skipEmpty: true })
                );
            }

            await this.fetchApi(url, { method: this.method, body: body });

            this.$refs.modal.close();
            this.grid.forceRender();
        },

        async onAdd() {
            this.method = "POST";
            this.$refs.form.reset();
            this.$refs.modal.showModal();
        },
        async onEdit(id) {
            this.$refs.form.reset();
            this.method = "PUT";
            this.id = id;

            const data = await this.fetchApi(`/${this.id}`);
            data.fecha_nacimiento = data.fecha_nacimiento?.split("T")[0];
            data.membresia.fecha_inicio = data.membresia.fecha_inicio?.split("T")[0];
            data.membresia.fecha_fin = data.membresia.fecha_fin?.split("T")[0];

            FormDataJson.fromJson(this.$refs.form, data, { clearOthers: true });
            this.$refs.modal.showModal();
        },
        async onDelete(id) {
            this.method = "DELETE";
            this.id = id;
            this.$refs.modal.showModal();
        },

        /** 
         * @param string params,
         * @param {RequestInit} options
         */
        async fetchApi(params = "", options = {}) {
            const res = await fetch(`${this.endpoint}${params}`, {
                headers: { "Content-Type": "application/json" },
                ...options
            });

            if (res.status === 204) {
                return {}
            } else if (res.ok) {
                return await res.json();
            } else {
                let json = await res.json();
                console.log(json);
                throw new Error(res.status);
            }
        },
    }));
});
