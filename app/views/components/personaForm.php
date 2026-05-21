<fieldset class="grid">
    <label>Cédula
        <input
            required
            name="cedula"
            type="text"
            pattern="^[V]-\d{8}$"
            x-mask="V-99999999"
            @input.debounce.500ms="validateCedula($el)">
        <small x-text="errors.cedula"></small>
    </label>

    <label>Nombre
        <input required name="nombre" type="text" @input.debounce="checkValidity($el)">
        <small x-text="errors.nombre"></small>
    </label>

    <label>Apellido
        <input required name="apellido" type="text" @input.debounce="checkValidity($el)">
        <small x-text="errors.apellido"></small>
    </label>
</fieldset>

<fieldset class="grid">
    <label>Teléfono
        <input
            required
            name="telefono"
            type="tel"
            x-mask="9999-9999999"
            pattern="04(12|14|16|24|26)-\d{7}"
            @input.debounce="checkValidity($el)">
        <small x-text="errors.telefono"></small>
    </label>

    <label>Correo
        <input required name="correo" type="email" @input.debounce="checkValidity($el)">
        <small x-text="errors.correo"></small>
    </label>

    <label>Dirección
        <input name="direccion" type="text" @input.debounce="checkValidity($el)">
        <small x-text="errors.direccion"></small>
    </label>
</fieldset>

<fieldset>
    <label>Fecha de nacimiento
        <input required name="fecha_nacimiento" type="date" @input.debounce="checkValidity($el)">
        <small x-text="errors.fecha_nacimiento"></small>
    </label>
</fieldset>