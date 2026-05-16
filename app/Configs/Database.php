<?php

namespace App\Configs;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    public static function getConnection(): PDO
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $database = getenv('DB_DATABASE') ?: 'sofit_gym';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $charset = 'utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $dsn = "mysql:host={$host};dbname={$database};charset={$charset};";

        try {
            return new PDO(
                dsn: $dsn,
                username: $username,
                password: $password,
                options: $options
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Conexion a base de datos fallida: ' . $e->getMessage());
        }
    }
}
