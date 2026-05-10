<?php

namespace App\Controladores;

use App\Core\BaseControlador;
use App\Modelos\Cliente;
use App\Modelos\ClientesModelo;

class ClientesControlador extends BaseControlador
{
    public function __construct(
        private ClientesModelo $modelo
    ) {}

    public function index(): string
    {
        $tipos = $this->modelo->getEstadosMembresia();
        $estados = $this->modelo->getTiposMembresia();

        return $this->render('clientes/index', [
            'tipos' => $tipos,
            'estados' => $estados,
        ]);
    }

    public function getClientes(): string
    {
        $clientes = $this->modelo->getAll();
        return $this->jsonResponse($clientes);
    }

    public function findCliente(array $vars): ?string
    {
        $cliente = $this->modelo->findByCedula($vars['cedula']);

        if (!$cliente) {
            return $this->emptyBodyResponse(404);
        }

        return $this->jsonResponse($cliente);
    }

    /**
     * Crear
     */
    public function insertCliente(): string
    {
        $body = $this->getParsedBody();

        // Valida el POST
        $cliente = $this->mapper->map(Cliente::class, $body);

        // Verificar que el cliente no exista
        if ($this->modelo->findByCedula($cliente->cedula)) {
            return $this->jsonResponse(['message' => 'El cliente ya existe'], 400);
        }

        // Crea el cliente
        $cliente = $this->modelo->insertCliente($cliente);

        // Enviar JSON
        return $this->jsonResponse($cliente, 201);
    }

    /**
     * Modificar
     */
    public function updateCliente(array $vars): string
    {
        $cedula = $vars['cedula'];

        $body = $this->getParsedBody();
        $body['cedula'] = $cedula;

        $cliente = $this->mapper->map(Cliente::class, $body);

        if (!$this->modelo->findByCedula($cedula)) {
            return $this->jsonResponse(['message' => 'El cliente no existe'], 400);
        }

        $cliente = $this->modelo->updateCliente($cliente);
        return $this->jsonResponse($cliente, 201);
    }

    /**
     * Eliminar
     */
    public function deleteCliente(array $vars): string|null
    {
        $cedula = $vars['cedula'];

        if (!$this->modelo->findByCedula($cedula)) {
            return $this->jsonResponse(['message' => 'El cliente no existe'], 404);
        }

        $this->modelo->deleteByCedula($cedula);

        return $this->emptyBodyResponse(204);
    }
}
