<?php

namespace App\Controladores;

use App\Core\BaseControlador;
use App\Modelos\Cliente;
use App\Modelos\ClientesModelo;
use DateTimeImmutable;
use Throwable;

class ClientesControlador extends BaseControlador
{
    public function __construct(
        private ClientesModelo $modelo
    ) {}

    public function index(): string
    {
        return $this->render('rutas/clientes', [
            'items' => []
        ]);
    }

    public function get(): string
    {
        $clientes = $this->modelo->getAll();
        return $this->jsonResponse($clientes);
    }

    public function getFind(array $vars): ?string
    {
        $cliente = $this->modelo->findByCedula($vars['cedula']);

        if (!$cliente) {
            http_response_code(404);
            return null;
        }

        return $this->jsonResponse($cliente);
    }

    /**
     * Crear
     */
    public function post(): string
    {
        try {
            $body = $this->getRequestBody();
            $body['fecha_registro'] = new DateTimeImmutable();  // Asignar fecha actual

            // Valida el POST
            $cliente = $this->mapper->map(Cliente::class, $body);

            // Verificar que el cliente no exista
            $check = $this->modelo->findByCedula($cliente->cedula);
            if ($check) {
                return $this->jsonResponse(['error' => 'El cliente ya existe'], 400);
            }

            // Crea el cliente
            $cliente = $this->modelo->insertCliente($cliente);

            // Enviar JSON
            return $this->jsonResponse($cliente, 201);
        } catch (Throwable $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Modificar
     */
    public function put(array $vars): string
    {
        try {
            $cedula = $vars['cedula'];

            $body = $this->getRequestBody();
            $cliente = $this->mapper->map(Cliente::class, $body);

            $check = $this->modelo->findByCedula($cedula);
            if (!$check) {
                return $this->jsonResponse(['error' => 'El cliente no existe'], 400);
            }

            $cliente = $this->modelo->updateCliente($cliente);
            return $this->jsonResponse($cliente, 201);
        } catch (Throwable $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Eliminar
     */
    public function delete(array $vars): string|null
    {
        $cedula = $vars['cedula'];

        $check = $this->modelo->findByCedula($cedula);
        if (!$check) {
            return $this->jsonResponse(['error' => 'El cliente no existe'], 404);
        }

        $this->modelo->deleteByCedula($cedula);

        http_response_code(204);
        return null;
    }
}
