<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionManpower;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionManpowerController extends Controller
{
    /**
     * Display manpower for the selected project.
     */
    public function index(
        Request $request,
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Project Manpower Query
        |--------------------------------------------------------------------------
        */

        $query = ConstructionManpower::query()
            ->where('project_id', $project->id)
            ->with([
                'project',
                'creator',
                'updater',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'manpower_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'manpower_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'trade',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Manpower Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('manpower_type')) {

            $query->where(
                'manpower_type',
                $request->manpower_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Employment Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('employment_type')) {

            $query->where(
                'employment_type',
                $request->employment_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $manpower = $query
            ->orderBy('manpower_name')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Project-wise Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionManpower::query()
            ->where('project_id', $project->id);

        $total = (clone $summaryQuery)
            ->count();

        $active = (clone $summaryQuery)
            ->where('status', 'Active')
            ->count();

        $inactive = (clone $summaryQuery)
            ->where('status', 'Inactive')
            ->count();

        $skilled = (clone $summaryQuery)
            ->where('manpower_type', 'Skilled')
            ->where('status', 'Active')
            ->count();

        return view(
            'construction.manpower.index',
            compact(
                'project',
                'manpower',
                'total',
                'active',
                'inactive',
                'skilled'
            )
        );
    }


    /**
     * Show create manpower form.
     */
    public function create(
        Project $project
    ): View {

        return view(
            'construction.manpower.create',
            compact('project')
        );
    }


    /**
     * Store manpower for the selected project.
     */
    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'manpower_name' => [
                'required',
                'string',
                'max:150',
            ],

            'manpower_type' => [
                'required',
                'in:Skilled,Semi-Skilled,Unskilled,Supervisor,Engineer,Technician,Operator,Other',
            ],

            'trade' => [
                'nullable',
                'string',
                'max:100',
            ],

            'employment_type' => [
                'required',
                'in:Direct,Contract,Subcontract,Temporary',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Project Manpower
        |--------------------------------------------------------------------------
        */

        $manpower = DB::transaction(function () use (
            $validated,
            $project
        ) {

            return ConstructionManpower::create([

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                | Project ID comes from the route.
                */

                'project_id' =>
                    $project->id,

                'manpower_code' =>
                    $this->generateManpowerCode($project),

                'manpower_name' =>
                    $validated['manpower_name'],

                'manpower_type' =>
                    $validated['manpower_type'],

                'trade' =>
                    $validated['trade'] ?? null,

                'employment_type' =>
                    $validated['employment_type'],

                'phone' =>
                    $validated['phone'] ?? null,

                'joining_date' =>
                    $validated['joining_date'] ?? null,

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Redirect to Project Manpower Detail
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.construction.manpower.show',
                [
                    'project' => $project->id,
                    'manpower' => $manpower->id,
                ]
            )
            ->with(
                'success',
                'Manpower created successfully.'
            );
    }


    /**
     * Display manpower details.
     */
    public function show(
        Project $project,
        ConstructionManpower $manpower
    ): View {

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT PROJECT CHECK
        |--------------------------------------------------------------------------
        |
        | The manpower must belong to the project in the URL.
        |
        */

        abort_unless(
            $manpower->project_id == $project->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Load Project-specific Relationships
        |--------------------------------------------------------------------------
        */

        $manpower->load([

            /*
            |--------------------------------------------------------------------------
            | Assignments
            |--------------------------------------------------------------------------
            */

            'assignments' => function ($query) use ($project) {

                $query
                    ->where('project_id', $project->id)
                    ->with([
                        'project',
                        'workOrder',
                    ])
                    ->latest('assignment_date');
            },

            /*
            |--------------------------------------------------------------------------
            | Daily Entries
            |--------------------------------------------------------------------------
            */

            'entries' => function ($query) use ($project) {

                $query
                    ->where('project_id', $project->id)
                    ->with([
                        'project',
                        'workOrder',
                        'assignment',
                    ])
                    ->latest('entry_date');
            },

            'project',
            'creator',
            'updater',
        ]);

        return view(
            'construction.manpower.show',
            compact(
                'project',
                'manpower'
            )
        );
    }


    /**
     * Show edit manpower form.
     */
    public function edit(
        Project $project,
        ConstructionManpower $manpower
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Project Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $manpower->project_id == $project->id,
            404
        );

        return view(
            'construction.manpower.edit',
            compact(
                'project',
                'manpower'
            )
        );
    }


    /**
     * Update manpower.
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionManpower $manpower
    ) {

        /*
        |--------------------------------------------------------------------------
        | Project Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $manpower->project_id == $project->id,
            404
        );

        $validated = $request->validate([

            'manpower_name' => [
                'required',
                'string',
                'max:150',
            ],

            'manpower_type' => [
                'required',
                'in:Skilled,Semi-Skilled,Unskilled,Supervisor,Engineer,Technician,Operator,Other',
            ],

            'trade' => [
                'nullable',
                'string',
                'max:100',
            ],

            'employment_type' => [
                'required',
                'in:Direct,Contract,Subcontract,Temporary',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        |
        | project_id and manpower_code are intentionally NOT changed.
        |
        */

        $manpower->update([

            'manpower_name' =>
                $validated['manpower_name'],

            'manpower_type' =>
                $validated['manpower_type'],

            'trade' =>
                $validated['trade'] ?? null,

            'employment_type' =>
                $validated['employment_type'],

            'phone' =>
                $validated['phone'] ?? null,

            'joining_date' =>
                $validated['joining_date'] ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.manpower.show',
                [
                    'project' => $project->id,
                    'manpower' => $manpower->id,
                ]
            )
            ->with(
                'success',
                'Manpower updated successfully.'
            );
    }


    /**
     * Delete manpower.
     */
    public function destroy(
        Project $project,
        ConstructionManpower $manpower
    ) {

        /*
        |--------------------------------------------------------------------------
        | Project Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $manpower->project_id == $project->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent deletion when assignment history exists
        |--------------------------------------------------------------------------
        */

        if (
            $manpower->assignments()->exists()
            ||
            $manpower->entries()->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This manpower record cannot be deleted because it has assignment or daily entry history.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $manpower->updated_by =
            auth()->id();

        $manpower->save();

        $manpower->delete();

        return redirect()
            ->route(
                'admin.projects.construction.manpower.index',
                $project
            )
            ->with(
                'success',
                'Manpower deleted successfully.'
            );
    }


    /**
     * Generate manpower code.
     *
     * Example:
     * MP-000001
     * MP-000002
     * MP-000003
     */
    protected function generateManpowerCode(
        Project $project
    ): string {

        $next =
            (
                ConstructionManpower
                    ::withTrashed()
                    ->max('id')
                ?? 0
            ) + 1;

        return 'MP-'
            . str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}