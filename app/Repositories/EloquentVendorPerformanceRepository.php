<?php

namespace App\Repositories;

use App\Models\VendorPerformance;

class EloquentVendorPerformanceRepository implements VendorPerformanceRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = VendorPerformance::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('strengths', 'like', "%{ $search }%");
                $q->orWhere('issues', 'like', "%{ $search }%");
                $q->orWhere('improvement_plan', 'like', "%{ $search }%");
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
        return VendorPerformance::findOrFail($id);
    }

    public function create(array $data)
    {
        return VendorPerformance::create($data);
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
