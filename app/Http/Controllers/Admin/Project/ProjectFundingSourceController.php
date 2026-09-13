<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFundingPlan;
use App\Models\ProjectFundingSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectFundingSourceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): View {

        $this->validateOwnership(
            $project,
            $fundingPlan
        );

        $this->validateEditable(
            $fundingPlan
        );

        return view(
            'projects.funding-plan.sources.create',
            compact(
                'project',
                'fundingPlan'
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
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $fundingPlan
        );

        $this->validateEditable(
            $fundingPlan
        );

        $validated = $request->validate([
            'source_code' => [
                'required',
                'string',
                'max:50',
            ],

            'source_name' => [
                'required',
                'string',
                'max:255',
            ],

            'source_type' => [
                'required',
                'in:Equity,Debt,Promoter Contribution,Investor,Internal Accrual,JV Partner,Government Grant,Other',
            ],

            'provider_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'committed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tenure_months' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'sequence' => [
                'required',
                'integer',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $existingPlanned = $fundingPlan->sources()
            ->where('id', '!=', $fundingSource->id ?? 0)
            ->sum('planned_amount');

        $newTotalPlanned =
            $existingPlanned +
            (float) $validated['planned_amount'];

        if (
            $newTotalPlanned >
            (float) $fundingPlan->total_funding_requirement
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'planned_amount' =>
                        'Total planned funding sources cannot exceed the funding requirement.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Source
        |--------------------------------------------------------------------------
        */

        $fundingPlan
            ->sources()
            ->create([

                'source_code' =>
                    $validated['source_code'],

                'source_name' =>
                    $validated['source_name'],

                'source_type' =>
                    $validated['source_type'],

                'provider_name' =>
                    $validated['provider_name'] ?? null,

                'planned_amount' =>
                    $validated['planned_amount'],

                'committed_amount' =>
                    $validated['committed_amount'],

                'interest_rate' =>
                    $validated['interest_rate'] ?? null,

                'tenure_months' =>
                    $validated['tenure_months'] ?? null,

                'sequence' =>
                    $validated['sequence'] ?? 0,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);


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
                'Funding source added successfully.'
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
        ProjectFundingSource $fundingSource
    ): View {

        $this->validateOwnership(
            $project,
            $fundingPlan
        );

        $this->validateSourceOwnership(
            $fundingPlan,
            $fundingSource
        );

        $this->validateEditable(
            $fundingPlan
        );

        return view(
            'projects.funding-plan.sources.edit',
            compact(
                'project',
                'fundingPlan',
                'fundingSource'
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
        ProjectFundingSource $fundingSource
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $fundingPlan
        );

        $this->validateSourceOwnership(
            $fundingPlan,
            $fundingSource
        );

        $this->validateEditable(
            $fundingPlan
        );

        $validated = $request->validate([
            'source_code' => [
                'required',
                'string',
                'max:50',
            ],

            'source_name' => [
                'required',
                'string',
                'max:255',
            ],

            'source_type' => [
                'required',
                'in:Equity,Debt,Promoter Contribution,Investor,Internal Accrual,JV Partner,Government Grant,Other',
            ],

            'provider_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'planned_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'committed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tenure_months' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'sequence' => [
                'required',
                'integer',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $existingPlanned = $fundingPlan->sources()
            ->where('id', '!=', $fundingSource->id ?? 0)
            ->sum('planned_amount');

        $newTotalPlanned =
            $existingPlanned +
            (float) $validated['planned_amount'];

        if (
            $newTotalPlanned >
            (float) $fundingPlan->total_funding_requirement
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'planned_amount' =>
                        'Total planned funding sources cannot exceed the funding requirement.'
                ]);
        }

        $fundingSource->update([

            'source_code' =>
                $validated['source_code'],

            'source_name' =>
                $validated['source_name'],

            'source_type' =>
                $validated['source_type'],

            'provider_name' =>
                $validated['provider_name'] ?? null,

            'planned_amount' =>
                $validated['planned_amount'],

            'committed_amount' =>
                $validated['committed_amount'],

            'interest_rate' =>
                $validated['interest_rate'] ?? null,

            'tenure_months' =>
                $validated['tenure_months'] ?? null,

            'sequence' =>
                $validated['sequence'] ?? 0,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


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
                'Funding source updated successfully.'
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
        ProjectFundingSource $fundingSource
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $fundingPlan
        );

        $this->validateSourceOwnership(
            $fundingPlan,
            $fundingSource
        );

        $this->validateEditable(
            $fundingPlan
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Delete When Commitments Exist
        |--------------------------------------------------------------------------
        */

        if (
            $fundingSource
                ->commitments()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This funding source cannot be deleted because commitments already exist.'
                );
        }


        $fundingSource->delete();


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
                'Funding source deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Project / Plan Ownership
    |--------------------------------------------------------------------------
    */

    private function validateOwnership(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): void {

        abort_unless(
            (int) $fundingPlan->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Source Ownership
    |--------------------------------------------------------------------------
    */

    private function validateSourceOwnership(
        ProjectFundingPlan $fundingPlan,
        ProjectFundingSource $fundingSource
    ): void {

        abort_unless(
            (int) $fundingSource->project_funding_plan_id ===
            (int) $fundingPlan->id,
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


    /*
    |--------------------------------------------------------------------------
    | Recalculate Funding Plan
    |--------------------------------------------------------------------------
    */

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
                round(
                    $planned,
                    2
                ),

            'total_committed_funding' =>
                round(
                    $committed,
                    2
                ),

            'funding_gap' =>
                round(
                    $gap,
                    2
                ),

            'updated_by' =>
                auth()->id(),
        ]);
    }
}