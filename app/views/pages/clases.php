<?php
$this->layout("layout", ["title" => "Clases"]);
$this->pushJs("pages/clases/clases.js");

$modalForm = $this->fetch('modalForm', [
    'alpineComponent' => 'modalForm',
    'formHtml' => <<<HTML
            <input hidden name="id_clase">

            <fieldset class="row">
                <label class="col form-label">Nombre de la clase
                    <input class="form-control" required name="nombre" type="text" 
                        @input.debounce="checkValidity(\$el)">
                    <small class="form-text" x-text="errors.nombre"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="col form-label">Descripción
                    <textarea class="form-control" name="descripcion" rows="2" 
                            @input.debounce="checkValidity(\$el)"></textarea>
                    <small class="form-text" x-text="errors.descripcion"></small>
                </label>
            </fieldset>

            <hr>

            <fieldset class="row">
                <label class="col form-label">Instructor (cédula)
                    <input class="form-control" required name="cedula_trabajador" type="text" 
                        pattern="^V-\d{8}$" x-mask="V-99999999" 
                        @input.debounce.500ms="checkValidity(\$el)">
                    <small class="form-text" x-text="errors.cedula_trabajador"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="col form-label">Cupos ocupados
                    <input class="form-control" required name="cupos_ocupados" type="number" min="0" 
                        @input.debounce="checkValidity(\$el)" placeholder="0">
                    <small class="form-text" x-text="errors.cupos_ocupados"></small>
                </label>

                <label class="col form-label">Capacidad máxima
                    <input class="form-control" required name="capacidad_maxima" type="number" min="1" 
                        @input.debounce="checkValidity(\$el)" placeholder="0">
                    <small class="form-text" x-text="errors.capacidad_maxima"></small>
                </label>
            </fieldset>

            <hr>

            <fieldset class="row">
                <label class="col form-label">Fecha y hora de inicio
                    <input class="form-control" required name="fecha_inicio" type="datetime-local" 
                        @input.debounce="checkValidity(\$el)">
                    <small class="form-text" x-text="errors.fecha_inicio"></small>
                </label>

                <label class="col form-label">Fecha y hora de fin
                    <input class="form-control" required name="fecha_fin" type="datetime-local" 
                        @input.debounce="checkValidity(\$el)">
                    <small class="form-text" x-text="errors.fecha_fin"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="col form-label">Estado
                    <select class="form-control" name="estado" required @change="checkValidity(\$el)">
                        <option value="Programado">Programado</option>
                        <option value="En curso">En curso</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                    <small class="form-text" x-text="errors.estado"></small>
                </label>
            </fieldset>
        HTML,
]);
?>

<?= $this->insert('card', [
    'title' => 'Clases',
    'body' => <<<HTML
        <main class="container px-5 mb-5">
            {$this->fetch("calendar", ["alpineComponent" => "calendarClases"])}
        </main>
        
        {$modalForm}
    HTML
]) ?>
