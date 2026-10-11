<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionProgressUpdate;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionProgressController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $progressUpdates = ConstructionProgressUpdate::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder.contract.bidder',
                'reportedBy',
            ])
            ->orderByDesc('progress_date')
            ->orderByDesc('id')
            ->get();

        $latestProgress = $progressUpdates->first();

        $summary = [
            'total' => $progressUpdates->count(),

            'latest_progress' => $latestProgress
                ? (float) $latestProgress->progress_percentage
                : 0,

            'average_progress' => $progressUpdates->isNotEmpty()
                ? (float) $progressUpdates->avg('progress_percentage')
                : 0,

            'completed' => $progressUpdates
                ->where('status', 'Completed')
                ->count(),
        ];

        return view(
            'construction.progress.index',
            [
                'project' => $project,
                'progressUpdates' => $progressUpdates,
                'summary' => $summary,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->with([
                'contract.bidder',
            ])
            ->whereNotIn('status', [
                'Cancelled',
            ])
            ->orderBy('work_order_number')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.progress.create',
            [
                'project' => $project,
                'workOrders' => $workOrders,
                'users' => $users,
                'progress' => null,
            ]
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

        $validated = $this->validateProgress($request);


        /*
        |--------------------------------------------------------------------------
        | Verify Work Order
        |--------------------------------------------------------------------------
        */

        $workOrder = ConstructionWorkOrder::query()
            ->where('id', $validated['construction_work_order_id'])
            ->where('project_id', $project->id)
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
        | Project
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] = $project->id;


        /*
        |--------------------------------------------------------------------------
        | Reported By
        |--------------------------------------------------------------------------
        */

        $validated['reported_by'] =
            !empty($validated['reported_by'])
                ? $validated['reported_by']
                : auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] = auth()->id();

        $validated['updated_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Progress Number
        |--------------------------------------------------------------------------
        */

        $validated['progress_number'] =
            $this->generateProgressNumber();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $progress = DB::transaction(
            function () use ($validated) {

                return ConstructionProgressUpdate::create(
                    $validated
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.construction.progress.show',
                [
                    'project' => $project,
                    'progress' => $progress,
                ]
            )
            ->with(
                'success',
                'Progress update '
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
        ConstructionProgressUpdate $progress
    ): View {

        $this->validateProgressProject(
            $project,
            $progress
        );

        $progress->load([
            'workOrder.contract.bidder',
            'workOrder.project',
            'reportedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.progress.show',
            [
                'project' => $project,
                'progress' => $progress,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionProgressUpdate $progress
    ): View {

        $this->validateProgressProject(
            $project,
            $progress
        );

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->with([
                'contract.bidder',
            ])
            ->whereNotIn('status', [
                'Cancelled',
            ])
            ->orderBy('work_order_number')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.progress.edit',
            [
                'project' => $project,
                'progress' => $progress,
                'workOrders' => $workOrders,
                'users' => $users,
            ]
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
        ConstructionProgressUpdate $progress
    ): RedirectResponse {

        $this->validateProgressProject(
            $project,
            $progress
        );

        $validated = $this->validateProgress($request);


        /*
        |--------------------------------------------------------------------------
        | Verify New Work Order belongs to Project
        |--------------------------------------------------------------------------
        */

        $workOrder = ConstructionWorkOrder::query()
            ->whereKey(
                $validated['construction_work_order_id']
            )
            ->where('project_id', $project->id)
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
        | Never Change System Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['progress_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        $validated['updated_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $progress->update($validated);


        return redirect()
            ->route(
                'admin.projects.construction.progress.show',
                [
                    'project' => $project,
                    'progress' => $progress,
                ]
            )
            ->with(
                'success',
                'Progress update updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionProgressUpdate $progress
    ): RedirectResponse {

        $this->validateProgressProject(
            $project,
            $progress
        );

        $progressNumber =
            $progress->progress_number;

        $progress->delete();

        return redirect()
            ->route(
                'admin.projects.construction.progress.index',
                $project
            )
            ->with(
                'success',
                'Progress update '
                . $progressNumber
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

            /*
            |--------------------------------------------------------------------------
            | Work Order
            |--------------------------------------------------------------------------
            */

            'construction_work_order_id' => [
                'required',
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Progress Date
            |--------------------------------------------------------------------------
            */

            'progress_date' => [
                'required',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Progress
            |--------------------------------------------------------------------------
            */

            'progress_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Planned Progress
            |--------------------------------------------------------------------------
            */

            'planned_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Physical Progress
            |--------------------------------------------------------------------------
            */

            'physical_progress' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Financial Progress
            |--------------------------------------------------------------------------
            */

            'financial_progress' => [
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
                'in:In Progress,Delayed,On Hold,Completed',
            ],


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'work_description' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Issues
            |--------------------------------------------------------------------------
            */

            'issues' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Corrective Action
            |--------------------------------------------------------------------------
            */

            'corrective_action' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Next Action
            |--------------------------------------------------------------------------
            */

            'next_action' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Weather
            |--------------------------------------------------------------------------
            */

            'weather_condition' => [
                'nullable',
                'string',
                'max:100',
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


            /*
            |--------------------------------------------------------------------------
            | Reported By
            |--------------------------------------------------------------------------
            */

            'reported_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY PROJECT
    |--------------------------------------------------------------------------
    */

    protected function validateProgressProject(
        Project $project,
        ConstructionProgressUpdate $progress
    ): void {

        if (
            (int) $progress->project_id !==
            (int) $project->id
        ) {

            abort(
                404,
                'Progress update does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NUMBER
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
            ConstructionProgressUpdate::query()
                ->where(
                    'progress_number',
                    $number
                )
                ->exists()
        );

        return $number;
    }
}