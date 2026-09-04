<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Repositories\DepartmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    protected DepartmentRepositoryInterface $repository;

    public function __construct(
        DepartmentRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Display departments.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $departments = $this->repository->paginate(
            $filters,
            15
        );

        return view(
            'admin.assets.departments.index',
            compact('departments')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $departments = $this->repository->dropdown();

        $users = $this->repository->users();

        return view(
            'admin.assets.departments.create',
            compact(
                'departments',
                'users'
            )
        );
    }

    /**
     * Store department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'department_code' => [
                'required',
                'string',
                'max:100',
                'unique:departments,department_code',
            ],

            'department_name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'parent_department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

            'head_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

        ]);

        $validated['uuid'] = (string) Str::uuid();

        $validated['created_by'] = Auth::id();

        $validated['updated_by'] = Auth::id();

        $this->repository->create($validated);

        return redirect()
            ->route('admin.assets.departments.index')
            ->with(
                'success',
                'Department created successfully.'
            );
    }

    /**
     * Display department.
     */
    public function show(int $id)
    {
        $department = $this->repository->find($id);

        abort_if(
            !$department,
            404
        );

        return view(
            'admin.assets.departments.show',
            compact('department')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        $department = $this->repository->find($id);

        abort_if(
            !$department,
            404
        );

        $departments = $this->repository->dropdown(
            $department->id
        );

        $users = $this->repository->users();

        return view(
            'admin.assets.departments.edit',
            compact(
                'department',
                'departments',
                'users'
            )
        );
    }

    /**
     * Update department.
     */
    public function update(
        Request $request,
        int $id
    ) {
        $department = $this->repository->find($id);

        abort_if(
            !$department,
            404
        );

        $validated = $request->validate([

            'department_code' => [
                'required',
                'string',
                'max:100',
                'unique:departments,department_code,' .
                $department->id,
            ],

            'department_name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'parent_department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($department) {

                    if (
                        $value
                        && (int) $value === (int) $department->id
                    ) {
                        $fail(
                            'A department cannot be its own parent.'
                        );
                    }
                },
            ],

            'head_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

        ]);

        $validated['updated_by'] = Auth::id();

        $this->repository->update(
            $department,
            $validated
        );

        return redirect()
            ->route(
                'admin.assets.departments.show',
                $department->id
            )
            ->with(
                'success',
                'Department updated successfully.'
            );
    }

    /**
     * Delete department.
     */
    public function destroy(int $id)
    {
        $department = $this->repository->find($id);

        abort_if(
            !$department,
            404
        );

        $this->repository->delete($department);

        return redirect()
            ->route('admin.assets.departments.index')
            ->with(
                'success',
                'Department deleted successfully.'
            );
    }
}