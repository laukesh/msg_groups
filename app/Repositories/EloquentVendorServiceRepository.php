<?php

namespace App\Repositories;

use App\Models\VendorService;

class EloquentVendorServiceRepository implements VendorServiceRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = VendorService::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('service_name', 'like', "%{ $search }%");
                $q->orWhere('service_category', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
                $q->orWhere('rate_unit', 'like', "%{ $search }%");
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
        return VendorService::findOrFail($id);
    }

    public function create(array $data)
    {
        return VendorService::create($data);
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
