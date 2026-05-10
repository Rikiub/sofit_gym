<?php

use App\Core\AssetExtension;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\Normalizer\Normalizer;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\NormalizerBuilder;
use League\Plates\Template\Theme;
use League\Plates\Engine;

return [
    // Conexion PDO a la base de datos
    PDO::class => function () {
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
                $dsn,
                $username,
                $password,
                $options
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Conexion a base de datos fallida: ' . $e->getMessage());
        }
    },
    // Directorio donde cargar vistas/plantillas
    Engine::class => function () {
        return Engine::fromTheme(Theme::hierarchy([
            Theme::new('app/Vistas/base', 'Base'),
            Theme::new('app/Vistas/paginas', 'Pagina'),
        ]))
            ->loadExtension(new AssetExtension());
    },
    // Validador
    TreeMapper::class => function () {
        return new MapperBuilder()
            ->allowScalarValueCasting()
            ->allowSuperfluousKeys()
            ->allowUndefinedValues()
            ->supportDateFormats(
                DateTimeInterface::ATOM,
                'Y-m-d H:i:s',
                'Y-m-d',
            )
            ->mapper();
    },
    // Normalizador
    Normalizer::class => function () {
        return new NormalizerBuilder()
            ->registerTransformer(fn(DateTimeInterface $date) => $date->format(DateTimeInterface::ATOM))
            ->normalizer(Format::json());
    },
];
