<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteDTO;
use App\Models\ClientesModel;
use App\Models\SeguimientoFisicoDTO;
use App\Models\SeguimientoFisicoModel;

class ClientesController extends BaseController
{
    public function __construct(
        private ClientesModel $clientesModelo,
        private SeguimientoFisicoModel $segModelo
    ) {}

    // CLIENTES

    public function index(): string
    {
        return $this->render('clientes/index', [
            'formMeta' => $this->formMeta(),
        ]);
    }

    // CLIENTES: JSON API

    public function showCliente(): string
    {
        $cedula = $_GET['cedula'] ?? null;

        $cliente = $this->clientesModelo->findByCedula($cedula);
        if (!$cliente) {
            $this->redirectToError();
        }

        return $this->render('clientes/item', [
            'cliente' => $cliente,
            'formMeta' => $this->formMeta(),
        ]);
    }

    private function formMeta(): array
    {
        $tipos = $this->clientesModelo->getTiposMembresia();
        $estados = $this->clientesModelo->getEstadosMembresia();
        return [
            'tipos' => $tipos,
            'estados' => $estados,
        ];
    }

    public function getClientes(): string
    {
        $clientes = $this->clientesModelo->getAll();
        return $this->response->json($clientes);
    }

    public function findCliente(): ?string
    {
        $cedula = $_GET['cedula'] ?? null;
        $cliente = $this->clientesModelo->findByCedula($cedula);

        if (!$cliente) {
            return $this->response->empty(404);
        }

        return $this->response->json($cliente);
    }

    public function insertCliente(): string
    {
        $body = $this->response->getParsedBody();

        // Valida el POST
        $cliente = $this->mapper->map(ClienteDTO::class, $body);

        // Verificar que el cliente no exista
        if ($this->clientesModelo->findByCedula($cliente->cedula)) {
            return $this->response->json(['message' => 'El cliente ya existe'], 400);
        }

        // Crea el cliente
        $cliente = $this->clientesModelo->insertCliente($cliente);

        // Enviar JSON
        return $this->response->json($cliente, 201);
    }

    public function updateCliente(array $params): string
    {
        $cedula = $params['cedula'];

        $body = $this->response->getParsedBody();
        $body['cedula'] = $cedula;

        $cliente = $this->mapper->map(ClienteDTO::class, $body);

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 400);
        }

        $cliente = $this->clientesModelo->updateCliente($cliente);
        return $this->response->json($cliente, 201);
    }

    public function deleteCliente(array $params): string|null
    {
        $cedula = $params['cedula'];

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 404);
        }

        $this->clientesModelo->deleteByCedula($cedula);

        return $this->response->empty(204);
    }

    // SEGUIMIENTO FISICO: JSON API

    public function getSeguimientoByCliente(array $params): ?string
    {
        $cedula = $params['cedula'];

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->empty(404);
        }

        $registros = $this->segModelo->getByCliente($cedula);
        return $this->response->json($registros);
    }

    public function insertSeguimiento(): string
    {
        $body = $this->response->getParsedBody();

        // Valida el POST
        $registro = $this->mapper->map(SeguimientoFisicoDTO::class, $body);

        // Verificar que el cliente no exista
        if ($this->clientesModelo->findByCedula($registro->cedula_cliente)) {
            return $this->response->json(['message' => 'El cliente ya existe'], 400);
        }

        // Crea el cliente
        $cliente = $this->segModelo->insert($registro);

        // Enviar JSON
        return $this->response->json($cliente, 201);
    }

    public function updateSeguimiento(array $params): string
    {
        $cedula = $params['cedula'];

        $body = $this->response->getParsedBody();
        $body['cedula_cliente'] = $cedula;

        $registro = $this->mapper->map(SeguimientoFisicoDTO::class, $body);

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 400);
        }

        $registro = $this->segModelo->update($registro);
        return $this->response->json($registro, 201);
    }

    public function deleteSeguimiento(array $params): string|null
    {
        $cedula = $params['cedula'];

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 404);
        }

        $this->segModelo->delete($cedula);
        return $this->response->empty(204);
    }
}
