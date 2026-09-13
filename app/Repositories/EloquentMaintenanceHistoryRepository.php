<?php

namespace App\Repositories;

use App\Models\MaintenanceHistory;

class EloquentMaintenanceHistoryRepository implements MaintenanceHistoryRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = MaintenanceHistory::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('history_number', 'like', "%{ $search }%");
                $q->orWhere('maintenance_type', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
                $q->orWhere('problem_reported', 'like', "%{ $search }%");
                $q->orWhere('work_performed', 'like', "%{ $search }%");
                $q->orWhere('findings', 'like', "%{ $search }%");
                $q->orWhere('parts_replaced', 'like', "%{ $search }%");
                $q->orWhere('remarks', 'like', "%{ $search }%");

            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($filters['per_page'] ?? 15)->withQueryString();
    }

    public function find(int $id)
    {
        return MaintenanceHistory::findOrFail($id);
    }

    public function create(array $data)
    {
        return MaintenanceHistory::create($data);
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
