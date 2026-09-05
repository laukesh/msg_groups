<?php

namespace App\Repositories;

use App\Models\AssetIncome;

class EloquentAssetIncomeRepository
    implements AssetIncomeRepositoryInterface
{
    public function paginateByAsset(
        int $assetId,
        array $filters = [],
        int $perPage = 15
    ) {
        $query = AssetIncome::query()
            ->where('asset_id', $assetId);

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where(
                    'income_type',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'remarks',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        return $query
            ->latest('income_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id)
    {
        return AssetIncome::findOrFail($id);
    }

    public function create(array $data)
    {
        return AssetIncome::create($data);
    }

    public function update(
        int $id,
        array $data
    ) {
        $income = AssetIncome::findOrFail($id);

        $income->update($data);

        return $income->fresh();
    }

    public function delete(int $id)
    {
        return AssetIncome::findOrFail($id)->delete();
    }
}