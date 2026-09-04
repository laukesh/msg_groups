<?php

namespace App\Repositories;

use App\Models\Asset;

class EloquentAssetRepository implements AssetRepositoryInterface
{
    /**
     * Get all assets.
     */
    public function all(array $filters = [])
    {
        $query = Asset::query()
            ->with([
                'assetCategory',
                'unit',
                'building',
                'floor',
                'zone',
                'department',
                'assignedUser',
                'vendor',
            ]);

        $this->applyFilters($query, $filters);

        return $query
            ->latest('id')
            ->get();
    }

    /**
     * Get paginated assets.
     */
    public function paginate(
        array $filters = [],
        int $perPage = 15
    ) {
        $query = Asset::query()
            ->with([
                'assetCategory',
                'unit',
                'building',
                'floor',
                'zone',
                'department',
                'assignedUser',
                'vendor',
            ]);

        $this->applyFilters($query, $filters);

        return $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Apply filters to asset query.
     */
    protected function applyFilters($query, array $filters): void
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'asset_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'asset_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'serial_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'model_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'manufacturer',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['status'])
            && $filters['status'] !== ''
        ) {
            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Asset Category
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['asset_category'])
            && $filters['asset_category'] !== ''
        ) {
            $query->where(
                'asset_category',
                $filters['asset_category']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Building
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['building_id'])
            && $filters['building_id'] !== ''
        ) {
            $query->where(
                'building_id',
                $filters['building_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Floor
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['floor_id'])
            && $filters['floor_id'] !== ''
        ) {
            $query->where(
                'floor_id',
                $filters['floor_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Zone
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['zone_id'])
            && $filters['zone_id'] !== ''
        ) {
            $query->where(
                'zone_id',
                $filters['zone_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unit
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['unit_id'])
            && $filters['unit_id'] !== ''
        ) {
            $query->where(
                'unit_id',
                $filters['unit_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['department_id'])
            && $filters['department_id'] !== ''
        ) {
            $query->where(
                'department_id',
                $filters['department_id']
            );
        }
    }

    /**
     * Find asset by ID.
     */
    public function find(int $id)
    {
        return Asset::with([
            'assetCategory',
            'unit',
            'building',
            'floor',
            'zone',
            'department',
            'assignedUser',
            'vendor',
            'creator',
            'updater',
        ])->findOrFail($id);
    }

    /**
     * Create asset.
     */
    public function create(array $data)
    {
        return Asset::create($data);
    }

    /**
     * Update asset.
     */
    public function update(int $id, array $data)
    {
        $asset = Asset::findOrFail($id);

        $asset->update($data);

        return $asset->fresh([
            'assetCategory',
            'unit',
            'building',
            'floor',
            'zone',
            'department',
            'assignedUser',
            'vendor',
        ]);
    }

    /**
     * Delete asset.
     */
    public function delete(int $id): bool
    {
        $asset = Asset::findOrFail($id);

        return (bool) $asset->delete();
    }
      public function getEconomicSummary(int $id)
    {
        $asset = $this->find($id);

        return [
            'income' => $asset->total_income,
            'operating_expenses' => $asset->operating_expenses,
            'noi' => $asset->noi,
            'roi' => $asset->roi,
        ];
    }
}