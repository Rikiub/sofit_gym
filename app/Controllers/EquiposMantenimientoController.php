<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Equipos\MantenimientoEquipoDTO;
use App\Models\Equipos\MantenimientoEquipoModel;
use CuyZ\Valinor\Mapper\TreeMapper;
use Exception;

class EquiposMantenimientoController extends BaseController
{
    public function __construct(
        private Response $response,
        private TreeMapper $mapper,
        private MantenimientoEquipoModel $model,
    ) {}

    public function index()
    {
        return $this->templates->render('equipos_mantenimiento');
    }

    private function getIdParam(): int
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception("'id' param is required");
        }
        return (int) $id;
    }

    public function getAll()
    {
        $results = $this->model->getAll();
        return $this->response->json($results);
    }

    public function find(): ?string
    {
        $id = $this->getIdParam();
        $man = $this->model->find($id);

        if (!$man) {
            return $this->response->empty(404);
        }

        return $this->response->json($man);
    }

    public function insert(): string
    {
        $body = $this->response->getParsedBody();
        $man = $this->mapper->map(MantenimientoEquipoDTO::class, $body);

        $man = $this->model->insert($man);
        return $this->response->json($man, 201);
    }

    public function update(): string
    {
        $body = $this->response->getParsedBody();
        $body["id"] = $this->getIdParam();
        $man = $this->mapper->map(MantenimientoEquipoDTO::class, $body);

        if (!$this->model->find($man->id)) {
            return $this->response->json(['message' => 'El mantenimiento no existe'], 404);
        }

        $man = $this->model->update($man);
        return $this->response->json($man, 201);
    }

    public function delete(): string|null
    {
        $id = $this->getIdParam();

        if (!$this->model->find($id)) {
            return $this->response->json(['message' => 'El mantenimiento no existe'], 404);
        }

        $this->model->delete($id);
        return $this->response->empty(204);
    }
}
