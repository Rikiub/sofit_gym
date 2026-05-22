<?php
$this->layout('layout');

$this->pushJs('pages/clientes/clientes.js');
$this->pushCss('pages/clientes/clientes.css');
$this->pushCss('lib/picocss/pico.red.min.css');
?>

<?= $this->insert('modalForm', [
    'alpineComponent' => <<<JS
            modalClientes({ isSinglePage: true })
        JS,
    'formHtml' => $this->fetch('clientes/forms/cliente'),
]) ?>

<article
    x-data="clienteInfo"
    x-effect="document.title = `Cliente: ${nombreCompleto()}`" ,
    @form-success.window="handleFormSuccess($event.detail)"
    class="pagina-cliente"
>
    <header>
        <div class="header-actions">
            <div>
                <i class="fa-arrow-left fa-solid"></i>
                <a href="?page=clientes">Volver</a>
            </div>

            <h3 x-text="nombreCompleto"></h3>
        </div>

        <div>
            <button
                class="btn btn-warning"
                data-tooltip="Editar"
                data-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'edit', dataId: cliente.cedula, id: 'clientes' })"
            >
                <i class="fa-pen-to-square fa-solid"></i>
            </button>

            <button
                class="btn btn-danger"
                data-tooltip="Eliminar"
                data-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'delete', dataId: cliente.cedula, id: 'clientes' })"
            >
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </header>

    <!-- Información del cliente -->
    <section class="grid">
        <article>
            <header>
                <h3>Información</h3>
            </header>

            <div class="grid">
                <hgroup>
                    <h5>Cédula</h5>
                    <p x-text="setText(cliente.cedula)"></p>
                </hgroup>

                <hgroup>
                    <h5>Telefono</h5>
                    <p x-text="setText(cliente.telefono)"></p>
                </hgroup>
            </div>

            <div class="grid">
                <hgroup>
                    <h5>Correo</h5>
                    <p x-text="setText(cliente.correo)"></p>
                </hgroup>

                <hgroup>
                    <h5>Dirección</h5>
                    <p x-text="setText(cliente.direccion)"></p>
                </hgroup>
            </div>

            <div class="grid">
                <hgroup>
                    <h5>Fecha de nacimiento</h5>
                    <p x-text="setText(onlyDate(cliente.fecha_nacimiento))"></p>
                </hgroup>

                <hgroup>
                    <h5>Fecha de registro</h5>
                    <p x-text="setText(onlyDate(cliente.fecha_registro))"></p>
                </hgroup>
            </div>
        </article>

        <article>
            <header>
                <h3>Membresia</h3>
            </header>

            <div class="grid">
                <hgroup>
                    <h5>Fecha de inicio</h5>
                    <p x-text="setText(onlyDate(cliente.membresia?.fecha_inicio))"></p>
                </hgroup>

                <hgroup>
                    <h5>Fecha de vencimiento</h5>
                    <p x-text="setText(onlyDate(cliente.membresia?.fecha_fin))"></p>
                </hgroup>
            </div>

            <div class="grid">
                <hgroup>
                    <h5>Tipo</h5>
                    <p x-text="setText(cliente.membresia?.tipo)"></p>
                </hgroup>

                <hgroup>
                    <h5>Estado</h5>
                    <p x-text="setText(cliente.membresia?.estado)"></p>
                </hgroup>
            </div>
        </article>
    </section>
</article>

<!-- Seguimiento Fisico -->
<section>
    <article>
        <header>
            <h3>Seguimiento Fisico</h3>
        </header>

        <?= $this->insert('crudTable', ['alpineComponent' => 'crudSegFisico']) ?>

        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalSegFisico',
            'formHtml' => $this->fetch('clientes/forms/seguimiento_fisico'),
        ]) ?>
    </article>
</section>

<!-- Seguimiento Nutricional -->
<section>
    <article>
        <header>
            <h3>Seguimiento Nutricional</h3>
        </header>

        <?= $this->insert('crudTable', ['alpineComponent' => 'crudSegNutricional']) ?>

        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalSegNutricional',
            'formHtml' => $this->fetch('clientes/forms/seguimiento_nutricional'),
        ]) ?>
    </article>
</section>

<style>
    .pagina-cliente {
        header {
            display: flex;
            justify-content: space-between;
        }

        .header-actions {
            display: flex;
            gap: 20px;
        }
    }
</style>