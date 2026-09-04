<?php

namespace App\Repositories;

use App\Models\Complaint;

class EloquentComplaintRepository implements ComplaintRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = Complaint::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{ $search }%");
                $q->orWhere('complaint_number', 'like', "%{ $search }%");
                $q->orWhere('complaint_category', 'like', "%{ $search }%");
                $q->orWhere('subject', 'like', "%{ $search }%");
                $q->orWhere('description', 'like', "%{ $search }%");
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
        return Complaint::findOrFail($id);
    }

    public function create(array $data)
    {
        return Complaint::create($data);
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
