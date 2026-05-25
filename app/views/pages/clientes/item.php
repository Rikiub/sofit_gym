<?php
$this->layout('layout', ["title" => "Cliente"]);
$this->pushJs('pages/clientes/clientes.js');
$this->pushCss('pages/clientes/clientes.css');
?>

<?php ob_start() ?>
<article
    x-data="clienteInfo"
    x-effect=" document.title=`Cliente: ${nombreCompleto()}`"
    @form-success.window="handleFormSuccess($event.detail)"
    class="pagina-cliente">

    <header class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <h3 class="mb-0" x-text="nombreCompleto"></h3>
        </div>

        <div class="d-flex gap-2">
            <button
                class="btn btn-warning"
                title="Editar"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'edit', dataId: cliente.cedula, id: 'clientes' })">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>

            <button
                class="btn btn-danger"
                title="Eliminar"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'delete', dataId: cliente.cedula, id: 'clientes' })">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </header>

    <div class="row">
        <!-- Información del cliente -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <header class="card-header">
                    <h3 class="card-title mb-0">Información</h3>
                </header>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Cédula</h5>
                            <p x-text="setText(cliente.cedula)"></p>
                        </div>
                        <div class="col-6">
                            <h5>Telefono</h5>
                            <p x-text="setText(cliente.telefono)"></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Correo</h5>
                            <p x-text="setText(cliente.correo)"></p>
                        </div>

                        <div class="col-6">
                            <h5>Dirección</h5>
                            <p x-text="setText(cliente.direccion)"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <h5>Fecha de nacimiento</h5>
                            <p x-text="setText(onlyDate(cliente.fecha_nacimiento))"></p>
                        </div>

                        <div class="col-6">
                            <h5>Fecha de registro</h5>
                            <p x-text="setText(onlyDate(cliente.fecha_registro))"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membresía -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <header class="card-header">
                    <h3 class="card-title mb-0">Membresia</h3>
                </header>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Fecha de inicio</h5>
                            <p x-text="setText(onlyDate(cliente.membresia?.fecha_inicio))"></p>
                        </div>

                        <div class="col-6">
                            <h5>Fecha de vencimiento</h5>
                            <p x-text="setText(onlyDate(cliente.membresia?.fecha_fin))"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>Tipo</h5>
                            <p x-text="setText(cliente.membresia?.tipo)"></p>
                        </div>

                        <div class="col-6">
                            <h5>Estado</h5>
                            <p x-text="setText(cliente.membresia?.estado)"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<hr>

<details class="card" open>
    <summary class="card-header">
        <h4 class="d-inline-block mb-0">Seguimiento Fisico</h4>
    </summary>

    <div class="card-body">
        <?= $this->insert('crudTable', ['alpineComponent' => 'crudSegFisico']) ?>
        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalSegFisico',
            'formHtml' => $this->fetch('clientes/forms/seguimiento_fisico'),
        ]) ?>
    </div>
</details>

<hr>

<details class="card" open>
    <summary class="card-header">
        <h4 class="d-inline-block mb-0">Seguimiento Nutricional</h4>
    </summary>

    <div class="card-body">
        <?= $this->insert('crudTable', ['alpineComponent' => 'crudSegNutricional']) ?>
        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalSegNutricional',
            'formHtml' => $this->fetch('clientes/forms/seguimiento_nutricional'),
        ]) ?>
    </div>
</details>
<?php $body = ob_get_clean() ?>

<?= $this->insert('modalForm', [
    'alpineComponent' => <<<JS
            modalClientes({ isSinglePage: true })
        JS,
    'formHtml' => $this->fetch('clientes/forms/cliente'),
]) ?>

<?= $this->insert("card", [
    "title" => "Información del cliente",
    "header_right" => <<<HTML
        <a href="?page=clientes" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left"></i>
            Volver
        </a>
    HTML,
    "body" => $body,
]) ?>