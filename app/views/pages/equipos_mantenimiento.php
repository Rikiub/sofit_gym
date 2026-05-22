<?php
$title = "Historial de mantenimientos";
$this->layout("layout", ["title" => $title]);
$this->pushJs("pages/equipos/equipos_mantenimiento.js");

$modalForm = $this->fetch(
    'modalForm',
    [
        'alpineComponent' => 'modalMantenimiento',
        'formHtml' => <<<HTML
            <input name="id" hidden>

            <fieldset class="row">
                <label class="text-label col">Equipo
                    <select class="form-select" name="codigo_equipo" required placeholder="Equipo">
                        <template x-for="item in equipos" :key="item.codigo">
                            <option :value="item.codigo" x-text="item.codigo + ': ' + item.nombre"></option>
                        </template>
                    </select>
                    <small x-text="errors.codigo_equipo"></small>
                </label>

                <label class="text-label col">Fecha
                    <input class="form-control" type="date" name="fecha" required>
                    <small x-text="errors.fecha"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="text-label col">Tipo de Mantenimiento
                    <select class="form-select" name="tipo" required>
                        <option value="">Seleccione un tipo…</option>
                        <option value="Preventivo">Preventivo</option>
                        <option value="Correctivo">Correctivo</option>
                        <option value="Predictivo">Predictivo</option>
                        <option value="Calibración">Calibración</option>
                    </select>
                    <small x-text="errors.tipo"></small>
                </label>

                <label class="text-label col">Costo
                    <input class="form-control" type="number" name="costo" step="any" min="0" x-mask="999999.99" placeholder="0.00">
                    <small x-text="errors.costo"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="text-label col">Técnico
                    <input class="form-control" type="text" name="tecnico" placeholder="Nombre del técnico responsable">
                    <small x-text="errors.tecnico"></small>
                </label>
            </fieldset>

            <hr>

            <fieldset class="row">
                <label class="text-label col">Descripción
                    <textarea class="form-control" name="descripcion" placeholder="Detalles del mantenimiento realizado" rows="3"></textarea>
                    <small x-text="errors.descripcion"></small>
                </label>
            </fieldset>
        HTML
    ]
);
?>

<main x-data="mainData">
    <?= $this->insert("card", [
        "cardTitle" => $title,
        "children" => <<<HTML
            {$this->fetch('crudTable', ['alpineComponent' => 'crudMantenimiento'])}
            {$modalForm}
        HTML,
    ]) ?>
</main>