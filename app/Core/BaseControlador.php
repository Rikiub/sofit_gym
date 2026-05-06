<?php

namespace App\Core;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Normalizer\Normalizer;
use DI\Attribute\Inject;
use League\Plates\Engine;
use Exception;

abstract class BaseControlador
{
    #[Inject]
    protected Engine $templates;

    #[Inject]
    protected TreeMapper $mapper;

    #[Inject]
    private Normalizer $normalizer;

    public function render(string $vista, array $datos = []): string
    {
        return $this->templates->render($vista, $datos);
    }

    /**
     * Convertir los datos en una JSON string y setear los headers
     */
    function jsonResponse(mixed $data, int $code = 200): string
    {
        header('Content-Type: application/json');
        http_response_code($code);
        return $this->normalizer->normalize($data);
    }

    /**
     * Intentar obtener los datos desde el POST o JSON
     */
    function getRequestBody(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // Si el contenido es JSON, entonces decodificarlo.
        if (stripos($contentType, 'application/json') !== false) {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }

            throw new Exception('JSON invalido');
        }

        // Si el contenido es form POST, entonces devolver directamente
        return $_POST;
    }
}
