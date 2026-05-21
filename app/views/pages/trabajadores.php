<?php

$this->layout('layout', ['title' => 'Trabajadores']);

$this->pushJs('pages/trabajadores/trabajadores.js');
$this->pushCss('pages/trabajadores/trabajadores.css');
?>

<main>
    <h1 style="color: white;">Trabajadores</h1>

    <div>
        <?= $this->insert('crudTable', ['alpineComponent' => 'crudTable']) ?>

        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalForm',
            'formHtml' => <<<HTML
                {$this->fetch("personaForm")}

                <hr>

                <!-- Datos específicos de TrabajadorDTO -->
                <fieldset class="grid">
                    <label>Rol
                        <select name="id_rol" required @input.debounce="checkValidity(\$el)">
                            <option value="">Seleccione un rol</option>
                            <option value="1">Administrador</option>
                            <option value="2">Entrenador</option>
                            <option value="3">Recepcionista</option>
                        </select>
                        <small x-text="errors.id_rol"></small>
                    </label>

                    <label>Salario
                        <input type="number" name="salario" step="any" required @input.debounce="checkValidity(\$el)">
                        <small x-text="errors.salario"></small>
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Fecha de contratación
                        <input type="date" name="fecha_contratacion" required @input.debounce="checkValidity(\$el)">
                        <small x-text="errors.fecha_contratacion"></small>
                    </label>
                </fieldset>
            HTML,
        ]) ?>
    </div>
</main>