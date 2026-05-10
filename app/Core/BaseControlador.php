<?php

namespace App\Core;

use CuyZ\Valinor\Mapper\TreeMapper;
use DI\Attribute\Inject;
use League\Plates\Engine;

abstract class BaseControlador
{
    #[Inject]
    protected Engine $templates;

    #[Inject]
    protected TreeMapper $mapper;

    #[Inject]
    protected Response $response;

    protected function render(string $vista, array $datos = []): string
    {
        return $this->templates->render($vista, $datos);
    }
}
