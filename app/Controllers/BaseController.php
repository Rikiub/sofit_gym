<?php

namespace App\Controllers;

use DI\Attribute\Inject;
use League\Plates\Engine;

abstract class BaseController
{
    #[Inject]
    protected Engine $templates;
}
