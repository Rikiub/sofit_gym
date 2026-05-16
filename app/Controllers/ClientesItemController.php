<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesItemModel;
use App\Models\ClientesModel;
use App\Models\SeguimientoFisicoDTO;
use Exception;

class ClientesItemController extends BaseController
{
    public function __construct(
        private ClientesModel $clientesModelo,
        private ClientesItemModel $segModelo
    ) {}

    // CLIENTES: Pagina unica

    public function index(): string
    {
        $cedula = $this->getCedulaParam();

        $cliente = $this->clientesModelo->findByCedula($cedula);
        if (!$cliente) {
            $this->redirectToError();
        }

        // Cargar vista: app/views/clientes/item.php
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

    private function getCedulaParam(): string
    {
        $cedula = $_GET['cedula'] ?? $_GET['id'] ?? '';
        if (!$cedula) {
            throw new Exception("'id' or 'cedula' param is required");
        }
        return $cedula;
    }

    // SEGUIMIENTO FISICO: JSON API

    public function getSeguimientoByCliente(): ?string
    {
        $cedula = $this->getCedulaParam();

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

    public function updateSeguimiento(): string
    {
        $cedula = $this->getCedulaParam();

        $body = $this->response->getParsedBody();
        $body['cedula_cliente'] = $cedula;

        $registro = $this->mapper->map(SeguimientoFisicoDTO::class, $body);

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 400);
        }

        $registro = $this->segModelo->update($registro);
        return $this->response->json($registro, 201);
    }

    public function deleteSeguimiento(): string|null
    {
        $cedula = $this->getCedulaParam();

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 404);
        }

        $this->segModelo->delete($cedula);
        return $this->response->empty(204);
    }
}
