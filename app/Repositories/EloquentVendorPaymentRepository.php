<?php

namespace App\Repositories;

use App\Models\VendorPayment;

class EloquentVendorPaymentRepository implements VendorPaymentRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = VendorPayment::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('payment_number', 'like', "%{ $search }%");
                $q->orWhere('invoice_number', 'like', "%{ $search }%");
                $q->orWhere('transaction_reference', 'like', "%{ $search }%");
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
        return VendorPayment::findOrFail($id);
    }

    public function create(array $data)
    {
        return VendorPayment::create($data);
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
