<?php

/** @var array $formMeta */
$this->layout('layout', ['title' => 'Clientes']);

$this->pushJs('pages/clientes/clientes.js');
$this->pushCss('pages/clientes/clientes.css');

$modalForm = $this->fetch('modalForm', [
    'alpineComponent' => 'modalClientes',
    'formHtml' => $this->fetch('clientes/forms/cliente', [
        'formMeta' => $formMeta,
    ]),
])
?>

<?= $this->insert(
    "card",
    [
        "cardTitle" => "Clientes",
        "children" => <<<HTML
            {$this->fetch('crudTable', ['alpineComponent' => 'crudClientes'])}
            {$modalForm}
        HTML
    ]
) ?>