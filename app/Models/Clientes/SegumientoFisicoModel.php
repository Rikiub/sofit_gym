<?php

namespace App\Models\Clientes;

use App\Helpers\Validator;
use App\Models\BaseModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use DateTimeImmutable;
use InvalidArgumentException;
use PDO;

readonly class SeguimientoFisicoDTO
{
    public function __construct(
        public ?int $id_seguimiento = null,
        public ?string $cedula_cliente = null,
        public ?DateTimeImmutable $fecha = new DateTimeImmutable(),
        public ?float $altura_cm = null,
        public ?float $peso_kg = null,
        public ?float $cintura_cm = null,
        public ?float $cadera_cm = null,
        public ?float $pecho_cm = null,
        public ?float $muslo_cm = null,
        public ?float $hombros_cm = null,
        public ?float $pantorrilla_cm = null,
    ) {}

    public function validateInsert(): void
    {
        if (!$this->cedula_cliente) {
            throw new InvalidArgumentException('Debe tener una cédula de cliente');
        }
        if (!$this->fecha) {
            throw new InvalidArgumentException('Debe tener una fecha de seguimiento');
        }

        // Al menos una medida numérica debe existir
        $medidas = [
            $this->altura_cm,
            $this->peso_kg,
            $this->cintura_cm,
            $this->cadera_cm,
            $this->pecho_cm,
            $this->muslo_cm,
            $this->hombros_cm,
            $this->pantorrilla_cm,
        ];

        $todasVacias = true;
        foreach ($medidas as $medida) {
            if ($medida !== null) {
                $todasVacias = false;
                break;
            }
        }

        if ($todasVacias) {
            throw new InvalidArgumentException('Debe proporcionar al menos una medida');
        }
    }

    public function validateUpdate()
    {
        if (!$this->id_seguimiento) {
            throw new InvalidArgumentException('Se requiere id_seguimiento para actualizar');
        }
    }
}

class SegumientoFisicoModel extends BaseModel
{
    private string $table = 'seguimiento_fisico';
    private string $primaryKey = 'id_seguimiento';

    public function __construct(
        PDO $pdo,
        private TreeMapper $mapper,
    ) {
        return parent::__construct($pdo);
    }

    private function sqlSelect(): string
    {
        return <<<SQL
                SELECT * FROM {$this->table}
            SQL;
    }

    private function mapRow(array $row): SeguimientoFisicoDTO
    {
        return $this->mapper->map(SeguimientoFisicoDTO::class, $row);
    }

    /**
     * Obtiene todos los seguimientos de un cliente.
     * @return SeguimientoFisicoDTO[]
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
    public function find(int $id): ?SeguimientoFisicoDTO
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

        return $this->mapRow($row);
    }

    /**
     * Inserta un nuevo seguimiento.
     */
    public function insert(SeguimientoFisicoDTO $seguimiento): SeguimientoFisicoDTO
    {
        $seguimiento->validateInsert();
        $this->pdoInsert($this->table, $this->dtoToArray($seguimiento));

        $id = (int) $this->pdo->lastInsertId();
        return $this->find($id);
    }

    /**
     * Actualiza un seguimiento existente.
     */
    public function update(SeguimientoFisicoDTO $seguimiento): SeguimientoFisicoDTO
    {
        $seguimiento->validateUpdate();

        $this->pdoUpdate(
            $this->table,
            $this->dtoToArray($seguimiento),
            [$this->primaryKey => $seguimiento->id_seguimiento]
        );

        $id = (int) $this->pdo->lastInsertId();
        return $this->find($id);
    }

    /**
     * Elimina un seguimiento por ID.
     */
    public function delete(int $id): void
    {
        $this->pdoDelete($this->table, $this->primaryKey, $id);
    }

    private function dtoToArray(SeguimientoFisicoDTO $seguimiento): array
    {
        return [
            'cedula_cliente' => $seguimiento->cedula_cliente,
            'fecha' => Validator::dateToString($seguimiento->fecha),
            'altura_cm' => $seguimiento->altura_cm,
            'peso_kg' => $seguimiento->peso_kg,
            'cintura_cm' => $seguimiento->cintura_cm,
            'cadera_cm' => $seguimiento->cadera_cm,
            'pecho_cm' => $seguimiento->pecho_cm,
            'muslo_cm' => $seguimiento->muslo_cm,
            'hombros_cm' => $seguimiento->hombros_cm,
            'pantorrilla_cm' => $seguimiento->pantorrilla_cm,
        ];
    }
}
