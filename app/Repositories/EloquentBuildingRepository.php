<?php

namespace App\Repositories;

use App\Models\Building;

class EloquentBuildingRepository
    implements BuildingRepositoryInterface
{
    /**
     * Get buildings.
     */
    public function all(array $filters = [])
    {
        $query = Building::with([
            'mall',
            'creator',
            'updater',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where(
                    'building_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'building_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Mall Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['mall_id'])) {

            $query->where(
                'mall_id',
                $filters['mall_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['status']) &&
            $filters['status'] !== ''
        ) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        return $query
            ->orderBy('building_name')
            ->get();
    }

    /**
     * Find building.
     */
    public function find($id)
    {
        return Building::with([
            'mall',
            'creator',
            'updater',
            'floors',
        ])->find($id);
    }

    /**
     * Create building.
     */
    public function create(array $data)
    {
        return Building::create($data);
    }

    /**
     * Update building.
     */
    public function update(
        Building $building,
        array $data
    ) {
        $building->update($data);

        return $building->fresh([
            'mall',
            'creator',
            'updater',
        ]);
    }

    /**
     * Delete building.
     */
    public function delete(Building $building)
    {
        return $building->delete();
    }
}