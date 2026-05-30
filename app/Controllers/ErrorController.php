<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ErrorController extends BaseController
{
    public function index(): string
    {
        $status = $_GET['status'] ?? '';
        $message = '';

        if ($status == '404') {
            $message = '404: Pagina no encontrada';
        } else if ($status == '405') {
            $message = '405: Metodo no soportado';
        } else if ($status == "500") {
            $message = '500: Ocurrio un error inesperado en el servidor';
        }

        return $this->templates->render('error', ['message' => $message]);
    }
}
