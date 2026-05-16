<?

/**
 * HTML base que heredan todas las vistas. Debe mantenerse lo más simple posible.
 * Tambien es donde se insertan las dependencias web.
 */
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <link rel="icon" href="/assets/favicon.png">

    <!-- Dependencias JavaScript -->
    <script type="importmap">
        {
            "imports": {
                "gridjs": "/assets/lib/gridjs/gridjs.module.js",
                "alpinejs": "/assets/lib/alpinejs/alpinejs.esm.min.js",
                "form-data-json": "/assets/lib/form-data-json/form-data-json.es6.js"
            }
        }
    </script>

    <?php
    $this->pushJs('/assets/lib/alpinejs/mask.min.js');

    $this->pushCss('/assets/lib/gridjs/mermaid.min.css');  // GridJS
    $this->pushCss('/assets/lib/picocss/pico.red.min.css');  // PicoCSS
    $this->pushCss('/assets/lib/picocss/pico.colors.min.css');  // PicoCSS Colors

    // TODO: Deberia guardarse como archivos en la carpeta "lib"
    $this->pushCss('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    $this->pushCss('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&display=swap');

    // Importes globales
    $this->pushCss('/assets/base/index.css');
    $this->pushJs('/assets/base/index.js');
    ?>

    <?= $this->renderCss() ?>
    <?= $this->renderJs() ?>
    
    <script type="module">
        import Alpine from 'alpinejs'
        window.Alpine = Alpine;
        Alpine.start();
    </script>

    <style>
        html {
            overflow-y: scroll;
            scrollbar-width: thin;
        }
    </style>

    <title><?= $this->e($title ?? 'Sofit Gym') ?></title>
</head>

<body>
    <div id="app">
        <?= $this->section('content') ?>
    </div>
</body>

