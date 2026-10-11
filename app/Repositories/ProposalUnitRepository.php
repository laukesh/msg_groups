<?php

namespace App\Repositories;

use App\Models\ProposalUnit;

class ProposalUnitRepository
{
    protected $model;

    public function __construct(ProposalUnit $model)
    {
        $this->model = $model;
    }

    public function paginate($perPage = 15)
    {
        return $this->model->with(['proposal', 'unit'])->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->with(['proposal', 'unit'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);

        return $item;
    }

    public function delete($id)
    {
        $item = $this->find($id);

        return $item->delete();
    }
}
