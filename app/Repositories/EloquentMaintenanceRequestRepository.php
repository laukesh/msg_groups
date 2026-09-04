<?php

namespace App\Repositories;

use App\Models\MaintenanceRequest;

class EloquentMaintenanceRequestRepository implements MaintenanceRequestRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = MaintenanceRequest::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('maintenance_number', 'like', "%{ $search }%");
                $q->orWhere('category', 'like', "%{ $search }%");
                $q->orWhere('sub_category', 'like', "%{ $search }%");
                $q->orWhere('title', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
                $q->orWhere('assessment', 'like', "%{ $search }%");
                $q->orWhere('resolution_notes', 'like', "%{ $search }%");

            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($filters['per_page'] ?? 15)->withQueryString();
    }

    public function find(int $id)
    {
        return MaintenanceRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return MaintenanceRequest::create($data);
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
