<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionManpower;
use App\Models\ConstructionManpowerAssignment;
use App\Models\ConstructionManpowerEntry;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ConstructionManpowerEntryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, Project $project)
    {
        $query = ConstructionManpowerEntry::where(
            'project_id',
            $project->id
        )
        ->with([
            'manpower',
            'assignment',
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
                    'entry_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('manpower', function ($q) use ($search) {

                    $q->where(
                        'manpower_code',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'manpower_name',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('attendance_status')) {

            $query->where(
                'attendance_status',
                $request->attendance_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'entry_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'entry_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Manpower Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('manpower_id')) {

            $query->where(
                'manpower_id',
                $request->manpower_id
            );
        }

        $entries = $query
            ->latest('entry_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionManpowerEntry::where(
            'project_id',
            $project->id
        );

        $summary = [
            'total_entries' => (clone $summaryQuery)->count(),

            'present' => (clone $summaryQuery)
                ->where('attendance_status', 'Present')
                ->count(),

            'absent' => (clone $summaryQuery)
                ->where('attendance_status', 'Absent')
                ->count(),

            'half_day' => (clone $summaryQuery)
                ->where('attendance_status', 'Half Day')
                ->count(),

            'leave' => (clone $summaryQuery)
                ->where('attendance_status', 'Leave')
                ->count(),

            'total_hours' => (clone $summaryQuery)
                ->sum('total_hours'),

            'total_cost' => (clone $summaryQuery)
                ->sum('total_cost'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Manpower List
        |--------------------------------------------------------------------------
        */

        $manpower = ConstructionManpower::where(
            'project_id',
            $project->id
        )
        ->where('status', 'Active')
        ->orderBy('manpower_name')
        ->get();

        return view(
            'construction.manpower.entries.index',
            compact(
                'project',
                'entries',
                'summary',
                'manpower'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        Project $project
    ) {

        /*
        |--------------------------------------------------------------------------
        | Only active assignments can receive daily entries
        |--------------------------------------------------------------------------
        */

        $assignments = ConstructionManpowerAssignment::where(
            'project_id',
            $project->id
        )
        ->where('status', 'Active')
        ->with([
            'manpower',
            'workOrder',
        ])
        ->orderByDesc('assignment_date')
        ->get();

        $selectedAssignment = null;

        if ($request->filled('assignment_id')) {

            $selectedAssignment = $assignments->firstWhere(
                'id',
                (int) $request->assignment_id
            );
        }

        $workOrders = ConstructionWorkOrder::where(
            'project_id',
            $project->id
        )
        ->orderByDesc('id')
        ->get();

        return view(
            'construction.manpower.entries.create',
            compact(
                'project',
                'assignments',
                'selectedAssignment',
                'workOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ) {

        $validated = $request->validate([

            'manpower_assignment_id' => [
                'required',
                'integer',
                'exists:construction_manpower_assignments,id',
            ],

            'entry_date' => [
                'required',
                'date',
            ],

            'attendance_status' => [
                'required',
                'in:Present,Absent,Half Day,Leave',
            ],

            'regular_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:24',
            ],

            'overtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:24',
            ],

            'overtime_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'work_description' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = ConstructionManpowerAssignment::with([
            'manpower',
            'workOrder',
        ])
        ->where('id', $validated['manpower_assignment_id'])
        ->where('project_id', $project->id)
        ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Assignment must be Active
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $assignment->status === 'Active',
            422,
            'Daily manpower entry can only be created for an active assignment.'
        );

        /*
        |--------------------------------------------------------------------------
        | Verify Manpower belongs to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $assignment->manpower &&
            (int) $assignment->manpower->project_id === (int) $project->id,
            422,
            'Selected manpower does not belong to this project.'
        );

        /*
        |--------------------------------------------------------------------------
        | Work Order Project Validation
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['construction_work_order_id'])) {

            $workOrder = ConstructionWorkOrder::where(
                'id',
                $validated['construction_work_order_id']
            )
            ->where(
                'project_id',
                $project->id
            )
            ->first();

            abort_unless(
                $workOrder,
                422,
                'Selected work order does not belong to this project.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Daily Entry
        |--------------------------------------------------------------------------
        */

        $duplicate = ConstructionManpowerEntry::where(
            'manpower_assignment_id',
            $assignment->id
        )
        ->where(
            'entry_date',
            $validated['entry_date']
        )
        ->whereNull('deleted_at')
        ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'entry_date' =>
                        'A daily manpower entry already exists for this assignment on the selected date.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Hours
        |--------------------------------------------------------------------------
        */

        $regularHours = (float) (
            $validated['regular_hours'] ?? 0
        );

        $overtimeHours = (float) (
            $validated['overtime_hours'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Attendance Based Hours
        |--------------------------------------------------------------------------
        */

        if ($validated['attendance_status'] === 'Absent') {

            $regularHours = 0;
            $overtimeHours = 0;
        }

        if ($validated['attendance_status'] === 'Leave') {

            $regularHours = 0;
            $overtimeHours = 0;
        }

        if (
            $validated['attendance_status'] === 'Half Day' &&
            $regularHours <= 0
        ) {

            $regularHours = 4;
        }

        /*
        |--------------------------------------------------------------------------
        | Rates
        |--------------------------------------------------------------------------
        */

        $dailyRate = (float) $assignment->daily_rate;

        $overtimeRate = (float) (
            $validated['overtime_rate'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Cost Calculation
        |--------------------------------------------------------------------------
        */

        $totalHours = $regularHours + $overtimeHours;

        $regularCost = 0;

        if ($regularHours > 0) {

            $regularCost =
                $dailyRate * ($regularHours / 8);
        }

        $overtimeCost =
            $overtimeHours * $overtimeRate;

        $totalCost =
            $regularCost + $overtimeCost;

        /*
        |--------------------------------------------------------------------------
        | Entry Number
        |--------------------------------------------------------------------------
        */

        $entryNumber = $this->generateEntryNumber();

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $project,
            $assignment,
            $validated,
            $entryNumber,
            $regularHours,
            $overtimeHours,
            $totalHours,
            $dailyRate,
            $overtimeRate,
            $totalCost
        ) {

            ConstructionManpowerEntry::create([

                'manpower_id' =>
                    $assignment->manpower_id,

                'project_id' =>
                    $project->id,

                'construction_work_order_id' =>
                    $validated['construction_work_order_id']
                    ?? $assignment->construction_work_order_id,

                'manpower_assignment_id' =>
                    $assignment->id,

                'entry_number' =>
                    $entryNumber,

                'entry_date' =>
                    $validated['entry_date'],

                'attendance_status' =>
                    $validated['attendance_status'],

                'regular_hours' =>
                    $regularHours,

                'overtime_hours' =>
                    $overtimeHours,

                'total_hours' =>
                    $totalHours,

                'daily_rate' =>
                    $dailyRate,

                'overtime_rate' =>
                    $overtimeRate,

                'total_cost' =>
                    $totalCost,

                'work_description' =>
                    $validated['work_description']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);
        });

        return redirect()
            ->route(
                'admin.projects.construction.manpower.entries.index',
                $project
            )
            ->with(
                'success',
                'Daily manpower entry created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionManpowerEntry $entry
    ) {

        abort_unless(
            (int) $entry->project_id === (int) $project->id,
            404
        );

        $entry->load([
            'manpower',
            'project',
            'assignment',
            'workOrder',
            'creator',
            'updater',
        ]);

        return view(
            'construction.manpower.entries.show',
            compact(
                'project',
                'entry'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionManpowerEntry $entry
    ) {

        abort_unless(
            (int) $entry->project_id === (int) $project->id,
            404
        );

        $assignments = ConstructionManpowerAssignment::where(
            'project_id',
            $project->id
        )
        ->whereIn('status', [
            'Active',
        ])
        ->with([
            'manpower',
            'workOrder',
        ])
        ->orderByDesc('assignment_date')
        ->get();

        $workOrders = ConstructionWorkOrder::where(
            'project_id',
            $project->id
        )
        ->orderByDesc('id')
        ->get();

        $entry->load([
            'assignment',
            'manpower',
        ]);

        return view(
            'construction.manpower.entries.edit',
            compact(
                'project',
                'entry',
                'assignments',
                'workOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionManpowerEntry $entry
    ) {

        abort_unless(
            (int) $entry->project_id === (int) $project->id,
            404
        );

        $validated = $request->validate([

            'manpower_assignment_id' => [
                'required',
                'integer',
                'exists:construction_manpower_assignments,id',
            ],

            'entry_date' => [
                'required',
                'date',
            ],

            'attendance_status' => [
                'required',
                'in:Present,Absent,Half Day,Leave',
            ],

            'regular_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:24',
            ],

            'overtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:24',
            ],

            'overtime_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'work_description' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        $assignment = ConstructionManpowerAssignment::with(
            'manpower'
        )
        ->where('id', $validated['manpower_assignment_id'])
        ->where('project_id', $project->id)
        ->firstOrFail();

        abort_unless(
            $assignment->status === 'Active',
            422,
            'Daily manpower entry can only be linked to an active assignment.'
        );

        abort_unless(
            $assignment->manpower &&
            (int) $assignment->manpower->project_id === (int) $project->id,
            422,
            'Selected manpower does not belong to this project.'
        );

        /*
        |--------------------------------------------------------------------------
        | Duplicate Check
        |--------------------------------------------------------------------------
        */

        $duplicate = ConstructionManpowerEntry::where(
            'manpower_assignment_id',
            $assignment->id
        )
        ->where(
            'entry_date',
            $validated['entry_date']
        )
        ->where(
            'id',
            '!=',
            $entry->id
        )
        ->whereNull('deleted_at')
        ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'entry_date' =>
                        'A daily manpower entry already exists for this assignment on the selected date.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Work Order Validation
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['construction_work_order_id'])) {

            $workOrder = ConstructionWorkOrder::where(
                'id',
                $validated['construction_work_order_id']
            )
            ->where(
                'project_id',
                $project->id
            )
            ->first();

            abort_unless(
                $workOrder,
                422,
                'Selected work order does not belong to this project.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate
        |--------------------------------------------------------------------------
        */

        $regularHours = (float) (
            $validated['regular_hours'] ?? 0
        );

        $overtimeHours = (float) (
            $validated['overtime_hours'] ?? 0
        );

        if (
            $validated['attendance_status'] === 'Absent' ||
            $validated['attendance_status'] === 'Leave'
        ) {

            $regularHours = 0;
            $overtimeHours = 0;
        }

        if (
            $validated['attendance_status'] === 'Half Day' &&
            $regularHours <= 0
        ) {

            $regularHours = 4;
        }

        $dailyRate = (float) $assignment->daily_rate;

        $overtimeRate = (float) (
            $validated['overtime_rate'] ?? 0
        );

        $totalHours =
            $regularHours + $overtimeHours;

        $regularCost =
            $dailyRate * ($regularHours / 8);

        $overtimeCost =
            $overtimeHours * $overtimeRate;

        $totalCost =
            $regularCost + $overtimeCost;

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $entry->update([

            'manpower_id' =>
                $assignment->manpower_id,

            'project_id' =>
                $project->id,

            'construction_work_order_id' =>
                $validated['construction_work_order_id']
                ?? $assignment->construction_work_order_id,

            'manpower_assignment_id' =>
                $assignment->id,

            'entry_date' =>
                $validated['entry_date'],

            'attendance_status' =>
                $validated['attendance_status'],

            'regular_hours' =>
                $regularHours,

            'overtime_hours' =>
                $overtimeHours,

            'total_hours' =>
                $totalHours,

            'daily_rate' =>
                $dailyRate,

            'overtime_rate' =>
                $overtimeRate,

            'total_cost' =>
                $totalCost,

            'work_description' =>
                $validated['work_description']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.manpower.entries.show',
                [
                    'project' => $project,
                    'entry' => $entry,
                ]
            )
            ->with(
                'success',
                'Daily manpower entry updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionManpowerEntry $entry
    ) {

        abort_unless(
            (int) $entry->project_id === (int) $project->id,
            404
        );

        $entry->delete();

        return redirect()
            ->route(
                'admin.projects.construction.manpower.entries.index',
                $project
            )
            ->with(
                'success',
                'Daily manpower entry deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Entry Number
    |--------------------------------------------------------------------------
    */

    private function generateEntryNumber(): string
    {
        $year = now()->format('Y');

        $lastEntry = ConstructionManpowerEntry::withTrashed()
            ->where(
                'entry_number',
                'like',
                "ME-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastEntry) {

            $lastNumber = (int) substr(
                $lastEntry->entry_number,
                -6
            );

            $nextNumber = $lastNumber + 1;
        }

        return sprintf(
            'ME-%s-%06d',
            $year,
            $nextNumber
        );
    }
}