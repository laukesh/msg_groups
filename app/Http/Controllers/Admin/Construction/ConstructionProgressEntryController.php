<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionProgressEntry;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionProgressEntryController
    extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $progressEntries =
            ConstructionProgressEntry::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'activity',
                    'workOrder',
                    'creator',
                ])
                ->orderByDesc(
                    'progress_date'
                )
                ->orderByDesc('id')
                ->get();


        $summary = [

            'total' =>
                $progressEntries->count(),

            'draft' =>
                $progressEntries
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'submitted' =>
                $progressEntries
                    ->where(
                        'status',
                        'Submitted'
                    )
                    ->count(),

            'approved' =>
                $progressEntries
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->count(),

            'rejected' =>
                $progressEntries
                    ->where(
                        'status',
                        'Rejected'
                    )
                    ->count(),
        ];


        return view(
            'construction.progress.index',
            compact(
                'project',
                'progressEntries',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        $activities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                    ]
                )
                ->with([
                    'workOrder',
                ])
                ->orderBy(
                    'activity_code'
                )
                ->get();


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                    ]
                )
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        return view(
            'construction.progress.create',
            compact(
                'project',
                'activities',
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
    ): RedirectResponse {

        $validated =
            $this->validateProgress(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Activity
        |--------------------------------------------------------------------------
        */

        $activity =
            ConstructionScheduleActivity::query()
                ->whereKey(
                    $validated[
                        'construction_schedule_activity_id'
                    ]
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->first();


        if (!$activity) {

            return back()
                ->withInput()
                ->withErrors([

                    'construction_schedule_activity_id' =>
                        'The selected Schedule Activity does not belong to this project.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->whereKey(
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Activity → Work Order
            |--------------------------------------------------------------------------
            */

            if (
                $activity
                    ->construction_work_order_id
                &&
                (int)
                $activity
                    ->construction_work_order_id
                !==
                (int)
                $workOrder->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not match the Schedule Activity.',

                    ]);
            }
        }


        $validated['project_id'] =
            $project->id;


        /*
        |--------------------------------------------------------------------------
        | Automatically use Activity Work Order
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
            &&
            $activity
                ->construction_work_order_id
        ) {

            $validated[
                'construction_work_order_id'
            ] =
                $activity
                    ->construction_work_order_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Progress Number
        |--------------------------------------------------------------------------
        */

        $validated['progress_number'] =
            $this->generateProgressNumber();


        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        $progress =
            DB::transaction(
                function () use (
                    $validated,
                    $activity
                ) {

                    $progress =
                        ConstructionProgressEntry::create(
                            $validated
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Schedule Activity
                    |--------------------------------------------------------------------------
                    */

                    $this->syncActivityProgress(
                        $activity
                    );


                    return $progress;
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.progress.show',
                [
                    'project' =>
                        $project,

                    'progress' =>
                        $progress,
                ]
            )
            ->with(
                'success',
                'Progress Entry '
                . $progress->progress_number
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionProgressEntry $progress
    ): View {

        $this->validateProgressProject(
            $project,
            $progress
        );


        $progress->load([
            'activity',
            'workOrder',
            'creator',
            'updater',
        ]);


        return view(
            'construction.progress.show',
            compact(
                'project',
                'progress'
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
        ConstructionProgressEntry $progress
    ): View {

        $this->validateProgressProject(
            $project,
            $progress
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft / Rejected Can Be Edited
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $progress->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.progress.show',
                    [
                        'project' =>
                            $project,

                        'progress' =>
                            $progress,
                    ]
                )
                ->with(
                    'error',
                    'Only Draft or Rejected progress entries can be edited.'
                );
        }


        $activities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                    ]
                )
                ->with([
                    'workOrder',
                ])
                ->orderBy(
                    'activity_code'
                )
                ->get();


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                    ]
                )
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        return view(
            'construction.progress.edit',
            compact(
                'project',
                'progress',
                'activities',
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
        ConstructionProgressEntry $progress
    ): RedirectResponse {

        $this->validateProgressProject(
            $project,
            $progress
        );


        if (
            !in_array(
                $progress->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Rejected progress entries can be edited.'
                );
        }


        $validated =
            $this->validateProgress(
                $request
            );


        $activity =
            ConstructionScheduleActivity::query()
                ->whereKey(
                    $validated[
                        'construction_schedule_activity_id'
                    ]
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->first();


        if (!$activity) {

            return back()
                ->withInput()
                ->withErrors([

                    'construction_schedule_activity_id' =>
                        'The selected Schedule Activity does not belong to this project.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Work Order Validation
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->whereKey(
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }


            if (
                $activity
                    ->construction_work_order_id
                &&
                (int)
                $activity
                    ->construction_work_order_id
                !==
                (int)
                $workOrder->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not match the Schedule Activity.',

                    ]);
            }
        }


        if (
            empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
            &&
            $activity
                ->construction_work_order_id
        ) {

            $validated[
                'construction_work_order_id'
            ] =
                $activity
                    ->construction_work_order_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Do Not Change System Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['progress_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        $validated['updated_by'] =
            auth()->id();


        DB::transaction(
            function () use (
                $progress,
                $validated,
                $activity
            ) {

                $progress->update(
                    $validated
                );


                $this->syncActivityProgress(
                    $activity
                );
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.progress.show',
                [
                    'project' =>
                        $project,

                    'progress' =>
                        $progress,
                ]
            )
            ->with(
                'success',
                'Progress Entry updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionProgressEntry $progress
    ): RedirectResponse {

        $this->validateProgressProject(
            $project,
            $progress
        );


        if (
            !in_array(
                $progress->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Rejected progress entries can be deleted.'
                );
        }


        $activity =
            $progress->activity;


        $number =
            $progress->progress_number;


        DB::transaction(
            function () use (
                $progress,
                $activity
            ) {

                $progress->delete();


                if ($activity) {

                    $this->syncActivityProgress(
                        $activity
                    );
                }
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.progress.index',
                $project
            )
            ->with(
                'success',
                'Progress Entry '
                . $number
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateProgress(
        Request $request
    ): array {

        return $request->validate([

            'construction_schedule_activity_id' =>
                'required|integer',

            'construction_work_order_id' =>
                'nullable|integer',

            'progress_date' =>
                'required|date',

            'planned_progress_percentage' =>
                'required|numeric|min:0|max:100',

            'actual_progress_percentage' =>
                'required|numeric|min:0|max:100',

            'quantity_planned' =>
                'nullable|numeric|min:0',

            'quantity_executed' =>
                'nullable|numeric|min:0',

            'unit' =>
                'nullable|string|max:50',

            'manpower_count' =>
                'nullable|integer|min:0',

            'status' =>
                'required|in:Draft,Submitted,Approved,Rejected',

            'remarks' =>
                'nullable|string',

            'issues_constraints' =>
                'nullable|string',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateProgressProject(
        Project $project,
        ConstructionProgressEntry $progress
    ): void {

        if (
            (int) $progress->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Progress Entry does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE PROGRESS NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateProgressNumber(): string
    {
        do {

            $number =
                'PROG-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            ConstructionProgressEntry::query()
                ->where(
                    'progress_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }


    /*
    |--------------------------------------------------------------------------
    | SYNC ACTIVITY PROGRESS
    |--------------------------------------------------------------------------
    */

    protected function syncActivityProgress(
        ConstructionScheduleActivity $activity
    ): void {

        $latest =
            ConstructionProgressEntry::query()
                ->where(
                    'construction_schedule_activity_id',
                    $activity->id
                )
                ->whereIn(
                    'status',
                    [
                        'Submitted',
                        'Approved',
                    ]
                )
                ->orderByDesc(
                    'progress_date'
                )
                ->orderByDesc('id')
                ->first();


        if (!$latest) {

            $activity->update([

                'progress_percentage' =>
                    0,

                'status' =>
                    'Not Started',

                'updated_by' =>
                    auth()->id(),

            ]);

            return;
        }


        $progress =
            (float)
            $latest->actual_progress_percentage;


        $status =
            $activity->status;


        if ($progress >= 100) {

            $status =
                'Completed';

        } elseif ($progress > 0) {

            $status =
                'In Progress';

        } else {

            $status =
                'Not Started';
        }


        $activity->update([

            'progress_percentage' =>
                $progress,

            'status' =>
                $status,

            'updated_by' =>
                auth()->id(),

        ]);
    }
}