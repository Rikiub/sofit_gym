<?php

namespace App\Models;

use App\Helpers\Validator;
use App\Models\BaseModel;
use DateTimeImmutable;
use InvalidArgumentException;

readonly class SeguimientoFisicoDTO
{
    public function __construct(
        public ?int $id_seguimiento = null,
        public ?string $cedula_cliente = null,
        public ?DateTimeImmutable $fecha = null,
        public ?float $altura_cm = null,
        public ?float $peso_kg = null,
        public ?float $cintura_cm = null,
        public ?float $cadera_cm = null,
        public ?float $pecho_cm = null,
        public ?float $muslo_cm = null,
        public ?float $hombros_cm = null,
        public ?float $pantorrilla_cm = null,
    ) {}

    public function validarInsert(): void
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
}

class SeguimientoFisicoModel extends BaseModel
{
    private function sqlSelect(): string
    {
        return 'SELECT * FROM seguimiento_fisico';
    }

    private function mapSeguimiento(array $row): SeguimientoFisicoDTO
    {
        return $this->mapper->map(SeguimientoFisicoDTO::class, $row);
    }

    /**
     * Obtiene todos los seguimientos de un cliente.
     * @return array<SeguimientoFisicoDTO>
     */
    public function getByCliente(string $cedula): array
    {
        $stmt = $this->pdo->prepare(
            $this->sqlSelect() . ' WHERE cedula_cliente = ? ORDER BY fecha DESC'
        );
        $stmt->execute([$cedula]);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapSeguimiento'], $rows);
    }

    /**
     * Busca un seguimiento por su ID.
     */
    public function findById(int $id): SeguimientoFisicoDTO|false
    {
        $stmt = $this->pdo->prepare(
            $this->sqlSelect() . ' WHERE id_seguimiento = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        return $this->mapSeguimiento($row);
    }

    /**
     * Inserta un nuevo seguimiento.
     */
    public function insert(SeguimientoFisicoDTO $seguimiento): SeguimientoFisicoDTO
    {
        $seguimiento->validarInsert();

        $this->pdoInsert('seguimiento_fisico', [
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
        ]);

        $id = $this->pdo->lastInsertId();
        return $this->findById($id);
    }

    /**
     * Actualiza un seguimiento existente.
     */
    public function update(SeguimientoFisicoDTO $seguimiento): SeguimientoFisicoDTO
    {
        if (!$seguimiento->id_seguimiento) {
            throw new InvalidArgumentException('Se requiere id_seguimiento para actualizar');
        }

        $seguimiento->validarInsert();  // al menos una medida

        $this->pdoUpdate('seguimiento_fisico', [
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
        ], ['id_seguimiento' => $seguimiento->id_seguimiento]);

        return $this->findById($seguimiento->id_seguimiento);
    }

    /**
     * Elimina un seguimiento por ID.
     */
    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM seguimiento_fisico WHERE id_seguimiento = ?'
        );
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
