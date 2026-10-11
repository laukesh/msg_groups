<?php

namespace App\Repositories;

use App\Models\Floor;

class EloquentFloorRepository implements FloorRepositoryInterface
{
    /**
     * Get all floors.
     */
    public function all(array $filters = [])
    {
        $query = Floor::with([
            'building',
            'creator',
            'updater',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('floor_code', 'like', "%{$search}%")
                    ->orWhere('floor_name', 'like', "%{$search}%")
                    ->orWhere('floor_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['building_id'])) {
            $query->where(
                'building_id',
                $filters['building_id']
            );
        }

        return $query
            ->orderBy('building_id')
            ->orderBy('floor_number')
            ->get();
    }

    /**
     * Find floor.
     */
    public function find($id)
    {
        return Floor::with([
            'building',
            'creator',
            'updater',
        ])->find($id);
    }

    /**
     * Create floor.
     */
    public function create(array $data)
    {
        return Floor::create($data);
    }

    /**
     * Update floor.
     */
    public function update(Floor $floor, array $data)
    {
        $floor->update($data);

        return $floor->fresh([
            'building',
            'creator',
            'updater',
        ]);
    }

    /**
     * Delete floor.
     */
    public function delete(Floor $floor)
    {
        return $floor->delete();
    }
}