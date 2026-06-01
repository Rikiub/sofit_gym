<?php

// Directorios
define('BASE_DIR', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
define('ASSETS_DIR', BASE_DIR . '/assets');

// Timezones
define("TIMEZONE", $_ENV["TIMEZONE"] ?? 'America/Caracas');
define('TIMEZONE_OFFSET', (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('P'));
date_default_timezone_set(TIMEZONE);

// Switch para desarrollo
define('DEBUG', $_ENV["DEBUG"] ?? true);

// Cargar composer autoload y front controller
require 'vendor/autoload.php';
require 'app/bootstrap.php';
