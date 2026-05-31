<?php

namespace App\Models\Clientes;

use App\Models\Personas\PersonaDTO;
use DateTimeImmutable;
use InvalidArgumentException;

readonly class MembresiaDTO
{
    public function __construct(
        public ?int $id_membresia = null,
        public ?int $id_tipo = null,
        public ?int $id_estado = null,
        public ?string $tipo = null,
        public ?string $estado = null,
        public ?DateTimeImmutable $fecha_inicio = null,
        public ?DateTimeImmutable $fecha_fin = null,
    ) {}
}

readonly class ClienteDTO extends PersonaDTO
{
    public function __construct(
        // Atributos heredados
        ?string $cedula = null,
        ?string $nombre = null,
        ?string $apellido = null,
        ?string $correo = null,
        ?string $telefono = null,
        ?string $direccion = null,
        ?bool $activo = true,
        ?DateTimeImmutable $fecha_nacimiento = null,
        ?DateTimeImmutable $fecha_registro = new DateTimeImmutable(),
        // Nuevos atributos
        public ?MembresiaDTO $membresia = null,
    ) {
        parent::__construct(
            cedula: $cedula,
            nombre: $nombre,
            apellido: $apellido,
            correo: $correo,
            telefono: $telefono,
            direccion: $direccion,
            activo: $activo,
            fecha_nacimiento: $fecha_nacimiento,
            fecha_registro: $fecha_registro,
        );
    }

    public function validateInsert()
    {
        parent::validateInsert();

        if (!$this->membresia) {
            throw new InvalidArgumentException('Debe tener una membresia');
        }
    }
}
