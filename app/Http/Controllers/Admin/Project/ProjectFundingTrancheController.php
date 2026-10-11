<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFundingCommitment;
use App\Models\ProjectFundingPlan;
use App\Models\ProjectFundingSource;
use App\Models\ProjectFundingTranche;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectFundingTrancheController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment
    ): View {

        $this->validateCommitment(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment
        );

        $this->validateEditable($fundingPlan);

        return view(
            'projects.funding-plan.tranches.create',
            compact(
                'project',
                'fundingPlan',
                'fundingSource',
                'fundingCommitment'
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
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment
    ): RedirectResponse {

        $this->validateCommitment(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment
        );

        $this->validateEditable($fundingPlan);

        $validated = $request->validate([

            'tranche_number' => [
                'required',
                'integer',
                'min:1',
            ],

            'planned_date' => [
                'nullable',
                'date',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'expected_date' => [
                'nullable',
                'date',
            ],

            'actual_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'actual_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Planned,Expected,Received,Delayed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        $commitment = ProjectFundingCommitment::findOrFail(
                $validated['project_funding_commitment_id']
            );

        $existingPlanned = $commitment->tranches()
            ->where(
                'id',
                '!=',
                $fundingTranche->id ?? 0
            )
            ->where(
                'status',
                '!=',
                'Cancelled'
            )
            ->sum('planned_amount');

        $newTrancheTotal =
            $existingPlanned +
            (float) $validated['planned_amount'];

        if (
            $newTrancheTotal >
            (float) $commitment->committed_amount
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'planned_amount' =>
                        'Total planned tranche amounts cannot exceed the commitment amount.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Tranche Amount Validation
        |--------------------------------------------------------------------------
        */

        $existingPlanned =
            $fundingCommitment
                ->tranches()
                ->whereNotIn(
                    'status',
                    ['Cancelled']
                )
                ->sum('planned_amount');


        $availableAmount =
            (float) $fundingCommitment->committed_amount -
            (float) $existingPlanned;


        abort_if(
            (float) $validated['planned_amount'] >
            $availableAmount,
            422,
            'Tranche amount exceeds the remaining committed amount.'
        );


        /*
        |--------------------------------------------------------------------------
        | Actual Amount Cannot Exceed Planned Amount
        |--------------------------------------------------------------------------
        */

        abort_if(
            (float) $validated['actual_amount'] >
            (float) $validated['planned_amount'],
            422,
            'Actual amount cannot exceed planned tranche amount.'
        );


        /*
        |--------------------------------------------------------------------------
        | Create Tranche
        |--------------------------------------------------------------------------
        */

        $fundingCommitment
            ->tranches()
            ->create([

                'project_funding_plan_id' =>
                    $fundingPlan->id,

                'project_funding_source_id' =>
                    $fundingSource->id,

                'tranche_number' =>
                    $validated['tranche_number'],

                'planned_date' =>
                    $validated['planned_date']
                    ?? null,

                'planned_amount' =>
                    $validated['planned_amount'],

                'expected_date' =>
                    $validated['expected_date']
                    ?? null,

                'actual_amount' =>
                    $validated['actual_amount'],

                'actual_date' =>
                    $validated['actual_date']
                    ?? null,

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);


        return redirect()
            ->route(
                'admin.projects.funding-plan.show',
                [
                    'project' =>
                        $project->id,

                    'fundingPlan' =>
                        $fundingPlan->id,
                ]
            )
            ->with(
                'success',
                'Funding tranche added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment,
        ProjectFundingTranche $fundingTranche
    ): View {

        $this->validateTranche(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment,
            $fundingTranche
        );

        $this->validateEditable($fundingPlan);

        return view(
            'projects.funding-plan.tranches.edit',
            compact(
                'project',
                'fundingPlan',
                'fundingSource',
                'fundingCommitment',
                'fundingTranche'
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
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment,
        ProjectFundingTranche $fundingTranche
    ): RedirectResponse {

        $this->validateTranche(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment,
            $fundingTranche
        );

        $this->validateEditable($fundingPlan);

        $validated = $request->validate([

            'tranche_number' => [
                'required',
                'integer',
                'min:1',
            ],

            'planned_date' => [
                'nullable',
                'date',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'expected_date' => [
                'nullable',
                'date',
            ],

            'actual_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'actual_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Planned,Expected,Received,Delayed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Exclude Current Tranche
        |--------------------------------------------------------------------------
        */

        $existingPlanned =
            $fundingCommitment
                ->tranches()
                ->where(
                    'id',
                    '!=',
                    $fundingTranche->id
                )
                ->whereNotIn(
                    'status',
                    ['Cancelled']
                )
                ->sum('planned_amount');


        $availableAmount =
            (float) $fundingCommitment->committed_amount -
            (float) $existingPlanned;


        abort_if(
            (float) $validated['planned_amount'] >
            $availableAmount,
            422,
            'Tranche amount exceeds the remaining committed amount.'
        );


        abort_if(
            (float) $validated['actual_amount'] >
            (float) $validated['planned_amount'],
            422,
            'Actual amount cannot exceed planned tranche amount.'
        );


        $fundingTranche->update([

            'tranche_number' =>
                $validated['tranche_number'],

            'planned_date' =>
                $validated['planned_date']
                ?? null,

            'planned_amount' =>
                $validated['planned_amount'],

            'expected_date' =>
                $validated['expected_date']
                ?? null,

            'actual_amount' =>
                $validated['actual_amount'],

            'actual_date' =>
                $validated['actual_date']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.funding-plan.show',
                [
                    'project' =>
                        $project->id,

                    'fundingPlan' =>
                        $fundingPlan->id,
                ]
            )
            ->with(
                'success',
                'Funding tranche updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment,
        ProjectFundingTranche $fundingTranche
    ): RedirectResponse {

        $this->validateTranche(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment,
            $fundingTranche
        );

        $this->validateEditable($fundingPlan);

        $fundingTranche->delete();

        return redirect()
            ->route(
                'admin.projects.funding-plan.show',
                [
                    'project' =>
                        $project->id,

                    'fundingPlan' =>
                        $fundingPlan->id,
                ]
            )
            ->with(
                'success',
                'Funding tranche deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Commitment
    |--------------------------------------------------------------------------
    */

    private function validateCommitment(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment
    ): void {

        abort_unless(
            (int) $fundingPlan->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            (int) $fundingSource->project_funding_plan_id ===
            (int) $fundingPlan->id,
            404
        );

        abort_unless(
            (int) $fundingCommitment->project_funding_plan_id ===
            (int) $fundingPlan->id,
            404
        );

        abort_unless(
            (int) $fundingCommitment->project_funding_source_id ===
            (int) $fundingSource->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Tranche
    |--------------------------------------------------------------------------
    */

    private function validateTranche(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment,
        ProjectFundingTranche $fundingTranche
    ): void {

        $this->validateCommitment(
            $project,
            $fundingPlan,
            $fundingSource,
            $fundingCommitment
        );

        abort_unless(
            (int) $fundingTranche->project_funding_plan_id ===
            (int) $fundingPlan->id,
            404
        );

        abort_unless(
            (int) $fundingTranche->project_funding_source_id ===
            (int) $fundingSource->id,
            404
        );

        abort_unless(
            (int) $fundingTranche->project_funding_commitment_id ===
            (int) $fundingCommitment->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Editable
    |--------------------------------------------------------------------------
    */

    private function validateEditable(
        ProjectFundingPlan $fundingPlan
    ): void {

        abort_unless(
            $fundingPlan->status !== 'Approved',
            403
        );
    }
}