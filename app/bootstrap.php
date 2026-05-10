<?php

use App\Core\Response;
use CuyZ\Valinor\Mapper\MappingError;
use DI\ContainerBuilder;

// Constantes
const CONTAINER_FILE = 'config/container.php';
const RUTAS_FILE = 'config/rutas.php';

// Configurar rutas
$dispatcher = FastRoute\simpleDispatcher(require RUTAS_FILE);

// Obtener metodo y URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$uri = rawurldecode(parse_url($uri, PHP_URL_PATH));

// Configurar inyector de dependencias (PHP-DI)
$builder = new ContainerBuilder();
$builder->addDefinitions(CONTAINER_FILE)->useAttributes(true);
$container = $builder->build();

$response = new Response(null);
$rutaInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($rutaInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        if ($response->isJson()) {
            echo $response->json([
                'error' => 'Not Found',
                'message' => "Route {$uri} not founded",
                'uri' => $uri,
            ], 404);
        } else {
            // Si no se encuentra la ruta, redirigir a pagina de error.
            $response->redirect('/error?status=404', 404);
        }
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        if ($response->isJson()) {
            echo $response->json([
                'error' => 'Not Allowed',
                'message' => "Route {$uri} not allowed",
                'uri' => $uri,
            ], 404);
        } else {
            // Si el metodo no se permite, redirigir a pagina de error.
            $response->redirect('/error?status=405', 405);
        }
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $rutaInfo[1];
        $vars = $rutaInfo[2];

        [$clase, $metodo] = $handler;

        try {
            if (!class_exists($clase))
                throw new Exception("Clase-controlador '$clase' no encontrado");

            // Obtener controlador e inyectar sus dependencias
            $controlador = $container->get($clase);

            // Ejecutar controlador junto a su metodo.
            $respuesta = $controlador->$metodo($vars);

            // Mostrar respuesta como string
            // Si es HTML, el navegador lo renderizara.
            echo $respuesta;
        } catch (MappingError $error) {
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
            echo $response->json([
                'error' => 'Internal Server Error',
                'message' => $error->getMessage()
            ], 500);
        }

        break;
}
