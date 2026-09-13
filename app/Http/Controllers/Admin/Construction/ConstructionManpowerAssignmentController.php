<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionManpower;
use App\Models\ConstructionManpowerAssignment;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstructionManpowerAssignmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, Project $project)
    {
        $query = ConstructionManpowerAssignment::query()
            ->where('project_id', $project->id)
            ->with([
                'manpower',
                'workOrder',
                'creator',
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
                    'assignment_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'role',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('manpower', function ($q2) use ($search) {

                    $q2->where(
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
                    );
                });
            });
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
        | Role
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->where(
                'role',
                'like',
                '%' . trim($request->role) . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'assignment_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'assignment_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = clone $query;

        $summary = [
            'total' => (clone $summaryQuery)->count(),

            'planned' => (clone $summaryQuery)
                ->where('status', 'Planned')
                ->count(),

            'active' => (clone $summaryQuery)
                ->where('status', 'Active')
                ->count(),

            'released' => (clone $summaryQuery)
                ->where('status', 'Released')
                ->count(),

            'cancelled' => (clone $summaryQuery)
                ->where('status', 'Cancelled')
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $assignments = $query
            ->orderByDesc('assignment_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'construction.manpower.assignments.index',
            compact(
                'project',
                'assignments',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Only active manpower belonging to THIS project
        |--------------------------------------------------------------------------
        */

        $manpower = ConstructionManpower::query()
            ->where('project_id', $project->id)
            ->where('status', 'Active')
            ->orderBy('manpower_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Work orders belonging to THIS project
        |--------------------------------------------------------------------------
        */

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderBy('work_order_number')
            ->get();

        return view(
            'construction.manpower.assignments.create',
            compact(
                'project',
                'manpower',
                'workOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'manpower_id' => [
                'required',
                'integer',
                'exists:construction_manpower,id',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'assignment_date' => [
                'required',
                'date',
            ],

            'release_date' => [
                'nullable',
                'date',
                'after_or_equal:assignment_date',
            ],

            'role' => [
                'nullable',
                'string',
                'max:100',
            ],

            'daily_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Planned,Active,Released,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate manpower belongs to current project
        |--------------------------------------------------------------------------
        */

        $person = ConstructionManpower::query()
            ->where('id', $validated['manpower_id'])
            ->where('project_id', $project->id)
            ->where('status', 'Active')
            ->first();

        if (!$person) {

            return back()
                ->withInput()
                ->withErrors([
                    'manpower_id' =>
                        'Selected manpower does not belong to this project or is not active.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order belongs to current project
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['construction_work_order_id'])) {

            $workOrderExists = ConstructionWorkOrder::query()
                ->where(
                    'id',
                    $validated['construction_work_order_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();

            if (!$workOrderExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate Planned / Active assignment
        |--------------------------------------------------------------------------
        */

        $alreadyAssigned = ConstructionManpowerAssignment::query()
            ->where('project_id', $project->id)
            ->where('manpower_id', $validated['manpower_id'])
            ->whereIn('status', [
                'Planned',
                'Active',
            ])
            ->exists();

        if ($alreadyAssigned) {

            return back()
                ->withInput()
                ->withErrors([
                    'manpower_id' =>
                        'This manpower is already planned or actively assigned to this project.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Assignment Number
        |--------------------------------------------------------------------------
        */

        $assignmentNumber = $this->generateAssignmentNumber();


        /*
        |--------------------------------------------------------------------------
        | Create Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = DB::transaction(function () use (
            $validated,
            $project,
            $assignmentNumber
        ) {

            return ConstructionManpowerAssignment::create([

                'manpower_id' =>
                    $validated['manpower_id'],

                'project_id' =>
                    $project->id,

                'construction_work_order_id' =>
                    $validated['construction_work_order_id'] ?? null,

                'assignment_number' =>
                    $assignmentNumber,

                'assignment_date' =>
                    $validated['assignment_date'],

                'release_date' =>
                    $validated['release_date'] ?? null,

                'role' =>
                    $validated['role'] ?? null,

                'daily_rate' =>
                    $validated['daily_rate'] ?? 0,

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


        return redirect()
            ->route(
                'admin.projects.construction.manpower.assignments.show',
                [
                    'project' => $project->id,
                    'assignment' => $assignment->id,
                ]
            )
            ->with(
                'success',
                'Manpower assignment created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        /*
        |--------------------------------------------------------------------------
        | Assignment must belong to current project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Also verify manpower belongs to same project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $assignment->manpower &&
            $assignment->manpower->project_id == $project->id,
            404
        );


        $assignment->load([
            'manpower',
            'project',
            'workOrder',
            'creator',
            'updater',
            'entries' => function ($query) {

                $query
                    ->latest('entry_date')
                    ->latest('id');
            },
        ]);


        return view(
            'construction.manpower.assignments.show',
            compact(
                'project',
                'assignment'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Only Planned assignments can be edited
        |--------------------------------------------------------------------------
        */

        if ($assignment->status !== 'Planned') {

            return redirect()
                ->route(
                    'admin.projects.construction.manpower.assignments.show',
                    [
                        'project' => $project->id,
                        'assignment' => $assignment->id,
                    ]
                )
                ->with(
                    'error',
                    'Only planned assignments can be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Only active manpower from current project
        |--------------------------------------------------------------------------
        */

        $manpower = ConstructionManpower::query()
            ->where('project_id', $project->id)
            ->where('status', 'Active')
            ->orderBy('manpower_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Work orders from current project
        |--------------------------------------------------------------------------
        */

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderBy('work_order_number')
            ->get();


        return view(
            'construction.manpower.assignments.edit',
            compact(
                'project',
                'assignment',
                'manpower',
                'workOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        if ($assignment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only planned assignments can be edited.'
                );
        }


        $validated = $request->validate([

            'manpower_id' => [
                'required',
                'integer',
                'exists:construction_manpower,id',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'assignment_date' => [
                'required',
                'date',
            ],

            'release_date' => [
                'nullable',
                'date',
                'after_or_equal:assignment_date',
            ],

            'role' => [
                'nullable',
                'string',
                'max:100',
            ],

            'daily_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:Planned,Active,Released,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate manpower belongs to current project
        |--------------------------------------------------------------------------
        */

        $person = ConstructionManpower::query()
            ->where('id', $validated['manpower_id'])
            ->where('project_id', $project->id)
            ->where('status', 'Active')
            ->first();

        if (!$person) {

            return back()
                ->withInput()
                ->withErrors([
                    'manpower_id' =>
                        'Selected manpower does not belong to this project or is not active.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Work Order Project Check
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['construction_work_order_id'])) {

            $validWorkOrder = ConstructionWorkOrder::query()
                ->where(
                    'id',
                    $validated['construction_work_order_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();

            if (!$validWorkOrder) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Assignment Check
        |--------------------------------------------------------------------------
        */

        $duplicate = ConstructionManpowerAssignment::query()
            ->where('project_id', $project->id)
            ->where('manpower_id', $validated['manpower_id'])
            ->where('id', '!=', $assignment->id)
            ->whereIn('status', [
                'Planned',
                'Active',
            ])
            ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'manpower_id' =>
                        'This manpower already has another planned or active assignment in this project.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $assignment->update([

            'manpower_id' =>
                $validated['manpower_id'],

            'construction_work_order_id' =>
                $validated['construction_work_order_id'] ?? null,

            'assignment_date' =>
                $validated['assignment_date'],

            'release_date' =>
                $validated['release_date'] ?? null,

            'role' =>
                $validated['role'] ?? null,

            'daily_rate' =>
                $validated['daily_rate'] ?? 0,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.construction.manpower.assignments.show',
                [
                    'project' => $project->id,
                    'assignment' => $assignment->id,
                ]
            )
            ->with(
                'success',
                'Manpower assignment updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE
    |--------------------------------------------------------------------------
    */

    public function activate(
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        if ($assignment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only planned assignments can be activated.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Make sure manpower still belongs to this project
        |--------------------------------------------------------------------------
        */

        $person = ConstructionManpower::query()
            ->where('id', $assignment->manpower_id)
            ->where('project_id', $project->id)
            ->where('status', 'Active')
            ->first();

        if (!$person) {

            return back()
                ->with(
                    'error',
                    'The assigned manpower is no longer active or does not belong to this project.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent another active assignment
        |--------------------------------------------------------------------------
        */

        $alreadyActive = ConstructionManpowerAssignment::query()
            ->where('project_id', $project->id)
            ->where('manpower_id', $assignment->manpower_id)
            ->where('id', '!=', $assignment->id)
            ->where('status', 'Active')
            ->exists();

        if ($alreadyActive) {

            return back()
                ->with(
                    'error',
                    'This manpower already has another active assignment in this project.'
                );
        }


        $assignment->update([
            'status' => 'Active',
            'updated_by' => auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'Manpower assignment activated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RELEASE
    |--------------------------------------------------------------------------
    */

    public function release(
        Request $request,
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        if ($assignment->status !== 'Active') {

            return back()
                ->with(
                    'error',
                    'Only active assignments can be released.'
                );
        }


        $validated = $request->validate([
            'release_date' => [
                'required',
                'date',
                'after_or_equal:assignment_date',
            ],
        ]);


        $assignment->update([
            'status' => 'Released',
            'release_date' => $validated['release_date'],
            'updated_by' => auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'Manpower assignment released successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        if ($assignment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only planned assignments can be cancelled.'
                );
        }


        $assignment->update([
            'status' => 'Cancelled',
            'updated_by' => auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'Manpower assignment cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionManpowerAssignment $assignment
    ) {

        abort_unless(
            $assignment->project_id == $project->id,
            404
        );


        if ($assignment->status !== 'Planned') {

            return back()
                ->with(
                    'error',
                    'Only planned assignments can be deleted.'
                );
        }


        if ($assignment->entries()->exists()) {

            return back()
                ->with(
                    'error',
                    'This assignment cannot be deleted because manpower entries already exist.'
                );
        }


        $assignment->delete();


        return redirect()
            ->route(
                'admin.projects.construction.manpower.assignments.index',
                $project
            )
            ->with(
                'success',
                'Manpower assignment deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    private function generateAssignmentNumber(): string
    {
        $year = now()->format('Y');

        $last = ConstructionManpowerAssignment::query()
            ->where(
                'assignment_number',
                'like',
                "MA-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();

        if (!$last) {

            $sequence = 1;

        } else {

            $parts = explode(
                '-',
                $last->assignment_number
            );

            $sequence = ((int) end($parts)) + 1;
        }

        return sprintf(
            'MA-%s-%06d',
            $year,
            $sequence
        );
    }
}