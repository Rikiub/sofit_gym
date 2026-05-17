<?php

/** @var array $formMeta */
$this->layout('layout', ['title' => 'Clientes']);
$this->pushJs('pages/clientes/clientes.js');
$this->pushCss('pages/clientes/clientes.css');
?>

<h1 class="title">Clientes</h1>

<div>
    <?= $this->insert('crudTable', ['alpineComponent' => 'crudClientes']) ?>

    <?= $this->insert('modalForm', [
        'alpineComponent' => 'modalClientes',
        'formHtml' => $this->fetch('clientes/forms/cliente', [
            'formMeta' => $formMeta,
        ]),
    ]) ?>
</div>

<script>
    let xd = "";
</script>