<?php

namespace App\Repositories;

use App\Models\VendorContract;

class EloquentVendorContractRepository implements VendorContractRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = VendorContract::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('contract_number', 'like', "%{ $search }%");
                $q->orWhere('contract_title', 'like', "%{ $search }%");
                $q->orWhere('contract_type', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
                $q->orWhere('payment_terms', 'like', "%{ $search }%");
                $q->orWhere('document_path', 'like', "%{ $search }%");
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
        return VendorContract::findOrFail($id);
    }

    public function create(array $data)
    {
        return VendorContract::create($data);
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
