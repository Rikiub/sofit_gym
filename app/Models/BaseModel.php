<?php

namespace App\Models;

use PDO;

abstract class BaseModel
{
    public function __construct(
        protected PDO $pdo
    ) {}

    /**
     * Insertar fila en una tabla.
     */
    protected function pdoInsert(string $table, array $data): void
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    /**
     * Actualizar fila en una tabla.
     */
    protected function pdoUpdate(string $table, array $data, array $conditions): void
    {
        $setParts = [];
        foreach ($data as $col => $val) {
            $setParts[] = "$col = :set_$col";
        }

        $whereParts = [];
        foreach ($conditions as $col => $val) {
            $whereParts[] = "$col = :where_$col";
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setParts),
            implode(' AND ', $whereParts)
        );

        // Prefix bind values to avoid name collisions
        $params = [];
        foreach ($data as $col => $val) {
            $params["set_$col"] = $val;
        }
        foreach ($conditions as $col => $val) {
            $params["where_$col"] = $val;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Eliminar fila en una tabla.
     */
    protected function pdoDelete(string $table, string $primaryKey, int|string $id): void
    {
        $sql = sprintf(
            'DELETE FROM %s
            WHERE %s = ?',
            $table,
            $primaryKey
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
