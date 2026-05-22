<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TrabajadorDTO;
use App\Models\TrabajadoresModel;
use Exception;

class TrabajadoresController extends BaseController
{
    public function __construct(
        private TrabajadoresModel $trabajadoresModel
    ) {}

    public function index(): string
    {
        return $this->templates->render('trabajadores');
    }

    private function getCedulaParam(): string
    {
        $cedula = $_GET['cedula'] ?? $_GET['id'] ?? '';
        if (!$cedula) {
            throw new Exception("'id' or 'cedula' param is required");
        }
        return $cedula;
    }

    public function getAll(): string
    {
        $trabajadores = $this->trabajadoresModel->getAll();
        return $this->response->json($trabajadores);
    }

    public function find(): ?string
    {
        $cedula = $this->getCedulaParam();
        $trabajador = $this->trabajadoresModel->find($cedula);

        if (!$trabajador) {
            return $this->response->empty(404);
        }

        return $this->response->json($trabajador);
    }

    public function insert(): string
    {
        $body = $this->response->getParsedBody();
        $trabajador = $this->mapper->map(TrabajadorDTO::class, $body);

        if ($this->trabajadoresModel->find($trabajador->cedula)) {
            return $this->response->json(['message' => 'El trabajador ya existe'], 400);
        }

        $this->trabajadoresModel->insert($trabajador);
        $trabajador = $this->trabajadoresModel->find($trabajador->cedula);

        return $this->response->json($trabajador, 201);
    }

    public function update(): string
    {
        $body = $this->response->getParsedBody();
        $trabajador = $this->mapper->map(TrabajadorDTO::class, $body);

        if (!$this->trabajadoresModel->find($trabajador->cedula)) {
            return $this->response->json(['message' => 'El trabajador no existe'], 400);
        }

        $this->trabajadoresModel->update($trabajador);
        $trabajador = $this->trabajadoresModel->find($trabajador->cedula);

        return $this->response->json($trabajador, 201);
    }

    public function delete(): string|null
    {
        $cedula = $this->getCedulaParam();

        if (!$this->trabajadoresModel->find($cedula)) {
            return $this->response->json(['message' => 'El trabajador no existe'], 404);
        }

        $this->trabajadoresModel->delete($cedula);
        return $this->response->empty(204);
    }
}
