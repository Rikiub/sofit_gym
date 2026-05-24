<?php

/**
 * Layout principal que incorpora la barra lateral.
 * Es el que se utiliza en la mayoria de vistas.
 *
 * Para utilizarlo, escribe:
 * $this->layout('layout')
 * En cualquier vista.
 */
$this->layout('base', ['title' => $title ?? null]);

$backgroundImage = ASSETS_DIR . '/base/background.webp';
?>

<div class="layout-root" style="background-image: url('<?= $backgroundImage ?>');">
    <?= $this->insert('sidebar') ?>

    <div class="layout-content">
        <?= $this->section('content') ?>
    </div>
</div>

<style>
    .layout-root {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;

        display: grid;
        grid-template-columns: auto 1fr;

        height: 100%;
        min-height: 100vh;

        .layout-content {
            margin: 1rem;
        }
    }
</style>