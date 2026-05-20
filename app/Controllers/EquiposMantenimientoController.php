<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Equipos\MantenimientoEquipoModel;

class EquiposMantenimientoController extends BaseController
{
    public function __construct(private MantenimientoEquipoModel $manModel) {}
}
