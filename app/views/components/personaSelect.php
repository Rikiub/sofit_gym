<?php
// Props
$required ??= false;
$name ??= "";

$this->pushJs("components/personaSelect/personaSelect.js");
?>

<div x-data="personaSelect">
    <input hidden <?= $required ? 'required' : '' ?> name="<?= $name ?>" x-model="selectedCedula">
    <button
        type="button"
        @click="togglePopover()"
        x-ref="selectButton"
        x-text="selectedCedula || 'Seleccionar persona...'"
        class="form-select text-start"
        :class="{ 'text-body-secondary': !selectedCedula }"></button>

    <div x-ref="popoverContainer">
        <div x-ref="popoverContent" style="display: none;">
            <search>
                <input
                    type=" text"
                    class="form-control mb-2"
                    placeholder="Buscar..."
                    @input.debounce.300ms="handleSearch"
                    x-model="search">
            </search>

            <ul class="list-unstyled mb-0">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Accion</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="item in items" :key="item.cedula">
                            <tr>
                                <td x-text="item.cedula"></td>
                                <td x-text="`${item.nombre} ${item.apellido}`"></td>
                                <td><button type="button" class="btn btn-sm btn-secondary" @click="setSelected(item)">Seleccionar</button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </ul>
        </div>
    </div>
</div>

<style>
    .select-popover {
        --bs-popover-max-width: 450px;
        width: 450px;
    }
</style>