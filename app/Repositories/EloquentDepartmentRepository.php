<?php

namespace App\Repositories;

use App\Models\Department;
use App\Models\User;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    /**
     * Get paginated departments.
     */
    public function paginate(
        array $filters = [],
        int $perPage = 15
    ) {
        $query = Department::query()->with([
            'parentDepartment',
            'headUser',
            'creator',
            'updater',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('department_code', 'like', "%{$search}%")
                    ->orWhere('department_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
        | Parent Department
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['parent_department_id'])
            && $filters['parent_department_id'] !== ''
        ) {
            $query->where(
                'parent_department_id',
                $filters['parent_department_id']
            );
        }

        return $query
            ->orderBy('department_name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Find department by ID.
     */
    public function find(int $id): ?Department
    {
        return Department::with([
            'parentDepartment',
            'childDepartments',
            'headUser',
            'creator',
            'updater',
        ])->find($id);
    }

    /**
     * Create department.
     */
    public function create(array $data): Department
    {
        return Department::create($data);
    }

    /**
     * Update department.
     */
    public function update(
        Department $department,
        array $data
    ): bool {
        return $department->update($data);
    }

    /**
     * Delete department.
     */
    public function delete(Department $department): bool
    {
        return $department->delete();
    }

    /**
     * Get departments for dropdown.
     */
    public function dropdown(?int $exceptId = null)
    {
        $query = Department::query()
            ->orderBy('department_name', 'asc');

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->pluck(
            'department_name',
            'id'
        );
    }

    /**
     * Get users having the "departments" role
     * for Department Head dropdown.
     */
    public function users()
    {
        return User::role('Department')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');
    }
}