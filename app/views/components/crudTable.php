<?php
$alpineComponent = $alpineComponent ?? 'crudTable';
$this->pushJs('/assets/components/crudTable/crudTable.js');
?>

<div
    x-data="<?= $alpineComponent ?>"
    x-ref="table"
    @form-success.window="handleFormSuccess($event.detail)" 
    class="overflow-auto"
></div>
