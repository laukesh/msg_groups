<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\MasterSchedule;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterScheduleController extends Controller
{
    /**
     * Display the master schedule.
     */
    public function index(Project $project)
    {
        $project->load([
            'masterSchedule.activities' => function ($query) {
                $query->orderBy('sequence');
            },
        ]);

        $masterSchedule = $project->masterSchedule;

        return view(
            'projects.master-schedule.index',
            compact(
                'project',
                'masterSchedule'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Project $project)
    {
        $project->load('masterSchedule');

        /*
        |--------------------------------------------------------------------------
        | One Master Schedule per Project
        |--------------------------------------------------------------------------
        */

        if ($project->masterSchedule) {

            return redirect()->route(
                'admin.projects.master-schedule.show',
                [
                    'project' =>
                        $project->id,

                    'masterSchedule' =>
                        $project->masterSchedule->id,
                ]
            )->with(
                'info',
                'Master Schedule already exists for this project.'
            );
        }


        return view(
            'projects.master-schedule.create',
            compact('project')
        );
    }


    /**
     * Store master schedule.
     */
    public function store(
        Request $request,
        Project $project
    ) {
        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate schedule
        |--------------------------------------------------------------------------
        */

        if ($project->masterSchedule) {

            return redirect()->route(
                'admin.projects.master-schedule.show',
                [
                    'project' =>
                        $project->id,

                    'masterSchedule' =>
                        $project->masterSchedule->id,
                ]
            )->with(
                'error',
                'Master Schedule already exists for this project.'
            );
        }


        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Baseline Dates
            |--------------------------------------------------------------------------
            */

            'baseline_start_date' => [
                'nullable',
                'date',
            ],

            'baseline_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:baseline_start_date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Current Dates
            |--------------------------------------------------------------------------
            */

            'current_start_date' => [
                'nullable',
                'date',
            ],

            'current_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:current_start_date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Progress
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Baseline / Approval
            |--------------------------------------------------------------------------
            */

            'baseline_date' => [
                'nullable',
                'date',
            ],

            'approved_date' => [
                'nullable',
                'date',
            ],

            'approved_by' => [
                'nullable',
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $masterSchedule = DB::transaction(
            function () use (
                $validated,
                $project
            ) {

                $data = $validated;


                /*
                |--------------------------------------------------------------------------
                | Project Relationship
                |--------------------------------------------------------------------------
                */

                $data['project_id'] =
                    $project->id;


                /*
                |--------------------------------------------------------------------------
                | Defaults
                |--------------------------------------------------------------------------
                */

                $data['planned_progress'] =
                    $data['planned_progress'] ?? 0;

                $data['actual_progress'] =
                    $data['actual_progress'] ?? 0;


                /*
                |--------------------------------------------------------------------------
                | Schedule Number
                |--------------------------------------------------------------------------
                */

                $data['schedule_number'] =
                    'MS-' .
                    now()->format('YmdHis') .
                    '-' .
                    Str::upper(
                        Str::random(4)
                    );


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $data['created_by'] =
                    auth()->id();


                return MasterSchedule::create(
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
            'Master Schedule created successfully.'
        );
    }


    /**
     * Display master schedule.
     */
    public function show(
        Project $project,
        MasterSchedule $masterSchedule
    ) {
        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );


        $masterSchedule->load([
            'activities' => function ($query) {
                $query->orderBy('sequence');
            },
        ]);


        return view(
            'projects.master-schedule.show',
            compact(
                'project',
                'masterSchedule'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        MasterSchedule $masterSchedule
    ) {
        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );


        return view(
            'projects.master-schedule.edit',
            compact(
                'project',
                'masterSchedule'
            )
        );
    }


    /**
     * Update master schedule.
     */
    public function update(
        Request $request,
        Project $project,
        MasterSchedule $masterSchedule
    ) {
        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'baseline_start_date' => [
                'nullable',
                'date',
            ],

            'baseline_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:baseline_start_date',
            ],

            'current_start_date' => [
                'nullable',
                'date',
            ],

            'current_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:current_start_date',
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

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'baseline_date' => [
                'nullable',
                'date',
            ],

            'approved_date' => [
                'nullable',
                'date',
            ],

            'approved_by' => [
                'nullable',
                'integer',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Immutable Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['project_id'],
            $validated['schedule_number']
        );


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        $masterSchedule->update(
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
            'Master Schedule updated successfully.'
        );
    }


    /**
     * Delete master schedule.
     */
    public function destroy(
        Project $project,
        MasterSchedule $masterSchedule
    ) {
        $this->validateMasterSchedule(
            $project,
            $masterSchedule
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft schedules can be deleted
        |--------------------------------------------------------------------------
        */

        if ($masterSchedule->status !== 'Draft') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Only Draft Master Schedules can be deleted.'
                );
        }


        $masterSchedule->delete();


        return redirect()->route(
            'admin.projects.master-schedule.index',
            [
                'project' =>
                    $project->id,
            ]
        )->with(
            'success',
            'Master Schedule deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Project Ownership
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
}