<?php

/**
 * Layout principal que incorpora la barra lateral.
 * Es el que se utiliza en la mayoria de vistas.
 *
 * Para utilizarlo, escribe:
 * $this->layout('layout')
 * En cualquier vista.
 */
$this->layout('base', ['title' => $titulo ?? null]);
$this->pushCss('base/layout/layout.css');

$backgroundImage = ASSETS_DIR . '/base/layout/background.webp';
?>

<div class="layout-default" style="background-image: url('<?= $backgroundImage ?>');">
    <?= $this->insert('sidebar') ?>

    <div class="layout-default-content">
        <?= $this->section('content') ?>
    </div>
</div>
