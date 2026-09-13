<?php

namespace App\Repositories;

use App\Models\Zone;

class EloquentZoneRepository implements ZoneRepositoryInterface
{
    /**
     * Get all zones.
     */
    public function all(array $filters = [])
    {
        $query = Zone::with([
            'floor',
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

                $q->where('zone_code', 'like', "%{$search}%")
                    ->orWhere('zone_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by Floor
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['floor_id'])) {

            $query->where(
                'floor_id',
                $filters['floor_id']
            );
        }

        return $query
            ->orderBy('floor_id')
            ->orderBy('zone_name')
            ->get();
    }

    /**
     * Find zone.
     */
    public function find($id)
    {
        return Zone::with([
            'floor',
            'creator',
            'updater',
        ])->find($id);
    }

    /**
     * Create zone.
     */
    public function create(array $data)
    {
        return Zone::create($data);
    }

    /**
     * Update zone.
     */
    public function update(Zone $zone, array $data)
    {
        $zone->update($data);

        return $zone->fresh([
            'floor',
            'creator',
            'updater',
        ]);
    }

    /**
     * Delete zone.
     */
    public function delete(Zone $zone)
    {
        return $zone->delete();
    }
}