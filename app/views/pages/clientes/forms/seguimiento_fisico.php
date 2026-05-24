<input name="id_seguimiento" hidden>

<fieldset class="row">
    <label class="form-label col">Fecha de seguimiento
        <input class="form-control" type="date" name="fecha" required>
        <small x-text="errors.fecha"></small>
    </label>
</fieldset>

<hr>

<fieldset class="row">
    <label class="form-label col">Altura (cm)
        <input class="form-control" type="number" name="altura_cm" step="any" min="100" max="230" x-mask="999" placeholder="000">
        <small x-text="errors.altura_cm"></small>
    </label>

    <label class="form-label col">Peso (kg)
        <input class="form-control" type="number" name="peso_kg" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.peso_kg"></small>
    </label>
</fieldset>

<fieldset class="row">
    <label class="form-label col">Cintura (cm)
        <input class="form-control" type="number" name="cintura_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.cintura_cm"></small>
    </label>

    <label class="form-label col">Cadera (cm)
        <input class="form-control" type="number" name="cadera_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.cadera_cm"></small>
    </label>
</fieldset>

<fieldset class="row">
    <label class="form-label col">Pecho (cm)
        <input class="form-control" type="number" name="pecho_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.pecho_cm"></small>
    </label>

    <label class="form-label col">Muslo (cm)
        <input class="form-control" type="number" name="muslo_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.muslo_cm"></small>
    </label>
</fieldset>

<fieldset class="row">
    <label class="form-label col">Hombros (cm)
        <input class="form-control" type="number" name="hombros_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.hombros_cm"></small>
    </label>

    <label class="form-label col">Pantorrilla (cm)
        <input class="form-control" type="number" name="pantorrilla_cm" step="any" x-mask="99.9" placeholder="0.0">
        <small x-text="errors.pantorrilla_cm"></small>
    </label>
</fieldset>