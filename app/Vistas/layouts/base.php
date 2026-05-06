<?

/**
 * HTML base que heredan todas las vistas. Debe mantenerse lo más simple posible.
 * Tambien es donde se insertan las dependencias web.
 */
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/assets/img/favicon.png">
    <link rel="stylesheet" href="/assets/css/index.css">

    <!-- Dependencias JavaScript -->
    <script type="importmap">
        {
            "imports": {
                "gridjs": "/lib/gridjs/gridjs.module.js"
            }
        }
    </script>
    <link rel="stylesheet" href="/lib/gridjs/mermaid.min.css"> <!-- GridJS -->

    <!-- TODO: Esto deberia guardarse como archivos en la carpeta "lib" -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&display=swap">

    <!-- Componentes globales -->
    <script defer src="/assets/componentes/sidebar/script.js"></script>
    <link rel="stylesheet" href="/assets/componentes/sidebar/styles.css">

    <?= $this->section('head') ?>

    <title><?= $this->e($titulo ?? 'Sofit Gym') ?></title>
</head>

<body>
    <div id="app">
        <?= $this->section('content') ?>
    </div>
</body>

<style>
    html {
        overflow-y: scroll;
        scrollbar-width: thin;
    }
</style>