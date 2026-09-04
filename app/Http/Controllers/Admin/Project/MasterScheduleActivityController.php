<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\MasterSchedule;
use App\Models\MasterScheduleActivity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterScheduleActivityController extends Controller
{
    /**
     * Store activity.
     */
    public function store(
        Request $request,
        Project $project,
        MasterSchedule $masterSchedule
    ) {
        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );

        $validated = $request->validate([

            'activity_code' => [
                'required',
                'string',
                'max:50',
            ],

            'activity_name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_activity_id' => [
                'nullable',
                'integer',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'activity_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_end_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_start_date',
            ],

            'baseline_start_date' => [
                'nullable',
                'date',
            ],

            'baseline_end_date' => [
                'nullable',
                'date',
                'after_or_equal:baseline_start_date',
            ],

            'actual_start_date' => [
                'nullable',
                'date',
            ],

            'actual_end_date' => [
                'nullable',
                'date',
                'after_or_equal:actual_start_date',
            ],

            'planned_duration_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'actual_duration_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'planned_progress' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'actual_progress' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'predecessor_activity_id' => [
                'nullable',
                'integer',
            ],

            'dependency_type' => [
                'nullable',
                'string',
                'max:20',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'is_milestone' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Parent Activity
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['parent_activity_id'])) {

            $parentActivity =
                MasterScheduleActivity::where(
                    'id',
                    $validated['parent_activity_id']
                )
                ->where(
                    'master_schedule_id',
                    $masterSchedule->id
                )
                ->first();

            if (!$parentActivity) {

                return back()
                    ->withErrors([
                        'parent_activity_id' =>
                            'Selected parent activity does not belong to this Master Schedule.'
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Predecessor
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['predecessor_activity_id'])) {

            $predecessor =
                MasterScheduleActivity::where(
                    'id',
                    $validated['predecessor_activity_id']
                )
                ->where(
                    'master_schedule_id',
                    $masterSchedule->id
                )
                ->first();

            if (!$predecessor) {

                return back()
                    ->withErrors([
                        'predecessor_activity_id' =>
                            'Selected predecessor activity does not belong to this Master Schedule.'
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Self Dependency
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['predecessor_activity_id']) &&
            isset($validated['id']) &&
            (int) $validated['predecessor_activity_id'] ===
            (int) $validated['id']
        ) {
            return back()
                ->withErrors([
                    'predecessor_activity_id' =>
                        'An activity cannot be its own predecessor.'
                ])
                ->withInput();
        }


        $activity = DB::transaction(
            function () use (
                $validated,
                $masterSchedule
            ) {

                $data = $validated;

                $data['master_schedule_id'] =
                    $masterSchedule->id;

                $data['sequence'] =
                    $data['sequence'] ?? 0;

                $data['planned_progress'] =
                    $data['planned_progress'] ?? 0;

                $data['actual_progress'] =
                    $data['actual_progress'] ?? 0;

                $data['is_milestone'] =
                    $data['is_milestone'] ?? false;

                $data['created_by'] =
                    auth()->id();

                return MasterScheduleActivity::create(
                    $data
                );
            }
        );


        return redirect()->route(
            'admin.projects.master-schedule.show',
            [
                'project' =>
                    $project->id,

                'masterSchedule' =>
                    $masterSchedule->id,
            ]
        )->with(
            'success',
            'Schedule activity added successfully.'
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        MasterSchedule $masterSchedule,
        MasterScheduleActivity $activity
    ) {
        $this->validateActivity(
            $project,
            $masterSchedule,
            $activity
        );

        $activities =
            $masterSchedule
                ->activities()
                ->where(
                    'id',
                    '!=',
                    $activity->id
                )
                ->orderBy('sequence')
                ->get();

        return view(
            'projects.master-schedule.activities.edit',
            compact(
                'project',
                'masterSchedule',
                'activity',
                'activities'
            )
        );
    }


    /**
     * Update activity.
     */
    public function update(
        Request $request,
        Project $project,
        MasterSchedule $masterSchedule,
        MasterScheduleActivity $activity
    ) {
        $this->validateActivity(
            $project,
            $masterSchedule,
            $activity
        );

        $validated = $request->validate([

            'activity_code' => [
                'required',
                'string',
                'max:50',
            ],

            'activity_name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_activity_id' => [
                'nullable',
                'integer',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'activity_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_end_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_start_date',
            ],

            'baseline_start_date' => [
                'nullable',
                'date',
            ],

            'baseline_end_date' => [
                'nullable',
                'date',
                'after_or_equal:baseline_start_date',
            ],

            'actual_start_date' => [
                'nullable',
                'date',
            ],

            'actual_end_date' => [
                'nullable',
                'date',
                'after_or_equal:actual_start_date',
            ],

            'planned_duration_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'actual_duration_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'planned_progress' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'actual_progress' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'predecessor_activity_id' => [
                'nullable',
                'integer',
            ],

            'dependency_type' => [
                'nullable',
                'string',
                'max:20',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'is_milestone' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Parent Activity Validation
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['parent_activity_id'])) {

            if (
                (int) $validated['parent_activity_id'] ===
                (int) $activity->id
            ) {
                return back()
                    ->withErrors([
                        'parent_activity_id' =>
                            'An activity cannot be its own parent.'
                    ])
                    ->withInput();
            }


            $parentExists =
                $masterSchedule
                    ->activities()
                    ->where(
                        'id',
                        $validated['parent_activity_id']
                    )
                    ->exists();

            if (!$parentExists) {

                return back()
                    ->withErrors([
                        'parent_activity_id' =>
                            'Selected parent activity does not belong to this Master Schedule.'
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Predecessor Validation
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['predecessor_activity_id'])) {

            if (
                (int) $validated['predecessor_activity_id'] ===
                (int) $activity->id
            ) {
                return back()
                    ->withErrors([
                        'predecessor_activity_id' =>
                            'An activity cannot be its own predecessor.'
                    ])
                    ->withInput();
            }


            $predecessorExists =
                $masterSchedule
                    ->activities()
                    ->where(
                        'id',
                        $validated['predecessor_activity_id']
                    )
                    ->exists();

            if (!$predecessorExists) {

                return back()
                    ->withErrors([
                        'predecessor_activity_id' =>
                            'Selected predecessor activity does not belong to this Master Schedule.'
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Immutable Relationship
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['master_schedule_id']
        );


        $validated['updated_by'] =
            auth()->id();


        $activity->update(
            $validated
        );


        return redirect()->route(
            'admin.projects.master-schedule.show',
            [
                'project' =>
                    $project->id,

                'masterSchedule' =>
                    $masterSchedule->id,
            ]
        )->with(
            'success',
            'Schedule activity updated successfully.'
        );
    }


    /**
     * Delete activity.
     */
    public function destroy(
        Project $project,
        MasterSchedule $masterSchedule,
        MasterScheduleActivity $activity
    ) {
        $this->validateActivity(
            $project,
            $masterSchedule,
            $activity
        );


        /*
        |--------------------------------------------------------------------------
        | Do not delete activity having children
        |--------------------------------------------------------------------------
        */

        if ($activity->children()->exists()) {

            return back()->with(
                'error',
                'This activity cannot be deleted because it has child activities.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Do not delete activity used as predecessor
        |--------------------------------------------------------------------------
        */

        if ($activity->successors()->exists()) {

            return back()->with(
                'error',
                'This activity cannot be deleted because other activities depend on it.'
            );
        }


        $activity->delete();


        return redirect()->route(
            'admin.projects.master-schedule.show',
            [
                'project' =>
                    $project->id,

                'masterSchedule' =>
                    $masterSchedule->id,
            ]
        )->with(
            'success',
            'Schedule activity deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Master Schedule
    |--------------------------------------------------------------------------
    */

    private function validateMasterSchedule(
        Project $project,
        MasterSchedule $masterSchedule
    ): void {

        abort_unless(
            (int) $masterSchedule->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Activity Ownership
    |--------------------------------------------------------------------------
    */

    private function validateActivity(
        Project $project,
        MasterSchedule $masterSchedule,
        MasterScheduleActivity $activity
    ): void {

        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );


        abort_unless(
            (int) $activity->master_schedule_id ===
            (int) $masterSchedule->id,
            404
        );
    }
}