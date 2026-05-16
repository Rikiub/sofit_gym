<?php

use App\Helpers\Response;
use CuyZ\Valinor\Mapper\MappingError;
use DI\ContainerBuilder;

// Constantes
const CONTAINER_FILE = 'app/container.php';
const CONTROLLERS_PATH = 'App\Controllers';

$response = new Response(normalizer: null);

// Configurar inyector de dependencias (PHP-DI).
// Dependiendo de las dependencias que tengan en los __contruct de los controladores
// el inyector las instanciara automaticamente con la configuración definida
// en el archivo CONTAINER_FILE.
$builder = new ContainerBuilder();
$builder->addDefinitions(CONTAINER_FILE)->useAttributes(true);
$container = $builder->build();

// FRONT CONTROLLER
try {
    // Verificar si esta pidiendo JSON
    $wantsJson = $_GET['format'] ?? '' == 'json' ?? $response->isJson();

    // Obtener query params
    $page = $_GET['page'] ?? 'inicio';
    $action = $_GET['action'] ?? 'index';

    // Construir clase a partir de los query params
    $className = ucfirst($page) . 'Controller';
    $classPath = '\\' . CONTROLLERS_PATH . "\\$className";

    if (!class_exists($classPath)) {
        if ($wantsJson) {
            // Si no se encuentra la pagina, devolver error como JSON
            echo $response->json([
                'error' => 'Not Found',
                'message' => "Controller {$className} not founded",
                'controller' => $classPath,
            ], 404);
        } else {
            // Si no se encuentra la pagina, redirigir a pagina de error.
            $response->redirect([
                'page' => 'error',
                'status' => 404,
            ]);
        }
        exit;
    }

    // Instanciar controlador e inyectar sus dependencias automaticamente
    $controller = $container->get($classPath);

    if (!method_exists($controller, $action)) {
        throw new Exception("Method '$action' not founded in controller '$className'");
    }

    // Ejecutar controlador junto a su metodo.
    $respuesta = $controller->$action();

    // Mostrar respuesta como string
    // Si es HTML, el navegador lo renderizara.
    echo $respuesta;
} catch (MappingError $error) {
    // Capturar errores de Valinor

    $messages = $error->messages();
    $errors = [];

    foreach ($messages as $m) {
        array_push($errors, [
            'name' => $m->name(),
            'source' => $m->sourceValue(),
            'expected' => $m->expectedSignature(),
        ]);
    }

    echo $response->json([
        'error' => 'Validation Error',
        'message' => 'The request contains invalid data',
        'errors' => $errors
    ], 400);
} catch (Throwable $error) {
    // Capturar todos los errores y convertirlos en JSON
    echo $response->json([
        'error' => 'Internal Server Error',
        'message' => $error->getMessage(),
    ], 500);
}
