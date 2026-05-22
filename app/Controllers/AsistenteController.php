<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AsistenteController extends BaseController
{

    public function __construct() {}

    public function index()
    {
        return $this->templates->render("asistente");
    }
}
