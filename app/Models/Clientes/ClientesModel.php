<?php

namespace App\Models\Clientes;

use App\Helpers\Validator;
use App\Models\Personas\PersonaDTO;
use App\Models\Personas\PersonasModel;
use App\Models\BaseModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use DateTimeImmutable;
use InvalidArgumentException;
use PDO;

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

class ClientesModel extends BaseModel
{
    public function __construct(
        PDO $pdo,
        private TreeMapper $mapper,
        private PersonasModel $personasModel
    ) {
        return parent::__construct($pdo);
    }

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

    private function mapToCliente(array $row): ClienteDTO
    {
        $row['membresia'] = json_decode($row['membresia'], true);
        return $this->mapper->map(ClienteDTO::class, $row);
    }

    /**
     * @return ClienteDTO[]
     */
    public function getAll(): array
    {
        $rows = $this->pdoQuery($this->sqlSelect())->fetchAll();
        return array_map($this->mapToCliente(...), $rows);
    }

    public function find(string $cedula): ?ClienteDTO
    {
        $row = $this->pdoQuery(
            "{$this->sqlSelect()} WHERE cedula_cliente = ?",
            [$cedula]
        )->fetch();

        if (!$row)
            return null;
        return $this->mapToCliente($row);
    }

    public function getEstadosMembresia(): array
    {
        return $this->pdoQuery('SELECT * FROM estado_membresia')->fetchAll();
    }

    public function getTiposMembresia(): array
    {
        return $this->pdoQuery('SELECT * FROM tipo_membresia')->fetchAll();
    }

    public function insert(ClienteDTO $cliente): ClienteDTO
    {
        $cliente->validateInsert();
        $this->pdo->beginTransaction();

        $this->personasModel->insert($cliente);
        $this->pdoInsert('membresia', $this->membresiaToArray($cliente->membresia));
        $membresiaId = $this->pdo->lastInsertId();

        $this->pdoInsert('cliente', [
            'cedula_cliente' => $cliente->cedula,
            'id_membresia' => $membresiaId,
        ]);

        $this->pdo->commit();
        return $this->find($cliente->cedula);
    }

    public function update(ClienteDTO $cliente): ClienteDTO
    {
        $cliente->validateUpdate();
        $this->pdo->beginTransaction();

        $this->personasModel->update($cliente);

        if ($cliente->membresia) {
            // Obtener ID de la membresia desde el cliente
            $membresiaId = $this->pdoQuery(
                'SELECT id_membresia FROM cliente WHERE cedula_cliente = ?',
                [$cliente->cedula]
            )->fetchColumn();

            $this->pdoUpdate(
                'membresia',
                $this->membresiaToArray($cliente->membresia),
                ['id_membresia' => $membresiaId],
            );
        }

        $this->pdo->commit();
        return $this->find($cliente->cedula);
    }

    public function delete(string $cedula): void
    {
        $this->pdoDelete('cliente', ['cedula_cliente' => $cedula]);
    }

    private function membresiaToArray(MembresiaDTO $membresia): array
    {
        return [
            'id_tipo' => $membresia->id_tipo,
            'id_estado' => $membresia->id_estado,
            'fecha_inicio' => Validator::dateToString($membresia->fecha_inicio),
            'fecha_fin' => Validator::dateToString($membresia->fecha_fin),
        ];
    }
}
