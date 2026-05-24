<?php
$title = 'Historial de mantenimientos';
$this->layout('layout', ['title' => $title]);
$this->pushJs('pages/equipos/equipos_mantenimiento.js');

$modalForm = $this->fetch(
    'modalForm',
    [
        'alpineComponent' => 'modalMantenimiento',
        'formHtml' => <<<HTML
                <input name="id" hidden>

                <fieldset class="row">
                    <label class="text-label col">Equipo
                        <select class="form-select" name="codigo_equipo" required placeholder="Equipo" @input.debounce="checkValidity(\$el)">
                            <template x-for="item in equipos" :key="item.codigo">
                                <option :value="item.codigo" x-text="item.codigo + ': ' + item.nombre"></option>
                            </template>
                        </select>
                        <small x-text="errors.codigo_equipo"></small>
                    </label>

                    <label class="text-label col">Fecha
                        <input class="form-control" type="date" name="fecha" required @input.debounce="checkValidity(\$el)">
                        <small x-text="errors.fecha"></small>
                    </label>
                </fieldset>

                <fieldset class="row">
                    <label class="text-label col">Tipo de Mantenimiento
                        <select class="form-select" name="tipo" required @input.debounce="checkValidity(\$el)">
                            <option value="">Seleccione un tipo…</option>
                            <option value="Preventivo">Preventivo</option>
                            <option value="Correctivo">Correctivo</option>
                            <option value="Predictivo">Predictivo</option>
                            <option value="Calibración">Calibración</option>
                        </select>
                        <small x-text="errors.tipo"></small>
                    </label>

                    <label class="text-label col">Costo
                        <input
                            class="form-control"
                            name="costo"
                            type="number"
                            step="any"
                            placeholder="0.00"
                            @input.debounce="checkValidity(\$el)"
                        >
                        <small x-text="errors.costo"></small>
                    </label>
                </fieldset>

                <fieldset class="row">
                    <label class="text-label col">Técnico
                        <input
                            class="form-control"
                            type="text"
                            name="tecnico"
                            placeholder="Nombre del técnico responsable" 
                            @input.debounce="checkValidity(\$el)"
                        >
                        <small x-text="errors.tecnico"></small>
                    </label>
                </fieldset>

                <hr>

                <fieldset class="row">
                    <label class="text-label col">Descripción
                        <textarea
                            class="form-control"
                            name="descripcion"
                            placeholder="Detalles del mantenimiento realizado"
                            rows="3"
                            @input.debounce="checkValidity(\$el)"
                        ></textarea>
                        <small x-text="errors.descripcion"></small>
                    </label>
                </fieldset>
            HTML
    ]
);
?>

<div x-data="mainData">
    <?= $this->insert('card', [
        'title' => $title,
        'children' => <<<HTML
                <main>
                    {$this->fetch('crudTable', ['alpineComponent' => 'crudMantenimiento'])}
                </main>
                {$modalForm}
            HTML,
    ]) ?>
</div>