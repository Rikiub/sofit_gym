<?php

// Constantes globales
define('BASE_DIR', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
define('ASSETS_DIR', BASE_DIR . '/assets');
define('DEBUG', true);

// Cargar composer autoload y front controller
require 'vendor/autoload.php';
require 'app/bootstrap.php';
