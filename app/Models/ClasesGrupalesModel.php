<?php

namespace App\Models;

use App\Helpers\Validator;
use App\Models\BaseModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use DateTimeImmutable;
use PDO;

enum EstadoClase: string
{
    case PROGRAMADO = "Programado";
    case EN_CURSO = "En curso";
    case FINALIZADO = "Finalizado";
    case CANCELADO = "Cancelado";
}

readonly class ClaseGrupalDTO
{
    public function __construct(
        public ?int $id_clase = null,
        public ?string $cedula_trabajador = null,
        public ?string $nombre = null,
        public ?string $descripcion = null,
        public ?int $cupos_ocupados = 0,
        public ?int $capacidad_maxima = null,
        public ?EstadoClase $estado = null,
        public ?DateTimeImmutable $fecha_inicio = null,
        public ?DateTimeImmutable $fecha_fin = null,
    ) {}

    public function validateInsert() {}

    public function validateUpdate() {}
}

class ClasesGrupalesModel extends BaseModel
{
    private string $table = 'clase';
    private string $primaryKey = 'id_clase';

    public function __construct(
        PDO $pdo,
        private TreeMapper $mapper,
    ) {
        parent::__construct($pdo);
    }

    private function sqlSelect(): string
    {
        return <<<SQL
            SELECT * FROM {$this->table}
        SQL;
    }

    /**
     * @return ClaseGrupalDTO[]
     */
    public function getAll(): array
    {
        $rows = $this->pdoQuery($this->sqlSelect())->fetchAll();
        return array_map(
            fn($row) => $this->mapper->map(ClaseGrupalDTO::class, $row),
            $rows
        );
    }

    public function find(int $id): ?ClaseGrupalDTO
    {
        $row = $this->pdoQuery(
            "{$this->sqlSelect()} WHERE {$this->primaryKey} = ?",
            [$id]
        )->fetch();

        if (!$row) return null;
        return $this->mapper->map(ClaseGrupalDTO::class, $row);
    }

    public function insert(ClaseGrupalDTO $clase): ClaseGrupalDTO
    {
        $clase->validateInsert();
        $this->pdo->beginTransaction();

        $this->pdoInsert($this->table, $this->dtoToArray($clase));

        $id = (int) $this->pdo->lastInsertId();
        $this->pdo->commit();

        return $this->find($id);
    }

    public function update(ClaseGrupalDTO $clase): ClaseGrupalDTO
    {
        $clase->validateUpdate();
        $this->pdo->beginTransaction();

        $array = $this->dtoToArray($clase);
        unset($array[$this->primaryKey]);

        $this->pdoUpdate(
            $this->table,
            $array,
            [$this->primaryKey => $clase->id_clase]
        );

        $this->pdo->commit();
        return $this->find($clase->id_clase);
    }

    public function delete(int $id): void
    {
        $this->pdoDelete($this->table, [$this->primaryKey => $id]);
    }

    private function dtoToArray(ClaseGrupalDTO $dto): array
    {
        return [
            'id_clase'          => $dto->id_clase,
            'cedula_trabajador' => $dto->cedula_trabajador,
            'nombre'            => $dto->nombre,
            'descripcion'       => $dto->descripcion,
            'cupos_ocupados'    => $dto->cupos_ocupados ?? 0,
            'capacidad_maxima'  => $dto->capacidad_maxima,
            'estado'            => $dto->estado->value,
            'fecha_inicio'      => Validator::dateToString($dto->fecha_inicio),
            'fecha_fin'         => Validator::dateToString($dto->fecha_fin),
        ];
    }
}
