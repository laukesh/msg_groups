<?php

namespace App\Repositories;

use App\Models\AssetCategory;

class EloquentAssetCategoryRepository implements AssetCategoryRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = AssetCategory::with([
            'creator',
            'updater',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query
            ->latest('id')
            ->paginate(15);
    }

    public function find(int $id)
    {
        return AssetCategory::with([
            'creator',
            'updater',
            'assets',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return AssetCategory::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = AssetCategory::findOrFail($id);

        $category->update($data);

        return $category->fresh();
    }

    public function delete(int $id)
    {
        $category = AssetCategory::findOrFail($id);

        return $category->delete();
    }
}