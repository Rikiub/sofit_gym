<?php

/** @var array $formMeta */
?>

<?= $this->fetch("personaForm") ?>

<hr>

<fieldset class="grid">
    <label>Tipo de membresia
        <select required name="membresia[id_tipo]">
            <?php foreach ($formMeta['tipos'] as $item): ?>
                <option value="<?= $item['id_tipo'] ?>">
                    <?= $item['nombre'] ?>
                </option>
            <?php endforeach ?>
        </select>
    </label>

    <label>Estado de membresia
        <select required name="membresia[id_estado]">
            <?php foreach ($formMeta['estados'] as $item): ?>
                <option value="<?= $item['id_estado'] ?>">
                    <?= $item['nombre'] ?>
                </option>
            <?php endforeach ?>
        </select>
    </label>
</fieldset>

<fieldset class="grid">
    <label>Fecha de inicio de membresía
        <input required name="membresia[fecha_inicio]" type="date" @input.debounce="checkValidity($el)">
        <small x-text="errors['membresia[fecha_inicio]']"></small>
    </label>

    <label>Fecha de fin de membresía
        <input required name="membresia[fecha_fin]" type="date" @input.debounce="checkValidity($el)">
        <small x-text="errors['membresia[fecha_fin]']"></small>
    </label>
</fieldset>