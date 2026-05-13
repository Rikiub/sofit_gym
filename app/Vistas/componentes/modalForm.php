<?php
$formHtml = $formHtml ?? '';

if (!isset($alpineComponent)) {
    $options = $options ?? '';
    $options = json_encode($options);
    $alpineComponent = htmlspecialchars("modalForm($options)", ENT_QUOTES);
} else {
    $alpineComponent = $alpineComponent;
}

$this->pushJs('/assets/componentes/modalForm/modalForm.js');
?>

<dialog
    x-data="<?= $alpineComponent ?>"
    x-ref="modal"
    x-id="['form']"
    :closedby="loading ? 'none' : 'any'"
    @open-modal.window="handleOpenModal($event.detail)"
>
    <article>
        <header>
            <button
                aria-label="Cerrar"
                rel="prev"
                @click="$refs.modal.close()"
            ></button>

            <h3 x-show="mode == 'add'">Crear</h3>
            <h3 x-show="mode == 'edit'">Editar</h3>
            <h3 x-show="mode == 'delete'">Eliminar</h3>
        </header>

        <p x-show="mode == 'delete'">
            ¿Seguro que quieres eliminarlo?
        </p>

        <form
            x-show="mode !== 'delete'" x-ref="form"
            @submit.prevent="handleSubmit"
            :id="$id('form')"
            novalidate
        >
            <?= $formHtml ?>
        </form>

        <footer>
            <button
                x-show="mode !== 'delete'"
                :form="$id('form')"
                :aria-busy="loading"
                :disabled="loading"
            >Enviar</button>

            <div x-show="mode == 'delete'">
                <button class="secondary" @click="$refs.modal.close()">No</button>
                <button
                    :form="$id('form')"
                    :aria-busy="loading" 
                    :disabled="loading"
                >Si</button>
            </div>
        </footer>
    </article>
</dialog>