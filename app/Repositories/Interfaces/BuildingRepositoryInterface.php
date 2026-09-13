<?php

namespace App\Repositories;

use App\Models\Building;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BuildingRepositoryInterface
{
    /**
     * Get buildings with optional filters.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all(array $filters = []);

    /**
     * Find a building by ID.
     *
     * @param int $id
     * @return Building
     */
    public function find(int $id);

    /**
     * Create a new building.
     *
     * @param array $data
     * @return Building
     */
    public function create(array $data);

    /**
     * Update an existing building.
     *
     * @param Building $building
     * @param array $data
     * @return Building
     */
    public function update(Building $building, array $data);

    /**
     * Delete a building.
     *
     * @param Building $building
     * @return bool
     */
    public function delete(Building $building): bool;
}