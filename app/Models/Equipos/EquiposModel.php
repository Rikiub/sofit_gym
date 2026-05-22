<?php

namespace App\Models\Equipos;

use App\Models\BaseModel;
use InvalidArgumentException;

enum EstadoEquipo: string
{
    case Operativo = 'Operativo';
    case Mantenimiento = 'Mantenimiento';
    case FueraDeServicio = 'Fuera de Servicio';
}

readonly class EquipoDTO
{
    public function __construct(
        public ?string $codigo = null,
        public ?string $nombre = null,
        public ?string $tipo = null,
        public ?EstadoEquipo $estado = null,
        public ?string $ubicacion = null,
        public ?bool $activo = true,
    ) {}

    public function validateInsert(): void
    {
        if (empty($this->codigo)) {
            throw new InvalidArgumentException('El código del equipo es obligatorio');
        }
        if (empty($this->nombre)) {
            throw new InvalidArgumentException('El nombre del equipo es obligatorio');
        }
        if ($this->estado === null) {
            throw new InvalidArgumentException('El estado del equipo es obligatorio');
        }
    }

    public function validateUpdate(): void
    {
        if (empty($this->codigo)) {
            throw new InvalidArgumentException('El código del equipo es necesario para actualizar');
        }
    }
}

class EquiposModel extends BaseModel
{
    private string $table = 'equipo';
    private string $primaryKey = 'codigo_equipo';

    private function sqlSelect(): string
    {
        return <<<SQL
                SELECT
                    codigo_equipo AS `codigo`,
                    nombre,
                    tipo,
                    estado,
                    ubicacion,
                    activo
                FROM {$this->table}
            SQL;
    }

    /**
     * @return array<EquipoDTO>
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare($this->sqlSelect());
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data = [];
        foreach ($rows as $row) {
            $data[] = $this->mapper->map(EquipoDTO::class, $row);
        }

        return $data;
    }

    public function find(string $codigo): ?EquipoDTO
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()} 
                WHERE {$this->primaryKey} = ?
            SQL
        );
        $stmt->execute([$codigo]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapper->map(EquipoDTO::class, $row);
    }

    public function insert(EquipoDTO $equipo): EquipoDTO
    {
        $equipo->validateInsert();

        $this->pdoInsert($this->table, [
            'codigo_equipo' => $equipo->codigo,
            'nombre' => $equipo->nombre,
            'tipo' => $equipo->tipo,
            'estado' => $equipo->estado->value,
            'ubicacion' => $equipo->ubicacion,
            'activo' => $equipo->activo,
        ]);

        return $this->find($equipo->codigo);
    }

    public function update(EquipoDTO $equipo): EquipoDTO
    {
        $equipo->validateUpdate();

        $this->pdoUpdate(
            $this->table,
            [
                'nombre' => $equipo->nombre,
                'tipo' => $equipo->tipo,
                'estado' => $equipo->estado->value,
                'ubicacion' => $equipo->ubicacion,
                'activo' => $equipo->activo,
            ],
            [$this->primaryKey => $equipo->codigo],
        );

        return $this->find($equipo->codigo);
    }

    public function delete(string $codigo): int
    {
        return $this->pdoDelete($this->table, $this->primaryKey, $codigo);
    }
}
