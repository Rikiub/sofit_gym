<input name="id_seguimiento" hidden>

<fieldset class="row">
    <label class="form-label col">Fecha de seguimiento
        <input class="form-control" type="date" name="fecha" required>
        <small x-text="errors.fecha"></small>
    </label>
</fieldset>

<hr>

<fieldset class="row">
    <label class="form-label col">Proteínas (g)
        <input class="form-control" type="number" name="proteinas_g" step="any" min="0" x-mask="999.9" placeholder="0.0">
        <small x-text="errors.proteinas_g"></small>
    </label>

    <label class="form-label col">Carbohidratos (g)
        <input class="form-control" type="number" name="carbohidratos_g" step="any" min="0" x-mask="999.9" placeholder="0.0">
        <small x-text="errors.carbohidratos_g"></small>
    </label>
</fieldset>

<fieldset class="row">
    <label class="form-label col">Grasas (g)
        <input class="form-control" type="number" name="grasas_g" step="any" min="0" x-mask="999.9" placeholder="0.0">
        <small x-text="errors.grasas_g"></small>
    </label>

    <label class="form-label col">Calorías diarias (kcal)
        <input class="form-control" type="number" name="calorias_diarias" step="any" min="0" x-mask="9999.9" placeholder="0.0">
        <small x-text="errors.calorias_diarias"></small>
    </label>
</fieldset>