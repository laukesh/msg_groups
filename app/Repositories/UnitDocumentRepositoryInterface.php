<?php

namespace App\Repositories;

interface UnitDocumentRepositoryInterface
{
    /**
     * Get all unit documents with filters.
     */
    public function all(array $filters = []);

    /**
     * Find a unit document by ID.
     */
    public function find(int $id);

    /**
     * Create a unit document.
     */
    public function create(array $data);

    /**
     * Update a unit document.
     */
    public function update(int $id, array $data);

    /**
     * Delete a unit document.
     */
    public function delete(int $id);
}