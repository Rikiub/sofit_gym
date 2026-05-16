<?php

namespace App\Controllers;

use App\Helpers\Response;
use CuyZ\Valinor\Mapper\TreeMapper;
use DI\Attribute\Inject;
use League\Plates\Engine;

abstract class BaseController
{
    #[Inject]
    protected Response $response;

    #[Inject]
    protected Engine $templates;

    #[Inject]
    protected TreeMapper $mapper;

    /**
     * Deben pasar el nombre de un archivo en la carpeta views/
     * y los datos a utilizar en un array.
     */
    protected function render(string $view, array $data = []): string
    {
        return $this->templates->render($view, $data);
    }

    protected function redirectToError($message = '', int $status = 404)
    {
        $this->response->redirect(
            [
                'page' => 'error',
                'message' => $message,
                'status' => $status,
            ],
            404
        );
    }
}
