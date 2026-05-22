<?php

namespace App\Models;

use App\Helpers\Validator;
use App\Models\Personas\PersonaDTO;
use App\Models\Personas\PersonaModel;
use App\Models\BaseModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use DateTimeImmutable;
use PDO;

readonly class TrabajadorDTO extends PersonaDTO
{
    public function __construct(
        ?string $cedula = null,
        ?string $nombre = null,
        ?string $apellido = null,
        ?string $correo = null,
        ?string $telefono = null,
        ?string $direccion = null,
        ?bool $activo = true,
        ?DateTimeImmutable $fecha_nacimiento = null,
        ?DateTimeImmutable $fecha_registro = new DateTimeImmutable(),
        public ?int $id_rol = null,
        public ?string $rol = null,
        public ?float $salario = null,
        public ?DateTimeImmutable $fecha_contratacion = new DateTimeImmutable(),
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
    }

    public function validateUpdate() {}
}

class TrabajadoresModel extends BaseModel
{
    private string $table = 'trabajador';
    private string $primaryKey = 'cedula_trabajador';

    public function __construct(
        PDO $pdo,
        private TreeMapper $mapper,
        private PersonaModel $personaModel,
    ) {
        return parent::__construct($pdo);
    }

    private function sqlSelect(): string
    {
        $pTable = $this->personaModel->table;
        $pKey = $this->personaModel->primaryKey;

        return <<<SQL
                SELECT
                    t.{$this->primaryKey} AS `cedula`,
                    t.*,
                    p.*,
                    tipo_rol.nombre AS `rol`
                FROM {$this->table} t
                LEFT JOIN {$pTable} p
                    ON p.{$pKey} = t.{$this->primaryKey}
                LEFT JOIN tipo_rol
                    ON t.id_rol = tipo_rol.id_rol
            SQL;
    }

    /**
     * @return TrabajadorDTO[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare($this->sqlSelect());
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data = [];
        foreach ($rows as $row) {
            $data[] = $this->mapper->map(TrabajadorDTO::class, $row);
        }

        return $data;
    }

    public function find(string $cedula): ?TrabajadorDTO
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                {$this->sqlSelect()} 
                WHERE {$this->primaryKey} = ?
            SQL
        );
        $stmt->execute([$cedula]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->mapper->map(TrabajadorDTO::class, $row);
    }

    public function insert(TrabajadorDTO $trabajador): void
    {
        $trabajador->validateInsert();
        $this->pdo->beginTransaction();

        $this->personaModel->insert($trabajador);
        $this->pdoInsert(
            $this->table,
            $this->dtoToArray($trabajador),
        );

        $this->pdo->commit();
    }

    public function update(TrabajadorDTO $trabajador): void
    {
        $trabajador->validateUpdate();
        $this->pdo->beginTransaction();

        $this->personaModel->update($trabajador);

        $array = $this->dtoToArray($trabajador);
        unset($array['cedula_trabajador']);

        $this->pdoUpdate(
            $this->table,
            $array,
            [$this->primaryKey => $trabajador->cedula],
        );

        $this->pdo->commit();
    }

    public function delete(string $cedula): int
    {
        return $this->pdoDelete(
            $this->table,
            $this->primaryKey,
            $cedula,
        );
    }

    private function dtoToArray(TrabajadorDTO $trabajador): array
    {
        return [
            'cedula_trabajador' => $trabajador->cedula,
            'id_rol' => $trabajador->id_rol,
            'salario' => $trabajador->salario,
            'fecha_contratacion' => Validator::dateToString($trabajador->fecha_contratacion),
        ];
    }
}
