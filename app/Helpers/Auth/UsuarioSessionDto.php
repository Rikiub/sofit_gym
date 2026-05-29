<?php

namespace App\Helpers\Auth;

/** Datos accesibles del usuario que ha iniciado sesión */
readonly class UsuarioSessionDto
{
    public function __construct(
        public int $id,
        public int $id_rol,
        public string $rol,
        public string $nombre,
    ) {}
}
