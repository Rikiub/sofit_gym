<?php
if (!isset($alpineComponent)) {
    $options = $options ?? '';
    $options = json_encode($options);
    $alpineComponent = htmlspecialchars("crudTable($options)", ENT_QUOTES);
} else {
    $alpineComponent = $alpineComponent;
}

$this->pushJs('/assets/parciales/crudTable/crudTable.js');
?>

<div
    x-data="<?= $alpineComponent ?>"
    x-ref="table"
    @form-success.window="handleFormSuccess($event.detail)" 
    class="overflow-auto"
></div>
