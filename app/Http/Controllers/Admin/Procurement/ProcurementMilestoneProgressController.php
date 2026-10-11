<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementMilestoneProgress;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementMilestoneProgressController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );


        $progressUpdates = ProcurementMilestoneProgress::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->where(
                'procurement_contract_milestone_id',
                $milestone->id
            )
            ->latest('progress_date')
            ->latest('id')
            ->get();


        return view(
            'procurement.milestone-progress.index',
            compact(
                'project',
                'procurementTender',
                'contract',
                'milestone',
                'progressUpdates'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );


        if ($contract->status !== 'Active') {

            abort(
                403,
                'Progress can only be recorded for an Active Contract.'
            );
        }


        if ($milestone->status === 'Completed') {

            abort(
                403,
                'Completed milestones cannot receive new progress.'
            );
        }


        return view(
            'procurement.milestone-progress.create',
            compact(
                'project',
                'procurementTender',
                'contract',
                'milestone'
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );


        /*
        |--------------------------------------------------------------------------
        | Contract Status
        |--------------------------------------------------------------------------
        */

        if ($contract->status !== 'Active') {

            return back()
                ->withInput()
                ->withErrors([
                    'progress' =>
                        'Progress can only be recorded for an Active Contract.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Milestone Status
        |--------------------------------------------------------------------------
        */

        if ($milestone->status === 'Completed') {

            return back()
                ->withInput()
                ->withErrors([
                    'progress' =>
                        'Completed milestones cannot receive new progress.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'progress_date' => [
                'required',
                'date',
            ],

            'progress_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'progress_description' => [
                'nullable',
                'string',
            ],

            'work_completed' => [
                'nullable',
                'string',
            ],

            'work_pending' => [
                'nullable',
                'string',
            ],

            'issues' => [
                'nullable',
                'string',
            ],

            'corrective_action' => [
                'nullable',
                'string',
            ],
        ]);


        $newProgress =
            (float) $validated['progress_percentage'];


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        |
        | Lock the milestone so two users cannot submit conflicting
        | progress updates at the same time.
        |
        */

        DB::beginTransaction();

        try {

            $lockedMilestone =
                ProcurementContractMilestone::query()
                    ->whereKey($milestone->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Re-check hierarchy after locking
            |--------------------------------------------------------------------------
            */

            abort_unless(
                (int) $lockedMilestone->procurement_contract_id ===
                    (int) $contract->id,
                404
            );


            /*
            |--------------------------------------------------------------------------
            | Current Progress
            |--------------------------------------------------------------------------
            */

            $currentProgress =
                (float) $lockedMilestone->progress_percentage;


            /*
            |--------------------------------------------------------------------------
            | Prevent Progress Decrease
            |--------------------------------------------------------------------------
            */

            if ($newProgress < $currentProgress) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'progress_percentage' =>
                            "Progress cannot decrease. Current progress is {$currentProgress}%.",
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Progress Record
            |--------------------------------------------------------------------------
            */

            ProcurementMilestoneProgress::create([

                'project_id' =>
                    $project->id,

                'procurement_contract_id' =>
                    $contract->id,

                'procurement_contract_milestone_id' =>
                    $lockedMilestone->id,

                'progress_date' =>
                    $validated['progress_date'],

                'progress_percentage' =>
                    $newProgress,

                'previous_progress_percentage' =>
                    $currentProgress,

                'progress_description' =>
                    $validated['progress_description']
                    ?? null,

                'work_completed' =>
                    $validated['work_completed']
                    ?? null,

                'work_pending' =>
                    $validated['work_pending']
                    ?? null,

                'issues' =>
                    $validated['issues']
                    ?? null,

                'corrective_action' =>
                    $validated['corrective_action']
                    ?? null,

                'status' =>
                    'Submitted',

                'submitted_by' =>
                    auth()->id(),

                'submitted_at' =>
                    now(),

                'created_by' =>
                    auth()->id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Milestone
            |--------------------------------------------------------------------------
            */

            $lockedMilestone->update([

                'progress_percentage' =>
                    $newProgress,

                'status' =>
                    $newProgress >= 100
                        ? 'Completed'
                        : 'In Progress',

                'actual_start_date' =>
                    $lockedMilestone->actual_start_date
                    ?? $validated['progress_date'],

                'actual_end_date' =>
                    $newProgress >= 100
                        ? $validated['progress_date']
                        : $lockedMilestone->actual_end_date,

                'completed_by' =>
                    $newProgress >= 100
                        ? auth()->id()
                        : $lockedMilestone->completed_by,

                'completed_at' =>
                    $newProgress >= 100
                        ? now()
                        : $lockedMilestone->completed_at,

                'updated_by' =>
                    auth()->id(),
            ]);


            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.contracts.milestones.progress.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'milestone' =>
                        $milestone,
                ]
            )
            ->with(
                'success',
                'Progress update recorded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PROJECT → TENDER → CONTRACT → MILESTONE
    |--------------------------------------------------------------------------
    */

    private function validateMilestone(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Tender → Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id ===
                (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract → Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->project_id ===
                (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract → Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Milestone → Contract
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $milestone->procurement_contract_id ===
                (int) $contract->id,
            404
        );
    }
}