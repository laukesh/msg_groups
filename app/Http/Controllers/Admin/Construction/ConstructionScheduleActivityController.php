<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionScheduleActivityController
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

        $activities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                    'responsibleUser',
                    'predecessor',
                ])
                ->orderBy(
                    'planned_start_date'
                )
                ->orderBy('id')
                ->get();


        $summary = [

            'total' =>
                $activities->count(),

            'not_started' =>
                $activities
                    ->where(
                        'status',
                        'Not Started'
                    )
                    ->count(),

            'in_progress' =>
                $activities
                    ->where(
                        'status',
                        'In Progress'
                    )
                    ->count(),

            'completed' =>
                $activities
                    ->where(
                        'status',
                        'Completed'
                    )
                    ->count(),

            'delayed' =>
                $activities
                    ->filter(
                        fn ($activity) =>
                            $activity->isDelayed()
                    )
                    ->count(),
        ];


        return view(
            'construction.schedule.index',
            compact(
                'project',
                'activities',
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


        $activities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'activity_code'
                )
                ->get();


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.schedule.create',
            compact(
                'project',
                'workOrders',
                'activities',
                'users'
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
            $this->validateActivity(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $exists =
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
                    ->exists();


            if (!$exists) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Predecessor
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'predecessor_activity_id'
                ]
            )
        ) {

            $exists =
                ConstructionScheduleActivity::query()
                    ->whereKey(
                        $validated[
                            'predecessor_activity_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();


            if (!$exists) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'predecessor_activity_id' =>
                            'The selected predecessor does not belong to this project.',

                    ]);
            }
        }


        $validated['project_id'] =
            $project->id;


        $validated['activity_code'] =
            $this->generateActivityCode();


        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Calculate Duration
        |--------------------------------------------------------------------------
        */

        $validated =
            $this->calculateDuration(
                $validated
            );


        $activity =
            DB::transaction(
                function () use (
                    $validated
                ) {

                    return
                        ConstructionScheduleActivity::create(
                            $validated
                        );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.schedule.show',
                [
                    'project' =>
                        $project,

                    'activity' =>
                        $activity,
                ]
            )
            ->with(
                'success',
                'Schedule Activity '
                . $activity->activity_code
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
        ConstructionScheduleActivity $activity
    ): View {

        $this->validateActivityProject(
            $project,
            $activity
        );


        $activity->load([
            'workOrder',
            'responsibleUser',
            'predecessor',
            'successors',
            'creator',
            'updater',
        ]);


        return view(
            'construction.schedule.show',
            compact(
                'project',
                'activity'
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
        ConstructionScheduleActivity $activity
    ): View {

        $this->validateActivityProject(
            $project,
            $activity
        );


        if (!$activity->canEdit()) {

            return redirect()
                ->route(
                    'admin.projects.construction.schedule.show',
                    [
                        'project' =>
                            $project,

                        'activity' =>
                            $activity,
                    ]
                )
                ->with(
                    'error',
                    'This Schedule Activity cannot be edited.'
                );
        }


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


        /*
        |--------------------------------------------------------------------------
        | Exclude Current Activity From Predecessors
        |--------------------------------------------------------------------------
        */

        $activities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'id',
                    '!=',
                    $activity->id
                )
                ->orderBy(
                    'activity_code'
                )
                ->get();


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.schedule.edit',
            compact(
                'project',
                'activity',
                'workOrders',
                'activities',
                'users'
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
        ConstructionScheduleActivity $activity
    ): RedirectResponse {

        $this->validateActivityProject(
            $project,
            $activity
        );


        if (!$activity->canEdit()) {

            return back()
                ->with(
                    'error',
                    'This Schedule Activity cannot be edited.'
                );
        }


        $validated =
            $this->validateActivity(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $exists =
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
                    ->exists();


            if (!$exists) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Predecessor
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'predecessor_activity_id'
                ]
            )
        ) {

            if (
                (int)
                $validated[
                    'predecessor_activity_id'
                ]
                ===
                (int) $activity->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'predecessor_activity_id' =>
                            'An activity cannot be its own predecessor.',

                    ]);
            }


            $exists =
                ConstructionScheduleActivity::query()
                    ->whereKey(
                        $validated[
                            'predecessor_activity_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();


            if (!$exists) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'predecessor_activity_id' =>
                            'The selected predecessor does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Never Change System Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['activity_code'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        $validated =
            $this->calculateDuration(
                $validated
            );


        $validated['updated_by'] =
            auth()->id();


        $activity->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.schedule.show',
                [
                    'project' =>
                        $project,

                    'activity' =>
                        $activity,
                ]
            )
            ->with(
                'success',
                'Schedule Activity updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionScheduleActivity $activity
    ): RedirectResponse {

        $this->validateActivityProject(
            $project,
            $activity
        );


        /*
        |--------------------------------------------------------------------------
        | Do Not Delete If Other Activities Depend On It
        |--------------------------------------------------------------------------
        */

        if (
            $activity
                ->successors()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This activity cannot be deleted because other activities depend on it.'
                );
        }


        $code =
            $activity->activity_code;


        $activity->delete();


        return redirect()
            ->route(
                'admin.projects.construction.schedule.index',
                $project
            )
            ->with(
                'success',
                'Schedule Activity '
                . $code
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateActivity(
        Request $request
    ): array {

        return $request->validate([

            'construction_work_order_id' =>
                'nullable|integer',

            'activity_name' =>
                'required|string|max:255',

            'wbs_code' =>
                'nullable|string|max:100',

            'phase' =>
                'nullable|string|max:150',

            'description' =>
                'nullable|string',

            'planned_start_date' =>
                'nullable|date',

            'planned_finish_date' =>
                'nullable|date|after_or_equal:planned_start_date',

            'actual_start_date' =>
                'nullable|date',

            'actual_finish_date' =>
                'nullable|date|after_or_equal:actual_start_date',

            'progress_percentage' =>
                'required|numeric|min:0|max:100',

            'predecessor_activity_id' =>
                'nullable|integer',

            'responsible_user_id' =>
                'nullable|integer|exists:users,id',

            'priority' =>
                'required|in:Low,Normal,High,Critical',

            'status' =>
                'required|in:Not Started,In Progress,Completed,On Hold,Delayed,Cancelled',

            'delay_days' =>
                'nullable|integer|min:0',

            'remarks' =>
                'nullable|string',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateActivityProject(
        Project $project,
        ConstructionScheduleActivity $activity
    ): void {

        if (
            (int) $activity->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Schedule Activity does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE DURATION
    |--------------------------------------------------------------------------
    */

    protected function calculateDuration(
        array $data
    ): array {

        if (
            !empty(
                $data['planned_start_date']
            )
            &&
            !empty(
                $data['planned_finish_date']
            )
        ) {

            $start =
                \Carbon\Carbon::parse(
                    $data['planned_start_date']
                );

            $finish =
                \Carbon\Carbon::parse(
                    $data['planned_finish_date']
                );


            $data['duration_days'] =
                $start->diffInDays(
                    $finish
                ) + 1;
        }


        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE ACTIVITY CODE
    |--------------------------------------------------------------------------
    */

    protected function generateActivityCode(): string
    {
        do {

            $code =
                'ACT-' .
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
            ConstructionScheduleActivity::query()
                ->where(
                    'activity_code',
                    $code
                )
                ->exists()
        );


        return $code;
    }
}