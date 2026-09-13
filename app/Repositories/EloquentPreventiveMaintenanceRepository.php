<?php

namespace App\Repositories;

use App\Models\PreventiveMaintenance;

class EloquentPreventiveMaintenanceRepository implements PreventiveMaintenanceRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = PreventiveMaintenance::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('maintenance_code', 'like', "%{ $search }%");
                $q->orWhere('maintenance_title', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
                $q->orWhere('maintenance_type', 'like', "%{ $search }%");
                $q->orWhere('checklist', 'like', "%{ $search }%");
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
        return PreventiveMaintenance::findOrFail($id);
    }

    public function create(array $data)
    {
        return PreventiveMaintenance::create($data);
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
