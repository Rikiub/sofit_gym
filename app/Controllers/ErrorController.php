<?php

namespace App\Controller;

use App\Controllers\BaseControlador;

class ErrorControlador extends BaseControlador
{
    public function index(): string
    {
        $status = $this->response->getQueryParams()['status'] ?? '';
        $mensaje = '';

        if ($status == '404') {
            $mensaje = '404: Pagina no encontrada';
        } else if ($status == '405') {
            $mensaje = '405: Metodo no soportado';
        }

        return $this->render('error/index', ['mensaje' => $mensaje]);
    }
}
