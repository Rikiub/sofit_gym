<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes\ClienteDTO;
use App\Models\Clientes\ClientesModel;
use Exception;

class ClientesController extends BaseController
{
    public function __construct(
        private ClientesModel $clientesModelo,
    ) {}

    // CLIENTES

    public function index(): string
    {
        // Cargar vista app/views/clientes/index.php
        $templates = $this->templates->addData(['formMeta' => $this->formMeta()]);
        return $templates->render('clientes/index');
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

    private function getCedulaParam(): string
    {
        $cedula = $_GET['cedula'] ?? $_GET['id'] ?? '';
        if (!$cedula) {
            throw new Exception("'id' or 'cedula' param is required");
        }
        return $cedula;
    }

    public function getClientes(): string
    {
        $clientes = $this->clientesModelo->getAll();
        return $this->response->json($clientes);
    }

    public function findCliente(): ?string
    {
        $cedula = $this->getCedulaParam();
        $cliente = $this->clientesModelo->find($cedula);

        if (!$cliente) {
            return $this->response->empty(404);
        }

        return $this->response->json($cliente);
    }

    public function insertCliente(): string
    {
        $body = $this->response->getParsedBody();
        $cliente = $this->mapper->map(ClienteDTO::class, $body);

        // Verificar que el cliente no exista
        if ($this->clientesModelo->find($cliente->cedula)) {
            return $this->response->json(['message' => 'El cliente ya existe'], 400);
        }

        $cliente = $this->clientesModelo->insert($cliente);
        return $this->response->json($cliente, 201);
    }

    public function updateCliente(): string
    {
        $body = $this->response->getParsedBody();
        $cliente = $this->mapper->map(ClienteDTO::class, $body);

        if (!$this->clientesModelo->find($cliente->cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 400);
        }

        $cliente = $this->clientesModelo->update($cliente);
        return $this->response->json($cliente, 201);
    }

    public function deleteCliente(): string|null
    {
        $cedula = $this->getCedulaParam();

        if (!$this->clientesModelo->find($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 404);
        }

        $this->clientesModelo->delete($cedula);
        return $this->response->empty(204);
    }
}
