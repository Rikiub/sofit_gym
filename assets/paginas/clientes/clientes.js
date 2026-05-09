import { createGrid, crudButtons } from "/assets/js/grid.js";
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
                    crudButtons(this.onEdit.bind(this), this.onDelete.bind(this))
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
            });
            this.grid.render(this.$refs.table);
        },

        async handleSubmit() {
            if (this.method === "DELETE") {
                await this.fetchApi(`/${this.id}`, { method: "DELETE" });
            } else {
                await this.fetchApi(
                    this.method === "PUT" ? `/${this.id}` : "",
                    { method: "POST", body: new FormData(this.$refs.form) }
                );
            }

            this.$refs.modal.close();
            this.grid.forceRender();
        },

        async onCreate() {
            this.method = "POST";
            this.$refs.form.reset();
            this.$refs.modal.showModal();
        },
        async onEdit(id) {
            this.$refs.form.reset();
            this.method = "PUT";
            this.id = id;

            const data = await this.fetchApi(`/${this.id}`);

            /** @type {HTMLFormElement} */
            let form = this.$refs.form;
            form.reset();

            for (const el of form.elements) {
                if (el.name in data) {
                    el.value = data[el.name];
                }
            }

            form.fecha_nacimiento.value = data.fecha_nacimiento?.substring(0, 10);

            form.elements["membresia[id_tipo]"].value = data.membresia.id_tipo;
            form.elements["membresia[id_estado]"].value = data.membresia.id_estado;
            form.elements["membresia[fecha_inicio]"].value = data.membresia.fecha_inicio.substring(0, 10);
            form.elements["membresia[fecha_fin]"].value = data.membresia.fecha_fin.substring(0, 10);

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
            const res = await fetch(`${this.endpoint}${params}`, { ...options });

            if (res.status === 204) {
                return {}
            } else if (res.ok) {
                return await res.json();
            } else {
                console.log(await res.text());
                throw new Error(res.status)
            }
        },
    }));
});
