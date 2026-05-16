<?php

/** @var array $formMeta */
$this->layout('layout', ['titulo' => 'Clientes']);
$this->pushJs('/assets/paginas/clientes/clientes.js');
$this->pushCss('/assets/paginas/clientes/clientes.css');
?>

<h1 class="title">Clientes</h1>

<div>
    <?= $this->insert('crudTable', ['alpineComponent' => 'crudTableClientes']) ?>
    
    <?= $this->insert('modalForm', [
        'alpineComponent' => 'modalFormClientes',
        'formHtml' => $this->fetch('clientes/forms/cliente', [
            'formMeta' => $formMeta,
        ]),
    ]) ?>
</div>