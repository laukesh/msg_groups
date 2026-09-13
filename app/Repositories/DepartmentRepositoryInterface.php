<?php

namespace App\Repositories;

use App\Models\Department;

interface DepartmentRepositoryInterface
{
    /**
     * Get paginated departments.
     */
    public function paginate(
        array $filters = [],
        int $perPage = 15
    );

    /**
     * Find department by ID.
     */
    public function find(int $id): ?Department;

    /**
     * Create department.
     */
    public function create(array $data): Department;

    /**
     * Update department.
     */
    public function update(
        Department $department,
        array $data
    ): bool;

    /**
     * Delete department.
     */
    public function delete(Department $department): bool;

    /**
     * Get departments for dropdown.
     */
    public function dropdown(
        ?int $exceptId = null
    );

    /**
     * Get all users for department head dropdown.
     */
    public function users();
}