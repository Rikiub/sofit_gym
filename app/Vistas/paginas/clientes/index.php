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

<dialog closedby="any" id="modal-edit">
	<article>
		<header>
			<h2>Modificar</h2>
			
			<button
				aria-label="Close"
				rel="prev"
                command="close"
                commandfor="modal-edit"
			>&times;</button>
		</header>

        <form name="edit">
		    <div class="content">
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
                <button type="submit">Enviar</button>
            </footer>
        </form>
	</article>
</dialog>

<dialog closedby="any" id="modal-delete">
    <article>
        <header>
			<h2>Eliminar</h2>
			
            <button
                aria-label="Close"
                rel="prev"
                command="close"
                commandfor="modal-delete"
            >&times;</button>
        </header>

        <footer>
            <form name="delete">
                <button type="button" command="close" commandfor="modal-delete">No</button>
                <button type="submit">Si</button>
            </form>
        </footer>
    </article>
</dialog>

<div class="Clientes">
    <h1>Clientes</h1>

    <button id="boton-insert">Insertar</button>
    <div class="table" id="table"></div>
</div>
