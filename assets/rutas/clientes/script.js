import { createGrid, crudButtons } from "/assets/js/grid.js";

const API_ENDPOINT = "/api/clientes";

const tableEl = document.getElementById("table");

/** @type {HTMLFormElement} */
const formEdit = document.forms["edit"];
/** @type {HTMLFormElement} */
const formDelete = document.forms["delete"];

/** @type {HTMLDialogElement} */
const modalEdit = document.getElementById("modal-edit");
/** @type {HTMLDialogElement} */
const modalDelete = document.getElementById("modal-delete");

let ID = "";
let METHOD = "PUT";

const grid = createGrid({
    columns: [
        "Cedula",
        "Nombre",
        "Apellido",
        "Correo",
        "Telefono",
        crudButtons(onModificar, onEliminar)
    ],
    server: {
        url: API_ENDPOINT,
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
grid.render(tableEl);

formEdit.addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = new FormData(formEdit);

    await fetchApi(
        METHOD === "PUT" ? `/${ID}` : "",
        { method: "POST", body: formData }
    );

    modalEdit.close();
    grid.forceRender();
});

document.getElementById("boton-insert").addEventListener("click", (event) => {
    formEdit.reset();
    METHOD = "POST";
    modalEdit.showModal();
});

async function onModificar(id) {
    const data = await fetchApi(`/${id}`);

    for (const el of formEdit.elements) {
        if (el.name in data) {
            el.value = data[el.name];
        }
    }

    formEdit.fecha_nacimiento.value = data.fecha_nacimiento?.substring(0, 10);

    formEdit.elements["membresia[id_tipo]"].value = data.membresia.id_tipo;
    formEdit.elements["membresia[id_estado]"].value = data.membresia.id_estado;
    formEdit.elements["membresia[fecha_inicio]"].value = data.membresia.fecha_inicio.substring(0, 10);
    formEdit.elements["membresia[fecha_fin]"].value = data.membresia.fecha_fin.substring(0, 10);

    ID = id;
    METHOD = "PUT";
    modalEdit.showModal();
}

async function onEliminar(id) {
    ID = id;
    modalDelete.showModal();
}
formDelete.addEventListener("submit", async (event) => {
    event.preventDefault();
    await fetchApi(`/${ID}`, { method: "DELETE" });

    modalDelete.close();
    grid.forceRender();
})

async function fetchApi(params = "", options = {}) {
    const res = await fetch(`${API_ENDPOINT}${params}`, { ...options });

    if (res.status === 204) {
        console.log("Eliminado con exito");
    } else if (res.ok) {
        return await res.json();
    } else {
        console.log(await res.text());
        throw new Error(res.status)
    }
}
