<?php

namespace App\Repositories;

interface UnitTypeRepositoryInterface
{
    /**
     * Get all unit types with optional filters.
     */
    public function all(array $filters = []);

    /**
     * Find a unit type by ID.
     */
    public function find(int $id);

    /**
     * Create a new unit type.
     */
    public function create(array $data);

    /**
     * Update an existing unit type.
     */
    public function update(int $id, array $data);

    /**
     * Delete a unit type.
     */
    public function delete(int $id);
}