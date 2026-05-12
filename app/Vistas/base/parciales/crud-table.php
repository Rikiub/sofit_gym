<?php
$alpineComponent = $alpineComponent ?? '{}';
$formHtml = $formHtml ?? '';
?>

<div x-data="<?= $alpineComponent ?>">
    <dialog x-ref="modal" x-id="['form']">
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

    <div class="overflow-auto" x-ref="table"></div>
</div>
