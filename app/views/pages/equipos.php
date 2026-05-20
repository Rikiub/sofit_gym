<?php
$title = "Gestion de equipos";
$this->layout("layout", ["title" => $title]);
$this->pushJs("pages/equipos/equipos.js");
?>

<main class="container">
    <article class="card main-card">
        <header class="card-header header d-flex justify-content-start gap-2">
            <i class="fas fa-door-open"></i>
            <h3 class="card-title fw-semibold"><?= $title ?></h3>
        </header>

        <?= $this->insert('crudTable', ['alpineComponent' => 'crudEquipos']) ?>
        <?= $this->insert('modalForm', [
            'alpineComponent' => 'modalEquipos',
            'formHtml' => <<<HTML
                <fieldset class="grid">
                    <label>Código
                        <input type="text" name="codigo" required placeholder="Código del equipo">
                        <small x-text="errors.codigo"></small>
                    </label>

                    <label>Nombre
                        <input type="text" name="nombre" required placeholder="Nombre del equipo">
                        <small x-text="errors.nombre"></small>
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Tipo
                        <input type="text" name="tipo" placeholder="Ej. Diagnóstico, Soporte vital">
                        <small x-text="errors.tipo"></small>
                    </label>

                    <label>Ubicación
                        <input type="text" name="ubicacion" placeholder="Área o sala">
                        <small x-text="errors.ubicacion"></small>
                    </label>
                </fieldset>

                <fieldset class="grid">
                    <label>Estado
                        <select name="estado" required>
                            <option value="">Seleccione un estado…</option>
                            <option value="Operativo">Operativo</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                        </select>
                        <small x-text="errors.estado"></small>
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="activo" value="1" checked>
                        Activo
                    </label>
                </fieldset>
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