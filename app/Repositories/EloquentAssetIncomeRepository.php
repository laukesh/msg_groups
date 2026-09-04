<?php

namespace App\Repositories;

use App\Models\AssetIncome;

class EloquentAssetIncomeRepository implements AssetIncomeRepositoryInterface
{
    /**
     * Get paginated incomes for a specific asset.
     */
    public function paginateByAsset(
        int $assetId,
        array $filters = [],
        int $perPage = 15
    ) {
        $query = AssetIncome::query()
            ->where('asset_id', $assetId);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('income_type', 'like', '%' . $search . '%')
                    ->orWhere('remarks', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query
            ->latest('income_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find an income by ID.
     */
    public function find(int $id)
    {
        return AssetIncome::findOrFail($id);
    }

    /**
     * Create a new asset income.
     */
    public function create(array $data)
    {
        return AssetIncome::create($data);
    }

    /**
     * Update an asset income.
     */
    public function update(
        int $id,
        array $data
    ) {
        $income = AssetIncome::findOrFail($id);

        $income->update($data);

        return $income->fresh();
    }

    /**
     * Delete an asset income.
     */
    public function delete(int $id)
    {
        return AssetIncome::findOrFail($id)->delete();
    }

    /**
     * Get all asset incomes with pagination.
     */
    public function paginate(
        int $perPage = 15,
        array $filters = []
    ) {
        $query = AssetIncome::query();

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('income_type', 'like', '%' . $search . '%')
                    ->orWhere('remarks', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query
            ->latest('income_date')
            ->paginate($perPage)
            ->withQueryString();
    }
}