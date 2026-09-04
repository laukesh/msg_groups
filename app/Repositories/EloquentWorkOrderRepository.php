<?php

namespace App\Repositories;

use App\Models\WorkOrder;

class EloquentWorkOrderRepository implements WorkOrderRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = WorkOrder::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('work_order_number', 'like', "%{ $search }%");
                $q->orWhere('work_title', 'like', "%{ $search }%");
                $q->orWhere('work_description', 'like', "%{ $search }%");
                $q->orWhere('completion_notes', 'like', "%{ $search }%");
                $q->orWhere('verification_notes', 'like', "%{ $search }%");

            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($filters['per_page'] ?? 15)->withQueryString();
    }

    public function find(int $id)
    {
        return WorkOrder::findOrFail($id);
    }

    public function create(array $data)
    {
        return WorkOrder::create($data);
    }

    public function update(int $id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item->refresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->find($id)->delete();
    }
}
