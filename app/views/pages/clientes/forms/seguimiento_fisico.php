<input name="id_seguimiento" hidden>

<fieldset class="grid">
    <label>Altura (cm)
        <input type="number" name="altura_cm" step="any" min="100" max="230" x-mask="999.9">
        <small x-text="errors.altura_cm"></small>
    </label>

    <label>Peso (kg)
        <input type="number" name="peso_kg" step="any" x-mask="99.9">
        <small x-text="errors.peso_kg"></small>
    </label>
</fieldset>

<fieldset class="grid">
    <label>Cintura (cm)
        <input type="number" name="cintura_cm" step="any" x-mask="99.9">
        <small x-text="errors.cintura_cm"></small>
    </label>

    <label>Cadera (cm)
        <input type="number" name="cadera_cm" step="any" x-mask="99.9">
        <small x-text="errors.cadera_cm"></small>
    </label>
</fieldset>

<fieldset class="grid">
    <label>Pecho (cm)
        <input type="number" name="pecho_cm" step="any" x-mask="99.9">
        <small x-text="errors.pecho_cm"></small>
    </label>

    <label>Muslo (cm)
        <input type="number" name="muslo_cm" step="any" x-mask="99.9">
        <small x-text="errors.muslo_cm"></small>
    </label>
</fieldset>

<fieldset class="grid">
    <label>Hombros (cm)
        <input type="number" name="hombros_cm" step="any" x-mask="99.9">
        <small x-text="errors.hombros_cm"></small>
    </label>

    <label>Pantorrilla (cm)
        <input type="number" name="pantorrilla_cm" step="any" x-mask="99.9">
        <small x-text="errors.pantorrilla_cm"></small>
    </label>
</fieldset>