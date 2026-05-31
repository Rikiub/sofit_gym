<?php
$title = "Calendario de Clases";

$this->layout("layout", ["title" => $title]);
$this->pushJs("pages/clases/clases.js");

$selectTrabajadores = $this->fetch("querySelect", [
    "input" => ["name" => "cedula_trabajador", "required" => true],
    "columns" => [
        ["name" => "Cédula", "id" => 'cedula'],
        ["name" => "Nombre", "id" => "nombre_completo", "computed" => '`${item.nombre} ${item.apellido}`'],
        ["name" => "Rol", "id" => 'rol']
    ],
    "params" => [
        "page" => "trabajadores",
        "action" => "query",
        "id_rol" => 2,
    ],
    "itemKey" => "cedula",
]);

$selectClientes = $this->fetch("querySelect", [
    "columns" => [
        ["name" => "Cédula", "id" => 'cedula'],
        ["name" => "Nombre", "id" => "nombre_completo", "computed" => '`${item.nombre} ${item.apellido}`'],
    ],
    "params" => [
        "page" => "clientes",
        "action" => "query",
    ],
    "itemKey" => "cedula",
]);

$modalForm = $this->fetch('modalForm', [
    'xData' => 'modalForm',
    'form' => <<<HTML
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
                <label class="col form-label">Entrenador
                    {$selectTrabajadores}
                    <small class="form-text" x-text="errors.cedula_trabajador"></small>
                </label>
            </fieldset>

            <hr>

            <div
                x-data="listaClientes"
                @form-reset.window="reset()"
                @form-load.window="load(\$event.detail)"
                @form-validate.window="validate(\$event.detail)"
                @form-serialize.window="serialize(\$event.detail)"
            >
                <fieldset class="row">
                    <label class="form-label col">Cupos Ocupados
                        <input class="form-control" name="cupos_ocupados" :value="cupos_ocupados" placeholder="0" readonly>
                    </label>

                    <label class="form-label col">Capacidad Maxima
                        <input class="form-control" name="capacidad_maxima" required type="number" placeholder="1" min="1" @input="checkValidity(\$el)">
                        <small class="form-text" x-text="errors.capacidad_maxima"></small>
                    </label>
                </fieldset>

                <div>
                    <label class="form-label">Lista de Clientes</label>

                    <div @item-selected="handleItemSelected(\$event.detail)">
                        {$selectClientes}
                    </div>

                    <ul class="list-group mb-3">
                        <template x-for="(cliente, index) in clientes" :key="cliente.cedula">
                            <li class="list-group-item d-flex justify-content-between align-items-center view-fade-in">
                                <div>
                                    <span class="badge bg-secondary me-2" x-text="cliente.cedula"></span>
                                    <span class="text-dark" x-text='`\${cliente.nombre} \${cliente.apellido}`'></span>
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-danger border-0" @click="remove(index)">
                                    Eliminar
                                </button>
                            </li>
                        </template>

                        <template x-if="clientes.length === 0">
                            <li class="list-group-item text-muted text-center py-3 bg-light">
                                No hay clientes asignados a esta clase todavía.
                            </li>
                        </template>
                    </ul>

                    <small class="form-text" x-text="errors.clientes"></small>
                </div>
            </div>

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
    "class" => "main",
    'title' => $title,
    'body' => <<<HTML
        <main class="px-1 mb-5">
            {$this->fetch("calendar", ["xData" => "calendarClases"])}
        </main>
        
        {$modalForm}
    HTML
]) ?>

<style>
    .main {
        max-width: 1000px;
        margin: auto;
    }
</style>