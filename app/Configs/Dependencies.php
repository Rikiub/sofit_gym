<?php

namespace App\Configs;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\Normalizer\Normalizer;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\NormalizerBuilder;
use League\Plates\Template\Theme;
use League\Plates\Engine;
use DateTimeInterface;

class Dependencies
{
    public static function getPlatesTemplates(): Engine
    {
        return Engine::fromTheme(Theme::hierarchy([
            Theme::new('app/views/base', 'Base'),
            Theme::new('app/views/components', 'Components'),
            Theme::new('app/views/pages', 'Page'),
        ]));
    }

    public static function getValinorNormalizer(): Normalizer
    {
        return new NormalizerBuilder()
            ->registerTransformer(
                // Convertir fechas a ISO
                fn(DateTimeInterface $date) => $date->format(DateTimeInterface::ATOM)
            )
            ->normalizer(Format::json());
    }

    public static function getValinorMapper(): TreeMapper
    {
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
    }
}
