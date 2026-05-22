<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class InicioController extends BaseController
{
    public function index(): string
    {
        return $this->templates->render('inicio');
    }
}
