<?php

/**
 * @var array $tipos
 * @var array $estados
 */
?>

<?php $this->layout('layout', ['titulo' => 'Clientes']) ?>

<?php
$this->pushCss('/assets/paginas/clientes/clientes.css');
$this->pushJs('/assets/paginas/clientes/clientes.js');
?>

<div x-data="crud">
    <dialog x-ref="modal" x-id="['form']">
        <article>
            <header>
                <button
                    aria-label="Cerrar"
                    rel="prev"
                    @click="$refs.modal.close()"
                ></button>

                <h3 x-show="method == 'POST'">Crear</h3>
                <h3 x-show="method == 'PUT'">Editar</h3>
                <h3 x-show="method == 'DELETE'">Eliminar</h3>
            </header>

            <p x-show="method == 'DELETE'">
                ¿Seguro que quieres eliminarlo?
            </p>

            <form
                x-show="method !== 'DELETE'" x-ref="form"
                @submit.prevent="handleSubmit"
                :id="$id('form')"
            >
                <fieldset class="grid">
                    <label>Cédula
                        <input required name="cedula" type="text" placeholder="29135792">
                    </label>

                    <label>Nombre
                        <input required name="nombre" type="text" placeholder="Juan">
                    </label>

                    <label>Apellido
                        <input required name="apellido" type="text" placeholder="Pérez">
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Teléfono
                        <input name="telefono" type="tel" placeholder="0414-526949">
                    </label>

                    <label>Correo
                        <input name="correo" type="email" placeholder="correo@ejemplo.com">
                    </label>

                    <label>Dirección
                        <input name="direccion" type="text" placeholder="Calle Principal #123">
                    </label>
                </fieldset>

                <fieldset>
                    <label>Fecha de nacimiento
                        <input name="fecha_nacimiento" type="date">
                    </label>
                </fieldset>

                <hr>

                <fieldset class="grid">
                    <label>Tipo de membresia
                        <select name="membresia[id_tipo]">
                            <?php foreach ($estados as $item): ?>
                                <option value="<?= $item['id_tipo'] ?>">
                                    <?= $item['nombre'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </label>

                    <label>Estado de membresia
                        <select name="membresia[id_estado]">
                            <?php foreach ($tipos as $item): ?>
                                <option value="<?= $item['id_estado'] ?>">
                                    <?= $item['nombre'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Fecha de inicio de membresía
                        <input name="membresia[fecha_inicio]" type="date">
                    </label>
            
                    <label>Fecha de fin de membresía
                        <input name="membresia[fecha_fin]" type="date">
                    </label>
                </fieldset>
            </form>

            <footer>
                <button x-show="method !== 'DELETE'" :form="$id('form')">Enviar</button>

                <div x-show="method == 'DELETE'">
                    <button class="secondary" @click="$refs.modal.close()">No</button>
                    <button :form="$id('form')">Si</button>
                </div>
            </footer>
        </article>
    </dialog>

    <div>
        <h1 class="title">Clientes</h1>
        <div class="overflow-auto" x-ref="table"></div>
    </div>
</div>
