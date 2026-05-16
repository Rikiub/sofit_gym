<?php

namespace App\Controller;

use App\Controllers\BaseControlador;

class InicioControlador extends BaseControlador
{
    public function index(): string
    {
        return $this->render('inicio/index');
    }
}
