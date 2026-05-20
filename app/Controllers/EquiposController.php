<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Equipos\EquipoDTO;
use App\Models\Equipos\EquiposModel;
use Exception;

class EquiposController extends BaseController
{
    public function __construct(private EquiposModel $equiposModel) {}

    public function index()
    {
        return $this->templates->render("equipos");
    }

    private function getIdParam(): string
    {
        $codigo = $_GET['codigo'] ?? $_GET['id'] ?? '';
        if (!$codigo) {
            throw new Exception("'id' or 'codigo_equipo' param is required");
        }
        return $codigo;
    }

    public function getAllEquipos()
    {
        $results = $this->equiposModel->getAll();
        return $this->response->json($results);
    }

    public function findEquipo(): ?string
    {
        $id = $this->getIdParam();
        $equipo = $this->equiposModel->find($id);

        if (!$equipo) {
            return $this->response->empty(404);
        }

        return $this->response->json($equipo);
    }

    public function insertEquipo(): string
    {
        $body = $this->response->getParsedBody();
        $equipo = $this->mapper->map(EquipoDTO::class, $body);

        // Verificar que el equipo no exista
        if ($this->equiposModel->find($equipo->codigo)) {
            return $this->response->json(['message' => 'El equipo ya existe'], 400);
        }

        $equipo = $this->equiposModel->insert($equipo);
        return $this->response->json($equipo, 201);
    }

    public function updateEquipo(): string
    {
        $body = $this->response->getParsedBody();
        $equipo = $this->mapper->map(EquipoDTO::class, $body);

        if (!$this->equiposModel->find($equipo->codigo)) {
            return $this->response->json(['message' => 'El equipo no existe'], 404);
        }

        $equipo = $this->equiposModel->update($equipo);
        return $this->response->json($equipo, 201);
    }

    public function deleteEquipo(): string|null
    {
        $codigo = $this->getIdParam();

        if (!$this->equiposModel->find($codigo)) {
            return $this->response->json(['message' => 'El equipo no existe'], 404);
        }

        $this->equiposModel->delete($codigo);
        return $this->response->empty(204);
    }
}
