<?php

namespace App\Repositories;

use App\Models\Deposit;

class DepositRepository
{
    protected $model;

    public function __construct(Deposit $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['lease_agreement_id'])) {
            $query->where('lease_agreement_id', $filters['lease_agreement_id']);
        }

        return $query->orderBy('due_date', 'desc')->get();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['lease_agreement_id'])) {
            $query->where('lease_agreement_id', $filters['lease_agreement_id']);
        }

        return $query->orderBy('due_date', 'desc')->paginate($perPage);
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
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }
}
