<?php
$this->layout('layout', ['title' => 'Clientes']);
$this->pushJs('pages/clientes/clientes.js');

$modalForm = $this->fetch('modalForm', [
    'alpineComponent' => 'modalClientes',
    'formHtml' => $this->fetch('clientes/forms/cliente'),
]);
?>

<?= $this->insert('card', [
    'title' => 'Clientes',
    'children' => <<<HTML
            <main>
                {$this->fetch('crudTable', ['alpineComponent' => 'crudClientes'])}
            </main>
            {$modalForm}
        HTML,
]) ?>