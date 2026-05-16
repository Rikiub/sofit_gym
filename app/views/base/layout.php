<?php

/**
 * Layout principal que incorpora la barra lateral.
 * Es el que se utiliza en la mayoria de vistas.
 *
 * Para utilizarlo, escribe $this->layout('layout')
 * En cualquier vista.
 */
$this->layout('base', ['title' => $titulo ?? null]);
$this->pushCss('/assets/base/layout/layout.css');
?>

<div class="layout-default">
    <?= $this->insert('sidebar') ?>

    <div class="layout-default-content">
        <?= $this->section('content') ?>
    </div>
</div>
