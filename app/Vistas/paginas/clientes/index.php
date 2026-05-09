<?php

/**
 * @var array $tipos
 * @var array $estados
 */
?>

<?php $this->layout('layout', ['titulo' => 'Clientes']) ?>

<?php $this->push('head') ?>
    <link rel="stylesheet" href="/assets/paginas/clientes/clientes.css">
    <script type="module" src="/assets/paginas/clientes/clientes.js"></script>
<?php $this->stop() ?>

<div x-data="crud">
    <dialog closedby="any" id="modal" x-ref="modal">
        <article>
            <header>
                <h2 x-show="method == 'POST'">Crear</h2>
                <h2 x-show="method == 'PUT'">Modificar</h2>
                <h2 x-show="method == 'DELETE'">Eliminar</h2>

                <button
                    aria-label="Close"
                    rel="prev"
                    command="close"
                    commandfor="modal"
                >&times;</button>
            </header>

            <form x-ref="form" @submit.prevent="handleSubmit">
                <div x-show="method !== 'DELETE'" class="content">
                    <div class="grid">
                        <label>Cédula
                            <input required name="cedula" type="text" placeholder="29135792">
                        </label>

                        <label>Nombre
                            <input required name="nombre" type="text" placeholder="Juan">
                        </label>

                        <label>Apellido
                            <input required name="apellido" type="text" placeholder="Pérez">
                        </label>
                    </div>

                    <div class="grid">
                        <label>Teléfono
                            <input name="telefono" type="tel" placeholder="0414-526949">
                        </label>

                        <label>Correo
                            <input name="correo" type="email" placeholder="correo@ejemplo.com">
                        </label>

                        <label>Dirección
                            <input name="direccion" type="text" placeholder="Calle Principal #123">
                        </label>

                        <label>Fecha de Nacimiento
                            <input name="fecha_nacimiento" type="date">
                        </label>
                    </div>

                    <div class="grid">
                        <label>Tipo Membresia
                            <select name="membresia[id_tipo]">
                                <?php foreach ($estados as $item): ?>
                                    <option value="<?= $item['id_tipo'] ?>">
                                        <?= $item['nombre'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </label>

                        <label>Estado Membresia
                            <select name="membresia[id_estado]">
                                <?php foreach ($tipos as $item): ?>
                                    <option value="<?= $item['id_estado'] ?>">
                                        <?= $item['nombre'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </label>
                    </div>

                    <div class="grid">
                        <label>Fecha Inicio Membresía
                            <input name="membresia[fecha_inicio]" type="date">
                        </label>
                
                        <label>Fecha Fin Membresía
                            <input name="membresia[fecha_fin]" type="date">
                        </label>
                    </div>
                </div>

                <footer>
                    <div x-show="method !== 'DELETE'">
                        <button type="submit">Enviar</button>
                    </div>

                    <div x-show="method == 'DELETE'">
                        <button type="button" command="close" commandfor="modal">No</button>
                        <button type="submit">Si</button>
                    </div>
                </footer>
            </form>
        </article>
    </dialog>

    <div>
        <h1>Clientes</h1>

        <button @click="onCreate">Crear</button>
        <div class="table" x-ref="table"></div>
    </div>
</div>

