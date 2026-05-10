<?php

namespace App\Core;

use CuyZ\Valinor\Normalizer\Normalizer;
use Exception;

class Response
{
    public function __construct(
        public ?Normalizer $normalizer
    ) {}

    public static function getQueryParams(): array
    {
        return $_GET;
    }

    /**
     * Intentar obtener los datos desde el POST o JSON
     */
    public static function getParsedBody(): array
    {
        // Si el contenido es JSON, entonces decodificarlo.
        if (Response::isJson()) {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON invalido');
            }

            return $data;
        }

        // Si el contenido es form POST, entonces devolver directamente
        return $_POST;
    }

    public static function empty(int $status = 204): null
    {
        http_response_code($status);
        return null;
    }

    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: $url");
    }

    /**
     * Codifica los datos en una JSON string. Si no se proporciono un normalizador, fallback a jscon_encode.
     */
    public function json(mixed $data, int $status = 200): string
    {
        Response::setJsonHeaders($status);

        if ($this->normalizer) {
            return $this->normalizer->normalize($data);
        } else {
            return json_encode($data);
        }
    }

    public static function setJsonHeaders(int $status)
    {
        header('Content-Type: application/json');
        http_response_code($status);
    }

    public static function isJson(): bool
    {
        return $_SERVER['CONTENT_TYPE'] ?? '' == 'application/json';
    }
}
