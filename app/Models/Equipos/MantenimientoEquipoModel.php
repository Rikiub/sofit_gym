<?php

namespace App\Models\Equipos;

use App\Helpers\Validator;
use App\Models\BaseModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use DateTimeImmutable;
use InvalidArgumentException;
use PDO;

enum TipoMantenimiento: string
{
    case Preventivo = 'Preventivo';
    case Correctivo = 'Correctivo';
}

readonly class MantenimientoEquipoDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $codigo_equipo = null,
        public ?DateTimeImmutable $fecha = null,
        public ?TipoMantenimiento $tipo = null,
        public ?string $descripcion = null,
        public ?float $costo = null,
        public ?string $tecnico = null,
        public ?EquipoDTO $equipo = null,
    ) {}

    public function validateInsert(): void
    {
        $this->validateShared();

        if (!$this->fecha) {
            throw new InvalidArgumentException('La fecha del mantenimiento es obligatoria');
        }
        if ($this->tipo === null) {
            throw new InvalidArgumentException('El tipo de mantenimiento es obligatorio');
        }
        if ($this->costo !== null && $this->costo < 0) {
            throw new InvalidArgumentException('El costo no puede ser negativo');
        }
    }

    public function validateUpdate()
    {
        $this->validateShared();

        if (!$this->id) {
            throw new InvalidArgumentException('El ID de mantenimiento es necesario para actualizar');
        }
    }

    private function validateShared()
    {
        if (!$this->codigo_equipo) {
            throw new InvalidArgumentException('El código de equipo es obligatorio');
        }
    }
}

class MantenimientoEquipoModel extends BaseModel
{
    private string $table = 'mantenimiento_equipo';
    private string $primaryKey = 'id_mantenimiento';

    public function __construct(
        protected PDO $pdo,
        protected TreeMapper $mapper,
        protected EquiposModel $equiposModel,
    ) {}

    private function sqlSelect(): string
    {
        return <<<SQL
                SELECT
                    id_mantenimiento AS `id`,
                    codigo_equipo,
                    fecha,
                    tipo,
                    descripcion,
                    costo,
                    tecnico
                FROM {$this->table}
            SQL;
    }

    private function mapToMantenimiento(array $row): MantenimientoEquipoDTO
    {
        $row['equipo'] = $this->equiposModel->find($row['codigo_equipo']);
        $mantenimiento = $this->mapper->map(MantenimientoEquipoDTO::class, $row);
        return $mantenimiento;
    }

    /**
     * @return array<MantenimientoEquipoDTO>
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare($this->sqlSelect());
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data = [];
        foreach ($rows as $row) {
            $data[] = $this->mapToMantenimiento($row);
        }

        return $data;
    }

    public function find(int $id): ?MantenimientoEquipoDTO
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()} 
                WHERE {$this->primaryKey} = ?
            SQL
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapToMantenimiento($row);
    }

    public function insert(MantenimientoEquipoDTO $mantenimiento): MantenimientoEquipoDTO
    {
        $mantenimiento->validateInsert();

        // Verificar que el equipo exista
        $equipo = $this->equiposModel->find($mantenimiento->codigo_equipo);

        if (!$equipo || !$equipo->activo) {
            throw new InvalidArgumentException("El equipo con código {$mantenimiento->codigo_equipo} no existe o está inactivo");
        }

        $this->pdoInsert($this->table, [
            'codigo_equipo' => $mantenimiento->codigo_equipo,
            'fecha' => Validator::dateToString($mantenimiento->fecha),
            'tipo' => $mantenimiento->tipo->value,
            'descripcion' => $mantenimiento->descripcion,
            'costo' => $mantenimiento->costo,
            'tecnico' => $mantenimiento->tecnico,
        ]);

        $id = (int) $this->pdo->lastInsertId();
        return $this->find($id);
    }

    public function update(MantenimientoEquipoDTO $mantenimiento): MantenimientoEquipoDTO
    {
        $mantenimiento->validateUpdate();

        // Verificar que el equipo exista
        $equipo = $this->equiposModel->find($mantenimiento->codigo_equipo);

        if (!$equipo || !$equipo->activo) {
            throw new InvalidArgumentException("El equipo con código {$mantenimiento->codigo_equipo} no existe o está inactivo");
        }

        $this->pdoUpdate(
            $this->table,
            [
                'codigo_equipo' => $mantenimiento->codigo_equipo,
                'fecha' => Validator::dateToString($mantenimiento->fecha),
                'tipo' => $mantenimiento->tipo->value,
                'descripcion' => $mantenimiento->descripcion,
                'costo' => $mantenimiento->costo,
                'tecnico' => $mantenimiento->tecnico,
            ],
            [$this->primaryKey => $mantenimiento->id],
        );

        return $this->find($mantenimiento->id);
    }

    public function delete(int $id): int
    {
        return $this->pdoDelete($this->table, $this->primaryKey, $id);
    }
}
