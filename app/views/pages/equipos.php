<?php
$title = 'Inventario de equipos';

$this->layout('layout', ['title' => $title]);
$this->pushJs('pages/equipos/equipos.js');

$modalForm = $this->fetch('modalForm', [
    'xData' => 'modalEquipos',
    'form' => <<<HTML
            <fieldset class="row">
                <label class="form-label col">Código
                    <input class="form-control" type="text" name="codigo" required placeholder="Código del equipo">
                    <small x-text="errors.codigo"></small>
                </label>

                <label class="form-label col">Nombre
                    <input class="form-control" type="text" name="nombre" required placeholder="Nombre del equipo">
                    <small x-text="errors.nombre"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="form-label col">Tipo
                    <input class="form-control" type="text" name="tipo" placeholder="Ej. Diagnóstico, Soporte vital">
                    <small x-text="errors.tipo"></small>
                </label>

                <label class="form-label col">Ubicación
                    <input class="form-control" type="text" name="ubicacion" placeholder="Área o sala">
                    <small x-text="errors.ubicacion"></small>
                </label>
            </fieldset>

            <fieldset class="row">
                <label class="form-label col">Estado
                    <select class="form-select" name="estado" required>
                        <option value="">Seleccione un estado…</option>
                        <option value="Operativo">Operativo</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Fuera de Servicio">Fuera de Servicio</option>
                    </select>
                    <small x-text="errors.estado"></small>
                </label>
            </fieldset>
        HTML,
]);
?>

<?= $this->insert('card', [
    'title' => $title,
    'icon' => 'fa-tools',
    'body' => <<<HTML
            <main>
                {$this->fetch('crudTable', ['xData' => 'crudEquipos'])}
            </main>
            {$modalForm}
        HTML,
]) ?>
