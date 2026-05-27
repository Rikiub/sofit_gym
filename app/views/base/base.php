<?

/**
 * HTML base que heredan todas las vistas. Debe mantenerse lo más simple posible.
 * Tambien es donde se insertan las dependencias web.
 */
?>

<!DOCTYPE html>
<html lang="es" data-theme="light" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <link rel="icon" href="<?= ASSETS_DIR ?>/favicon.svg">

    <!-- Dependencias JavaScript -->
    <script type="importmap">
        {
            "imports": {
                "@/": "<?= ASSETS_DIR ?>/",

                "form-data-json": "<?= ASSETS_DIR ?>/lib/form-data-json/form-data-json.es6.js",
                "alpinejs": "<?= ASSETS_DIR ?>/lib/alpinejs/alpinejs.esm.min.js",
                "gridjs": "<?= ASSETS_DIR ?>/lib/gridjs/gridjs.module.js",
                
                "dayjs": "<?= ASSETS_DIR ?>/lib/dayjs/dayjs.esm.js",
                "@fullcalendar/core": "https://esm.sh/@fullcalendar/core@6.1.20",
                "@fullcalendar/core/locales/es": "https://esm.sh/@fullcalendar/core/locales/es",
                "@fullcalendar/daygrid": "https://esm.sh/@fullcalendar/daygrid@6.1.20",
                "@fullcalendar/bootstrap5": "https://esm.sh/@fullcalendar/bootstrap5@6.1.20"
            }
        }
    </script>

    <?php
    $this->pushJs('lib/alpinejs/mask.min.js'); // AlpineJS: Mask Plugin
    $this->pushCss('lib/gridjs/mermaid.min.css');  // GridJS

    // Bootstrap
    $this->pushCss("lib/bootstrap/bootstrap.min.css");
    $this->pushJs("lib/bootstrap/bootstrap.bundle.min.js", false);

    $this->pushCss("lib/font-awesome/css/all.min.css"); // Font Awesome

    // Importes globales
    $this->pushCss('base/index.css');
    $this->pushJs('base/index.js');
    ?>

    <?= $this->renderCss() ?>
    <?= $this->renderJs() ?>

    <style>
        /**
        Muestra siempre la scrollbar
        Para evitar flickering
        */
        html {
            overflow-y: scroll;
            scrollbar-width: thin;
        }

        body {
            /** 
            Remueve el padding extraño que agrega
            Boostrap al abrir un modal.
            */
            padding-right: 0 !important;
        }
    </style>

    <title><?= $this->e($title ?? 'Sofit Gym') ?></title>
</head>

<body>
    <div id="app">
        <?= $this->section('content') ?>
    </div>
</body>

<script type="module">
    import Alpine from 'alpinejs';
    window.Alpine = Alpine;
    Alpine.start();
</script>