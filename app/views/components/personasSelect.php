<?php
// Props
$required ??= false;
$name ??= "";

$this->pushJs("components/personasSelect/personasSelect.js");
?>

<div x-data="personasSelect">
    <input hidden <?= $required ? 'required' : '' ?> name="<?= $name ?>" x-model="selectedCedula">
    <button
        type="button"
        class="form-select"
        @click="togglePopover()"
        x-ref="selectButton"
        x-text="selectedCedula || 'Seleccionar persona'"></button>

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
                            <th>Rol</th>
                            <th>Accion</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="item in items" :key="item.cedula">
                            <tr>
                                <td x-text="item.cedula"></td>
                                <td x-text="`${item.nombre} ${item.apellido}`"></td>
                                <td x-text="item.rol"></td>
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