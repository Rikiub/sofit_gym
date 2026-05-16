<?php

namespace App\Controller\Clientes;

use App\Controllers\BaseControlador;
use App\Model\Clientes\ClientesModelo;
use App\Model\Clientes\SeguimientoFisicoDTO;
use App\Model\Clientes\SeguimientoFisicoModelo;

class SeguimientoFisicoControlador extends BaseControlador
{
    public function __construct(
        public ClientesModelo $clientesModelo,
        public SeguimientoFisicoModelo $segModelo
    ) {}

    public function getByCliente(array $params): ?string
    {
        $cedula = $params['cedula'];

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->empty(404);
        }

        $registros = $this->segModelo->getByCliente($cedula);
        return $this->response->json($registros);
    }

    /**
     * Crear
     */
    public function insert(): string
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

    /**
     * Modificar
     */
    public function update(array $params): string
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

    /**
     * Eliminar
     */
    public function delete(array $params): string|null
    {
        $cedula = $params['cedula'];

        if (!$this->clientesModelo->findByCedula($cedula)) {
            return $this->response->json(['message' => 'El cliente no existe'], 404);
        }

        $this->segModelo->delete($cedula);
        return $this->response->empty(204);
    }
}
