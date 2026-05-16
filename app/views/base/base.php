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
    <link rel="icon" href="<?= ASSETS_DIR ?>/favicon.png">

    <!-- Dependencias JavaScript -->
    <script type="importmap">
        {
            "imports": {
                "@/": "<?= ASSETS_DIR ?>/",
                "gridjs": "<?= ASSETS_DIR ?>/lib/gridjs/gridjs.module.js",
                "alpinejs": "<?= ASSETS_DIR ?>/lib/alpinejs/alpinejs.esm.min.js",
                "form-data-json": "<?= ASSETS_DIR ?>/lib/form-data-json/form-data-json.es6.js"
            }
        }
    </script>

    <?php
    $this->pushJs('lib/alpinejs/mask.min.js');

    $this->pushCss('lib/gridjs/mermaid.min.css');  // GridJS
    $this->pushCss('lib/picocss/pico.red.min.css');  // PicoCSS
    $this->pushCss('lib/picocss/pico.colors.min.css');  // PicoCSS Colors

    // TODO: Deberia guardarse como archivos en la carpeta "lib"
    $this->pushCss('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    $this->pushCss('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&display=swap');

    // Importes globales
    $this->pushCss('base/index.css');
    $this->pushJs('base/index.js');
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

