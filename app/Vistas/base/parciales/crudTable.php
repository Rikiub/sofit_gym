<?php
$this->pushJs('/assets/base/parciales/crudTable/crudTable.js');

$options = $options ?? [];
$options = htmlspecialchars(json_encode($options), ENT_QUOTES, 'UTF-8');
?>

<div
    x-data="crudTable(<?= $options ?>)"
    x-ref="table"
    @form-success.window="refreshGrid" 
    class="overflow-auto"
></div>
