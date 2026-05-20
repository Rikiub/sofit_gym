<?php
$title = "Historial de mantenimientos";
$this->layout("layout", ["title" => $title]);
$this->pushJs("pages/equipos/equipos_mantenimiento.js");
?>

<main class="container" x-data="mainData">
    <article class="card main-card">
        <header class="card-header header d-flex justify-content-start gap-2">
            <i class="fas fa-door-open"></i>
            <h3 class="card-title fw-semibold"><?= $title ?></h3>
        </header>

        <?= $this->insert('crudTable', ['alpineComponent' => 'crudMantenimiento']) ?>
        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalMantenimiento',
            'formHtml' => <<<HTML
                <input name="id" hidden>

                <fieldset class="grid">
                    <label>Equipo
                        <select name="codigo_equipo" required placeholder="Equipo">
                            <template x-for="item in equipos" :key="item.codigo">
                                <option :value="item.codigo" x-text="item.codigo + ': ' + item.nombre"></option>
                            </template>
                        </select>
                        <small x-text="errors.codigo_equipo"></small>
                    </label>

                    <label>Fecha
                        <input type="date" name="fecha" required>
                        <small x-text="errors.fecha"></small>
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Tipo de Mantenimiento
                        <select name="tipo" required>
                            <option value="">Seleccione un tipo…</option>
                            <option value="Preventivo">Preventivo</option>
                            <option value="Correctivo">Correctivo</option>
                            <option value="Predictivo">Predictivo</option>
                            <option value="Calibración">Calibración</option>
                        </select>
                        <small x-text="errors.tipo"></small>
                    </label>

                    <label>Costo
                        <input type="number" name="costo" step="any" min="0" x-mask="999999.99" placeholder="0.00">
                        <small x-text="errors.costo"></small>
                    </label>
                </fieldset>

                <label>Técnico
                    <input type="text" name="tecnico" placeholder="Nombre del técnico responsable">
                    <small x-text="errors.tecnico"></small>
                </label>

                <hr>

                <label>Descripción
                    <textarea name="descripcion" placeholder="Detalles del mantenimiento realizado" rows="3"></textarea>
                    <small x-text="errors.descripcion"></small>
                </label>
            HTML,
        ]) ?>
    </article>
</main>

<style>
    .container {
        --bs-gutter-x: 0;
        max-width: 1100px;
        margin: 0 auto;
        border-radius: 28px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .header {
        background: #C62828;
        color: white;
        padding: 1.2rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .card-body {
        padding: 1.5rem;
    }
</style>