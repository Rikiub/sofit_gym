<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Equipos\EquiposModel;

class EquiposController extends BaseController
{
    public function __construct(private EquiposModel $equiposModel) {}
}
