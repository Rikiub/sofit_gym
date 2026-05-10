<?php

namespace App\Core;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Normalizer\Normalizer;
use DI\Attribute\Inject;
use League\Plates\Engine;

abstract class BaseControlador
{
    #[Inject]
    protected Engine $templates;

    #[Inject]
    protected TreeMapper $mapper;

    #[Inject]
    private Normalizer $normalizer;

    protected function render(string $vista, array $datos = []): string
    {
        return $this->templates->render($vista, $datos);
    }

    /**
     * Intentar obtener los datos desde el POST o JSON
     */
    protected function getParsedBody()
    {
        return Response::getParsedBody();
    }

    /**
     * Convertir los datos en una JSON string y setear los headers
     */
    protected function jsonResponse(mixed $data, int $code = 200): string
    {
        Response::setJsonHeaders($code);
        return $this->normalizer->normalize($data);
    }

    protected function emptyBodyResponse(int $code = 200): null
    {
        http_response_code($code);
        return null;
    }
}
