<?php

namespace App\Controllers;

use App\Helpers\AssetExtension;
use App\Helpers\Response;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\NormalizerBuilder;
use League\Plates\Template\Theme;
use League\Plates\Engine;
use DateTimeInterface;

abstract class BaseControlador
{
    protected Response $response;
    protected Engine $templates;
    protected TreeMapper $mapper;

    public function __construct()
    {
        $this->templates = Engine::fromTheme(Theme::hierarchy([
            Theme::new('app/Vistas/base', 'Base'),
            Theme::new('app/Vistas/componentes', 'Componentes'),
            Theme::new('app/Vistas/paginas', 'Pagina'),
        ]))
            ->loadExtension(new AssetExtension());
        $this->mapper = new MapperBuilder()
            ->allowScalarValueCasting()
            ->allowSuperfluousKeys()
            ->allowUndefinedValues()
            ->supportDateFormats(
                DateTimeInterface::ATOM,
                'Y-m-d H:i:s',
                'Y-m-d',
            )
            ->mapper();
        $this->response = new Response(normalizer: new NormalizerBuilder()
            ->registerTransformer(fn(DateTimeInterface $date) => $date->format(DateTimeInterface::ATOM))
            ->normalizer(Format::json()));
    }

    protected function render(string $vista, array $datos = []): string
    {
        return $this->templates->render($vista, $datos);
    }
}
