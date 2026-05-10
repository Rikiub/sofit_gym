<?php

namespace App\Core;

use League\Plates\Extension\ExtensionInterface;
use League\Plates\Engine;

class AssetExtension implements ExtensionInterface
{
    protected $css = [];
    protected $js = [];

    public function register(Engine $engine)
    {
        // Funciones para añadir assets
        $engine->registerFunction('pushCss', [$this, 'pushCss']);
        $engine->registerFunction('pushJs', [$this, 'pushJs']);

        // Funciones para renderizar assets
        $engine->registerFunction('renderCss', [$this, 'renderCss']);
        $engine->registerFunction('renderJs', [$this, 'renderJs']);
    }

    public function pushCss(string $path)
    {
        if (!isset($this->css[$path])) {
            $this->css[$path] = true;
        }
    }

    public function pushJs(string $path, bool $isModule = true)
    {
        if (!isset($this->js[$path])) {
            $this->js[$path] = $isModule;
        }
    }

    public function renderCss(): string
    {
        $tags = [];

        foreach (array_keys($this->css) as $path) {
            $tags[] = <<<HTML
                    <link rel="stylesheet" href="{$path}">
                HTML;
        }

        return implode("\n", $tags);
    }

    public function renderJs(): string
    {
        $tags = [];

        foreach ($this->js as $path => $isModule) {
            $type = $isModule ? 'type="module"' : 'defer';
            $tags[] = <<<HTML
                <script {$type} src="{$path}"></script>
                HTML;
        }

        return implode("\n", $tags);
    }
}
