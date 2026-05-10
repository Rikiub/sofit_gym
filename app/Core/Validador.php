<?php

namespace App\Core;

use DateTimeInterface;

class Validador
{
    public static function dateToString(?DateTimeInterface $date): ?string
    {
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }
}
