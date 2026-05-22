<?php
$formHtml = $formHtml ?? '';
$alpineComponent = $alpineComponent ?? 'modalForm';

$this->pushJs('components/modalForm/modalForm.js');
?>

<div
    class="modal"
    tabindex="-1"
    x-ref="modal"
    x-data="<?= $alpineComponent ?>"
    x-id="['form']"
    :closedby="loading ? 'none' : 'any'"
    @open-modal.window="handleOpenModal($event.detail)">
    <div class="modal-dialog modal-lg">
        <article class="modal-content">
            <header class="modal-header">
                <h3 class="modal-title" x-show="mode == 'add'">Crear</h3>
                <h3 class="modal-title" x-show="mode == 'edit'">Editar</h3>
                <h3 class="modal-title" x-show="mode == 'delete'">Eliminar</h3>

                <button type="button" class="btn-close" @click="closeModal()" aria-label="Close"></button>
            </header>

            <div class="modal-body">
                <p x-show="mode == 'delete'">
                    ¿Seguro que quieres eliminarlo?
                </p>

                <form
                    x-show="mode !== 'delete'" x-ref="form"
                    @submit.prevent="handleSubmit"
                    :id="$id('form')"
                    novalidate>
                    <?= $formHtml ?>
                </form>
            </div>

            <footer class="modal-footer">
                <button
                    class="btn btn-primary"
                    x-show="mode !== 'delete'"
                    :form="$id('form')"
                    :aria-busy="loading"
                    :disabled="loading">Enviar</button>

                <div x-show="mode == 'delete'">
                    <button class="btn btn-secondary" @click="closeModal()">No</button>
                    <button
                        class="btn btn-danger"
                        :form="$id('form')"
                        :aria-busy="loading"
                        :disabled="loading">Si</button>
                </div>
            </footer>
        </article>
    </div>
</div>