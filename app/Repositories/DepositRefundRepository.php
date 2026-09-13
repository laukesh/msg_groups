<?php

namespace App\Repositories;

use App\Models\DepositRefund;

class DepositRefundRepository
{
    protected $model;

    public function __construct(DepositRefund $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['deposit_id'])) {
            $query->where('deposit_id', $filters['deposit_id']);
        }

        return $query->orderBy('refund_date', 'desc')->get();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['deposit_id'])) {
            $query->where('deposit_id', $filters['deposit_id']);
        }

        return $query->orderBy('refund_date', 'desc')->paginate($perPage);
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
