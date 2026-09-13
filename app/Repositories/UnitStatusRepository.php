<?php

namespace App\Repositories;

use App\Models\UnitStatus;

class UnitStatusRepository
{
    protected $model;

    public function __construct(UnitStatus $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $entity = $this->find($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id)
    {
        $entity = $this->find($id);

        return $entity->delete();
    }
}
