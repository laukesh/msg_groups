<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository
{
    protected $model;

    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    public function paginate($perPage = 15)
    {
        return $this->model->with(['mall', 'building', 'floor', 'zone', 'unitType', 'unitStatus'])->paginate($perPage);
    }

    public function all()
    {
        return $this->model->all();
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
        $unit = $this->find($id);
        $unit->update($data);

        return $unit;
    }

    public function delete($id)
    {
        $unit = $this->find($id);

        return $unit->delete();
    }
}
