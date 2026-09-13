<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFundingCommitment;
use App\Models\ProjectFundingPlan;
use App\Models\ProjectFundingSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectFundingCommitmentController extends Controller
{
    public function create(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource
    ): View {

        $this->validateSource(
            $project,
            $fundingPlan,
            $fundingSource
        );

        $this->validateEditable($fundingPlan);

        return view(
            'projects.funding-plan.commitments.create',
            compact(
                'project',
                'fundingPlan',
                'fundingSource'
            )
        );
    }


    public function store(
        Request $request,
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource
    ): RedirectResponse {

        $this->validateSource(
            $project,
            $fundingPlan,
            $fundingSource
        );

        $this->validateEditable($fundingPlan);

        $validated = $request->validate([

            'commitment_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_funding_commitments,commitment_number',
            ],

            'commitment_date' => [
                'nullable',
                'date',
            ],

            'committed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'approved_amount' => [
                'required',
                'numeric',
                'min:0',
                'lte:committed_amount',
            ],

            'provider_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:Planned,Submitted,Approved,Rejected,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        $source = ProjectFundingSource::findOrFail(
		    $validated['project_funding_source_id']
		);

        $existingCommitted = $source->commitments()
		    ->where(
		        'id',
		        '!=',
		        $fundingCommitment->id ?? 0
		    )
		    ->sum('committed_amount');

		$newCommittedTotal =
		    $existingCommitted +
		    (float) $validated['committed_amount'];

		if (
		    $newCommittedTotal >
		    (float) $source->planned_amount
		) {
		    return back()
		        ->withInput()
		        ->withErrors([
		            'committed_amount' =>
		                'Total commitments cannot exceed the planned amount of the funding source.'
		        ]);
		}


        /*
        |--------------------------------------------------------------------------
        | Commitment Cannot Exceed Source Planned Amount
        |--------------------------------------------------------------------------
        */

        $existingCommitted =
            $fundingSource
                ->commitments()
                ->whereNotIn(
                    'status',
                    ['Rejected', 'Cancelled']
                )
                ->sum('committed_amount');


        $available =
            (float) $fundingSource->planned_amount -
            (float) $existingCommitted;


        abort_if(
            (float) $validated['committed_amount'] >
            $available,
            422,
            'Commitment amount exceeds the remaining planned funding for this source.'
        );


        /*
        |--------------------------------------------------------------------------
        | Create Commitment
        |--------------------------------------------------------------------------
        */

        $fundingSource
            ->commitments()
            ->create([

                'project_funding_plan_id' =>
                    $fundingPlan->id,

                'commitment_number' =>
                    $validated['commitment_number'],

                'commitment_date' =>
                    $validated['commitment_date'] ?? null,

                'committed_amount' =>
                    $validated['committed_amount'],

                'approved_amount' =>
                    $validated['approved_amount'],

                'provider_name' =>
                    $validated['provider_name']
                    ?? $fundingSource->provider_name,

                'reference_number' =>
                    $validated['reference_number']
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


        $this->syncSourceCommittedAmount(
            $fundingSource
        );

        $this->recalculateFundingPlan(
            $fundingPlan
        );


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
                'Funding commitment added successfully.'
            );
    }


    public function edit(
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
            'projects.funding-plan.commitments.edit',
            compact(
                'project',
                'fundingPlan',
                'fundingSource',
                'fundingCommitment'
            )
        );
    }


    public function update(
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

            'commitment_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_funding_commitments,commitment_number,' .
                $fundingCommitment->id,
            ],

            'commitment_date' => [
                'nullable',
                'date',
            ],

            'committed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'approved_amount' => [
                'required',
                'numeric',
                'min:0',
                'lte:committed_amount',
            ],

            'provider_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:Planned,Submitted,Approved,Rejected,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Exclude Current Commitment From Existing Total
        |--------------------------------------------------------------------------
        */

        $existingCommitted =
            $fundingSource
                ->commitments()
                ->whereKeyNot(
                    $fundingCommitment->id
                )
                ->whereNotIn(
                    'status',
                    ['Rejected', 'Cancelled']
                )
                ->sum('committed_amount');


        $available =
            (float) $fundingSource->planned_amount -
            (float) $existingCommitted;


        abort_if(
            (float) $validated['committed_amount'] >
            $available,
            422,
            'Commitment amount exceeds the remaining planned funding for this source.'
        );


        $fundingCommitment->update([

            'commitment_number' =>
                $validated['commitment_number'],

            'commitment_date' =>
                $validated['commitment_date'] ?? null,

            'committed_amount' =>
                $validated['committed_amount'],

            'approved_amount' =>
                $validated['approved_amount'],

            'provider_name' =>
                $validated['provider_name']
                ?? $fundingSource->provider_name,

            'reference_number' =>
                $validated['reference_number']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        $this->syncSourceCommittedAmount(
            $fundingSource
        );

        $this->recalculateFundingPlan(
            $fundingPlan
        );


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
                'Funding commitment updated successfully.'
            );
    }


    public function destroy(
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


        /*
        |--------------------------------------------------------------------------
        | Do Not Delete If Tranches Exist
        |--------------------------------------------------------------------------
        */

        if (
            $fundingCommitment
                ->tranches()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This commitment cannot be deleted because funding tranches already exist.'
                );
        }


        $fundingCommitment->delete();


        $this->syncSourceCommittedAmount(
            $fundingSource
        );

        $this->recalculateFundingPlan(
            $fundingPlan
        );


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
                'Funding commitment deleted successfully.'
            );
    }


    private function validateSource(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource
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
    }


    private function validateCommitment(
        Project $project,
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource,
        ProjectFundingCommitment $fundingCommitment
    ): void {

        $this->validateSource(
            $project,
            $fundingPlan,
            $fundingSource
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


    private function validateEditable(
        ProjectFundingPlan $fundingPlan
    ): void {

        abort_unless(
            $fundingPlan->status !== 'Approved',
            403
        );
    }


    private function syncSourceCommittedAmount(
        ProjectFundingSource $fundingSource
    ): void {

        $committed =
            $fundingSource
                ->commitments()
                ->whereNotIn(
                    'status',
                    ['Rejected', 'Cancelled']
                )
                ->sum('committed_amount');


        $fundingSource->update([

            'committed_amount' =>
                round(
                    $committed,
                    2
                ),

        ]);
    }


    private function recalculateFundingPlan(
        ProjectFundingPlan $fundingPlan
    ): void {

        $planned =
            $fundingPlan
                ->sources()
                ->sum('planned_amount');

        $committed =
            $fundingPlan
                ->sources()
                ->sum('committed_amount');

        $requirement =
            (float) $fundingPlan
                ->total_funding_requirement;

        $gap =
            max(
                $requirement -
                (float) $committed,
                0
            );

        $fundingPlan->update([

            'total_planned_funding' =>
                round($planned, 2),

            'total_committed_funding' =>
                round($committed, 2),

            'funding_gap' =>
                round($gap, 2),

            'updated_by' =>
                auth()->id(),
        ]);
    }
}