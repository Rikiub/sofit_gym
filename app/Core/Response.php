<?php

namespace App\Core;

use Exception;

class Response
{
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

    protected function toEmptyBody(int $code = 200): null
    {
        http_response_code($code);
        return null;
    }

    public static function toJson(mixed $data, int $code = 200): string
    {
        Response::setJsonHeaders($code);
        return json_encode($data);
    }

    public static function setJsonHeaders(int $code)
    {
        header('Content-Type: application/json');
        http_response_code($code);
    }

    public static function isJson(): bool
    {
        return $_SERVER['CONTENT_TYPE'] ?? '' == 'application/json';
    }
}
