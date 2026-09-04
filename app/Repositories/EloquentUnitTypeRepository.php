<?php

namespace App\Repositories;

use App\Models\UnitType;

class EloquentUnitTypeRepository implements UnitTypeRepositoryInterface
{
    /**
     * Get all unit types.
     */
    public function all(array $filters = [])
    {
        $query = UnitType::with([
            'creator',
            'updater',
            'units',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where('type_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if (isset($filters['status']) && $filters['status'] !== '') {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        return $query
            ->latest('id')
            ->paginate(15);
    }

    /**
     * Find unit type by ID.
     */
    public function find(int $id)
    {
        return UnitType::with([
            'creator',
            'updater',
            'units',
        ])->findOrFail($id);
    }

    /**
     * Create unit type.
     */
    public function create(array $data)
    {
        return UnitType::create($data);
    }

    /**
     * Update unit type.
     */
    public function update(int $id, array $data)
    {
        $unitType = UnitType::findOrFail($id);

        $unitType->update($data);

        return $unitType->fresh([
            'creator',
            'updater',
            'units',
        ]);
    }

    /**
     * Delete unit type.
     */
    public function delete(int $id)
    {
        $unitType = UnitType::findOrFail($id);

        return $unitType->delete();
    }
}