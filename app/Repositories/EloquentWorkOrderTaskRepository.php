<?php

namespace App\Repositories;

use App\Models\WorkOrderTask;

class EloquentWorkOrderTaskRepository implements WorkOrderTaskRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = WorkOrderTask::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('task_number', 'like', "%{ $search }%");
                $q->orWhere('task_title', 'like', "%{ $search }%");
                $q->orWhere('task_description', 'like', "%{ $search }%");
                $q->orWhere('completion_notes', 'like', "%{ $search }%");

            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($filters['per_page'] ?? 15)->withQueryString();
    }

    public function find(int $id)
    {
        return WorkOrderTask::findOrFail($id);
    }

    public function create(array $data)
    {
        return WorkOrderTask::create($data);
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
