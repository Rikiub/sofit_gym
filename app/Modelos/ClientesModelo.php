<?php

namespace App\Modelos;

use App\Core\BaseModelo;
use App\Core\Validador;
use DateTimeImmutable;
use InvalidArgumentException;

readonly class Membresia
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

readonly class Cliente
{
    public function __construct(
        public ?string $cedula = null,
        public ?string $nombre = null,
        public ?string $apellido = null,
        public ?string $correo = null,
        public ?string $telefono = null,
        public ?string $direccion = null,
        public ?bool $activo = true,
        public ?DateTimeImmutable $fecha_nacimiento = null,
        public ?DateTimeImmutable $fecha_registro = new DateTimeImmutable(),
        public ?Membresia $membresia = null,
    ) {}

    public function validarInsert()
    {
        if (!$this->cedula) {
            throw new InvalidArgumentException('Debe tener una cedula');
        }
        if (!$this->nombre || !$this->apellido) {
            throw new InvalidArgumentException('Debe tener nombre y apellido');
        }
        if (!$this->membresia) {
            throw new InvalidArgumentException('Debe tener una membresia');
        }
    }
}

class ClientesModelo extends BaseModelo
{
    private function sqlSelect(): string
    {
        return <<<SQL
                SELECT
                    cliente.cedula_cliente AS `cedula`,
                    persona.nombre,
                    persona.apellido,
                    persona.correo,
                    persona.telefono,
                    persona.direccion,
                    persona.fecha_nacimiento,
                    persona.fecha_registro,
                    persona.activo,
                    JSON_OBJECT(
                        "id_membresia", m.id_membresia,
                        "id_tipo", m.id_tipo,
                        "estado", me.nombre,
                        "id_estado", m.id_estado,
                        "tipo", mt.nombre,
                        "fecha_inicio", m.fecha_inicio,
                        "fecha_fin", m.fecha_fin
                    ) AS membresia
                FROM cliente
                LEFT JOIN persona ON persona.cedula_persona = cliente.cedula_cliente
                LEFT JOIN membresia m ON cliente.id_membresia = m.id_membresia
                LEFT JOIN tipo_membresia mt ON m.id_tipo = mt.id_tipo
                LEFT JOIN estado_membresia me ON m.id_estado = me.id_estado
            SQL;
    }

    private function mapToCliente(array $row): Cliente
    {
        $row['membresia'] = json_decode($row['membresia'], true);
        return $this->mapper->map(Cliente::class, $row);
    }

    /**
     * @return array<Cliente>
     */
    public function getAll()
    {
        $stmt = $this->pdo->prepare($this->sqlSelect());
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data = [];
        foreach ($rows as $row) {
            array_push($data, $this->mapToCliente($row));
        }

        return $data;
    }

    public function findByCedula(string $cedula): Cliente|false
    {
        // Cliente
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()}
                WHERE cliente.cedula_cliente = ?
            SQL
        );
        $stmt->execute([$cedula]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        // Build
        return $this->mapToCliente($row);
    }

    public function getEstadosMembresia(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM estado_membresia');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function getTiposMembresia(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tipo_membresia');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function insertCliente(Cliente $cliente): Cliente
    {
        $cliente->validarInsert();
        $this->pdo->beginTransaction();

        $this->pdoInsert('persona', [
            'cedula_persona' => $cliente->cedula,
            'nombre' => $cliente->nombre,
            'apellido' => $cliente->apellido,
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'direccion' => $cliente->direccion,
            'fecha_nacimiento' => Validador::dateToString($cliente->fecha_nacimiento),
            'fecha_registro' => Validador::dateToString($cliente->fecha_registro),
            'activo' => $cliente->activo,
        ]);

        $membresia = $cliente->membresia;
        $this->pdoInsert('membresia', [
            'id_tipo' => $membresia->id_tipo,
            'id_estado' => $membresia->id_estado,
            'fecha_inicio' => Validador::dateToString($membresia->fecha_inicio),
            'fecha_fin' => Validador::dateToString($membresia->fecha_fin),
        ]);
        $membresiaId = $this->pdo->lastInsertId();

        $this->pdoInsert('cliente', [
            'cedula_cliente' => $cliente->cedula,
            'id_membresia' => $membresiaId,
        ]);

        $this->pdo->commit();
        return $this->findByCedula($cliente->cedula);
    }

    public function updateCliente(Cliente $cliente): Cliente
    {
        $cliente->validarInsert();
        $this->pdo->beginTransaction();

        $this->pdoUpdate(
            'persona', [
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'correo' => $cliente->correo,
                'telefono' => $cliente->telefono,
                'direccion' => $cliente->direccion,
                'fecha_nacimiento' => Validador::dateToString($cliente->fecha_nacimiento),
                'fecha_registro' => Validador::dateToString($cliente->fecha_registro),
                'activo' => $cliente->activo,
            ],
            ['cedula_persona' => $cliente->cedula]
        );

        if ($cliente->membresia) {
            $stmt = $this->pdo->prepare('SELECT id_membresia FROM cliente WHERE cedula_cliente = ?');
            $stmt->execute([$cliente->cedula]);
            $membresiaId = $stmt->fetchColumn();

            $membresia = $cliente->membresia;
            $this->pdoUpdate(
                'membresia',
                [
                    'id_tipo' => $membresia->id_tipo,
                    'id_estado' => $membresia->id_estado,
                    'fecha_inicio' => Validador::dateToString($membresia->fecha_inicio),
                    'fecha_fin' => Validador::dateToString($membresia->fecha_fin),
                ],
                ['id_membresia' => $membresiaId],
            );
        }

        $this->pdo->commit();
        return $this->findByCedula($cliente->cedula);
    }

    public function deleteByCedula(string $cedula): int
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM cliente
            WHERE cedula_cliente = ?'
        );
        $stmt->execute([$cedula]);
        return $stmt->rowCount();
    }
}
