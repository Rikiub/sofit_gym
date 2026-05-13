<?php
$this->layout('base', ['titulo' => $titulo ?? null]);
$this->pushCss('/assets/base/layout/layout.css');
?>

<div class="layout-default">
    <?= $this->insert('parciales/sidebar') ?>

    <div class="layout-default-content">
        <?= $this->section('content') ?>
    </div>
</div>
