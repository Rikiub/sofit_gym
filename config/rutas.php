<?php

use App\Controladores\ClientesControlador;
use App\Controladores\ErrorControlador;
use App\Controladores\InicioControlador;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/', [InicioControlador::class, 'index']);
    $r->addRoute('GET', '/error', [ErrorControlador::class, 'index']);

    $r->addRoute('GET', '/clientes', [ClientesControlador::class, 'index']);
    $r->addRoute('GET', '/clientes/{cedula}', [ClientesControlador::class, 'showCliente']);

    $r->addGroup('/api', function (RouteCollector $r) {
        $r->addGroup('/clientes', function (RouteCollector $r) {
            $r->addRoute('GET', '', [ClientesControlador::class, 'getClientes']);
            $r->addRoute('GET', '/{cedula}', [ClientesControlador::class, 'findCliente']);
            $r->addRoute('POST', '', [ClientesControlador::class, 'insertCliente']);
            $r->addRoute('PUT', '/{cedula}', [ClientesControlador::class, 'updateCliente']);
            $r->addRoute('DELETE', '/{cedula}', [ClientesControlador::class, 'deleteCliente']);
        });
    });
};
