<?php

namespace App\Models\Clientes;

use App\Helpers\Validator;
use App\Models\BaseModel;
use DateTimeImmutable;
use InvalidArgumentException;

readonly class SeguimientoNutricionalDTO
{
    public function __construct(
        public ?int $id_seguimiento = null,
        public ?string $cedula_cliente = null,
        public ?DateTimeImmutable $fecha = new DateTimeImmutable(),
        public ?float $proteinas_g = null,
        public ?float $carbohidratos_g = null,
        public ?float $grasas_g = null,
        public ?float $calorias_diarias = null,
    ) {}

    public function validateInsert(): void
    {
        if (!$this->cedula_cliente) {
            throw new InvalidArgumentException('Debe tener una cédula de cliente');
        }
        if (!$this->fecha) {
            throw new InvalidArgumentException('Debe tener una fecha de seguimiento');
        }
    }
}

class SegumientoNutricionalModel extends BaseModel
{
    private string $table = 'seguimiento_nutricional';
    private string $primaryKey = 'id_seguimiento';

    private function sqlSelect(): string
    {
        return <<<SQL
                SELECT * FROM {$this->table}
            SQL;
    }

    private function mapRow(array $row): SeguimientoNutricionalDTO
    {
        return $this->mapper->map(SeguimientoNutricionalDTO::class, $row);
    }

    /**
     * Obtiene todos los seguimientos de un cliente.
     * @return array<SeguimientoNutricionalDTO>
     */
    public function getAllByCliente(string $cedula): array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()} 
                WHERE cedula_cliente = ?
                ORDER BY fecha DESC
            SQL
        );
        $stmt->execute([$cedula]);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapRow'], $rows);
    }

    /**
     * Busca un seguimiento por su ID.
     */
    public function find(int $id): SeguimientoNutricionalDTO|false
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()} 
                WHERE {$this->primaryKey} = ?
            SQL,
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        return $this->mapRow($row);
    }

    /**
     * Inserta un nuevo seguimiento.
     */
    public function insert(SeguimientoNutricionalDTO $seguimiento): SeguimientoNutricionalDTO
    {
        $seguimiento->validateInsert();

        $this->pdoInsert($this->table, [
            'cedula_cliente' => $seguimiento->cedula_cliente,
            'fecha' => Validator::dateToString($seguimiento->fecha),
            'proteinas_g' => $seguimiento->proteinas_g,
            'carbohidratos_g' => $seguimiento->carbohidratos_g,
            'grasas_g' => $seguimiento->grasas_g,
            'calorias_diarias' => $seguimiento->calorias_diarias,
        ]);

        $id = $this->pdo->lastInsertId();
        return $this->find($id);
    }

    /**
     * Actualiza un seguimiento existente.
     */
    public function update(SeguimientoNutricionalDTO $seguimiento): SeguimientoNutricionalDTO
    {
        if (!$seguimiento->id_seguimiento) {
            throw new InvalidArgumentException('Se requiere id_seguimiento para actualizar');
        }

        $seguimiento->validateInsert();

        $this->pdoUpdate($this->table, [
            'cedula_cliente' => $seguimiento->cedula_cliente,
            'fecha' => Validator::dateToString($seguimiento->fecha),
            'proteinas_g' => $seguimiento->proteinas_g,
            'carbohidratos_g' => $seguimiento->carbohidratos_g,
            'grasas_g' => $seguimiento->grasas_g,
            'calorias_diarias' => $seguimiento->calorias_diarias,
        ], [$this->primaryKey => $seguimiento->id_seguimiento]);

        return $this->find($seguimiento->id_seguimiento);
    }

    /**
     * Elimina un seguimiento por ID.
     */
    public function delete(int $id): int
    {
        return $this->pdoDelete($this->table, $this->primaryKey, $id);
    }
}
