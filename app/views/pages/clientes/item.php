<?php

/**
 * @var array $formMeta
 * @var App\Modelos\Clientes\ClienteDTO $cliente
 */
$this->layout('layout');
$this->pushJs('/assets/paginas/clientes/clientes.js');
$this->pushCss('/assets/paginas/clientes/clientes.css');

$nombreCompleto = "$cliente->nombre $cliente->apellido"
?>

<?= $this->insert('modalForm', [
    'alpineComponent' => <<<JS
            modalFormClientes({ isSinglePage: true })
        JS,
    'formHtml' => $this->fetch('clientes/forms/cliente', [
        'formMeta' => $formMeta,
    ]),
]) ?>

<article x-data class="pagina-cliente">
    <header>
        <div class="header-actions">
            <div>
                <i class="fa-arrow-left fa-solid"></i>
                <a href="/clientes">Volver</a>
            </div>

            <h3><?= $nombreCompleto ?></h3>
        </div>

        <div>
            <button
                data-tooltip="Editar"
                data-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'edit', dataId: '<?= $cliente->cedula ?>' })"
            >
                <i class="fa-pen-to-square fa-solid"></i>
            </button>

            <button
                data-tooltip="Eliminar"
                data-placement="bottom"
                @click="$dispatch('open-modal', { mode: 'delete', dataId: '<?= $cliente->cedula ?>' })"
            >
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </header>

    <div class="grid">
        <article>
            <header>
                <h3>Información</h3>
            </header>

            <div class="grid">
                <hgroup>
                    <h5>Telefono</h5>
                    <p><?= $cliente->telefono ?></p>
                </hgroup>
            
                <hgroup>
                    <h5>Correo</h5>
                    <p><?= $cliente->correo ?></p>
                </hgroup>

                <hgroup>
                    <h5>Dirección</h5>
                    <p><?= $cliente->direccion ?? 'Desconocida' ?></p>
                </hgroup>
            </div>

            <div class="grid">
                <hgroup>
                    <h5>Fecha de nacimiento</h5>
                    <p><?= $cliente->fecha_nacimiento->format('d/m/Y') ?? 'Desconocida' ?></p>
                </hgroup>

                <hgroup>
                    <h5>Fecha de registro</h5>
                    <p><?= $cliente->fecha_registro->format('d/m/Y') ?? 'Desconocida' ?></p>
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
                    <p><?= $cliente->membresia->fecha_inicio->format('d/m/Y') ?? 'Desconocida' ?></p>
                </hgroup>

                <hgroup>
                    <h5>Fecha de vencimiento</h5>
                    <p><?= $cliente->membresia->fecha_fin->format('d/m/Y') ?? 'Desconocida' ?></p>
                </hgroup>
            </div>

            <div class="grid">
                <hgroup>
                    <h5>Tipo</h5>
                    <p><?= $cliente->membresia->tipo ?></p>
                </hgroup>

                <hgroup>
                    <h5>Estado</h5>
                    <p><?= $cliente->membresia->estado ?></p>
                </hgroup>
            </div>
        </article>
    </div>
</article>
