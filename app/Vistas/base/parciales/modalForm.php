<?php
$formHtml = $formHtml ?? '';

$options = $options ?? [];
$options = htmlspecialchars(json_encode($options), ENT_QUOTES, 'UTF-8');
$alpineComponent = $alpineComponent ?? "modalForm($options)";

$this->pushJs('/assets/base/parciales/modalForm/modalForm.js');
?>

<dialog
    x-data="<?= $alpineComponent ?>"
    x-ref="modal"
    x-id="['form']"
    @open-modal.window="$event.detail"
>
    <article>
        <header>
            <button
                aria-label="Cerrar"
                rel="prev"
                @click="$refs.modal.close()"
            ></button>

            <h3 x-show="method == 'POST'">Crear</h3>
            <h3 x-show="method == 'PUT'">Editar</h3>
            <h3 x-show="method == 'DELETE'">Eliminar</h3>
        </header>

        <p x-show="method == 'DELETE'">
            ¿Seguro que quieres eliminarlo?
        </p>

        <form
            x-show="method !== 'DELETE'" x-ref="form"
            @submit.prevent="handleSubmit"
            :id="$id('form')"
            novalidate
        >
            <?= $formHtml ?>
        </form>

        <footer>
            <button
                x-show="method !== 'DELETE'"
                :form="$id('form')"
                :aria-busy="loading"
                :disabled="loading"
            >Enviar</button>

            <div x-show="method == 'DELETE'">
                <button class="secondary" @click="$refs.modal.close()">No</button>
                <button
                    x-ref="button_delete"
                    :form="$id('form')"
                    :aria-busy="loading" 
                    :disabled="loading"
                >Si</button>
            </div>
        </footer>
    </article>
</dialog>