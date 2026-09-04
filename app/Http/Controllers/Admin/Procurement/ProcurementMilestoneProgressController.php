<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
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
    public function index(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        $progressUpdates = $milestone
            ->progressUpdates()
            ->latest('progress_date')
            ->latest('id')
            ->get();

        return view(
            'procurement.milestone-progress.index',
            compact(
                'procurementTender',
                'contract',
                'milestone',
                'progressUpdates'
            )
        );
    }


    public function create(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        if (
            $contract->status !== 'Active'
        ) {
            abort(
                403,
                'Progress can only be recorded for an Active Contract.'
            );
        }

        if (
            $milestone->status === 'Completed'
        ) {
            abort(
                403,
                'Completed milestones cannot receive new progress.'
            );
        }

        return view(
            'procurement.milestone-progress.create',
            compact(
                'procurementTender',
                'contract',
                'milestone'
            )
        );
    }


    public function store(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        if ($contract->status !== 'Active') {

            return back()
                ->withInput()
                ->withErrors([
                    'progress' =>
                        'Progress can only be recorded for an Active Contract.',
                ]);
        }

        if ($milestone->status === 'Completed') {

            return back()
                ->withInput()
                ->withErrors([
                    'progress' =>
                        'Completed milestones cannot receive new progress.',
                ]);
        }


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


        $currentProgress =
            (float)
            $milestone->progress_percentage;


        $newProgress =
            (float)
            $validated['progress_percentage'];


        if ($newProgress < $currentProgress) {

            return back()
                ->withInput()
                ->withErrors([
                    'progress_percentage' =>
                        "Progress cannot decrease. Current progress is {$currentProgress}%.",
                ]);
        }


        DB::transaction(function () use (
            $validated,
            $contract,
            $milestone,
            $currentProgress,
            $newProgress
        ) {

            ProcurementMilestoneProgress::create([

                'procurement_contract_id' =>
                    $contract->id,

                'procurement_contract_milestone_id' =>
                    $milestone->id,

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


            $milestone->update([

                'progress_percentage' =>
                    $newProgress,

                'status' =>
                    $newProgress >= 100
                        ? 'Completed'
                        : 'In Progress',

                'actual_start_date' =>
                    $milestone->actual_start_date
                    ?? $validated['progress_date'],

                'actual_end_date' =>
                    $newProgress >= 100
                        ? $validated['progress_date']
                        : $milestone->actual_end_date,

                'completed_by' =>
                    $newProgress >= 100
                        ? auth()->id()
                        : $milestone->completed_by,

                'completed_at' =>
                    $newProgress >= 100
                        ? now()
                        : $milestone->completed_at,

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.milestones.progress.index',
                [
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


    private function validateMilestone(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );

        abort_unless(
            $milestone->procurement_contract_id
            === $contract->id,
            404
        );
    }
}