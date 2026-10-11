<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectFundingPlan;
use App\Models\ProjectFundingPlanHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProjectFundingPlanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $fundingPlans = $project
            ->fundingPlans()
            ->with('basisBudget')
            ->get();

        return view(
            'projects.funding-plan.index',
            compact(
                'project',
                'fundingPlans'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Current Approved Budget
        |--------------------------------------------------------------------------
        */

        $approvedBudget = $project
            ->approvedBudget;


        return view(
            'projects.funding-plan.create',
            compact(
                'project',
                'approvedBudget'
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
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'basis_budget_id' => [
                'required',
                'integer',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Budget
        |--------------------------------------------------------------------------
        */

        $budget = $project
            ->budgets()
            ->find(
                $validated['basis_budget_id']
            );


        abort_unless(
            $budget,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Funding Plan Should Normally Use Approved Budget
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $budget->status === 'Approved',
            422,
            'Funding Plan must be based on an approved project budget.'
        );


        /*
        |--------------------------------------------------------------------------
        | Version
        |--------------------------------------------------------------------------
        */

        $versionNumber = (
            $project
                ->fundingPlans()
                ->max('version_number')
            ?? 0
        ) + 1;


        /*
        |--------------------------------------------------------------------------
        | Funding Plan Number
        |--------------------------------------------------------------------------
        */

        $fundingPlanNumber =
            $this->generateFundingPlanNumber(
                $project,
                $versionNumber
            );


        /*
        |--------------------------------------------------------------------------
        | Funding Requirement
        |--------------------------------------------------------------------------
        */

        $fundingRequirement =
            (float) $budget->total_budget;


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $fundingPlan =
            $project
                ->fundingPlans()
                ->create([

                    'basis_budget_id' =>
                        $budget->id,

                    'funding_plan_number' =>
                        $fundingPlanNumber,

                    'title' =>
                        $validated['title'],

                    'version_number' =>
                        $versionNumber,

                    'status' =>
                        'Draft',

                    'currency' =>
                        $validated['currency'],

                    'total_funding_requirement' =>
                        $fundingRequirement,

                    'total_planned_funding' =>
                        0,

                    'total_committed_funding' =>
                        0,

                    'funding_gap' =>
                        $fundingRequirement,

                    'effective_date' =>
                        $validated['effective_date'] ?? null,

                    'remarks' =>
                        $validated['remarks'] ?? null,

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
                'Funding Plan created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): View {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );

        $fundingPlan->load([
            'basisBudget',

            'sources.commitments.tranches',

            'commitments.source',
            'commitments.tranches',

            'tranches.source',
            'tranches.commitment',
            'histories'
        ]);

        $revisions = ProjectFundingPlan::where(
            'project_id',
            $project->id
        )
        ->where(
            'basis_budget_id',
            $fundingPlan->basis_budget_id
        )
        ->orderByDesc('version_number')
        ->get();

        return view(
            'projects.funding-plan.show',
            compact(
                'project',
                'fundingPlan',
                'revisions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): View {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );


        $this->validateEditablePlan(
            $fundingPlan
        );


        $budgets = $project
            ->budgets()
            ->where(
                'status',
                'Approved'
            )
            ->orderByDesc(
                'version_number'
            )
            ->get();


        return view(
            'projects.funding-plan.edit',
            compact(
                'project',
                'fundingPlan',
                'budgets'
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
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );


        $this->validateEditablePlan(
            $fundingPlan
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'basis_budget_id' => [
                'required',
                'integer',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Budget Ownership
        |--------------------------------------------------------------------------
        */

        $budget = $project
            ->budgets()
            ->find(
                $validated['basis_budget_id']
            );


        abort_unless(
            $budget,
            404
        );


        abort_unless(
            $budget->status === 'Approved',
            422,
            'Funding Plan must be based on an approved project budget.'
        );


        /*
        |--------------------------------------------------------------------------
        | Update Requirement From Budget
        |--------------------------------------------------------------------------
        */

        $fundingRequirement =
            (float) $budget->total_budget;


        $fundingPlan->update([

            'basis_budget_id' =>
                $budget->id,

            'title' =>
                $validated['title'],

            'currency' =>
                $validated['currency'],

            'total_funding_requirement' =>
                $fundingRequirement,

            'effective_date' =>
                $validated['effective_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Recalculate Gap
        |--------------------------------------------------------------------------
        */

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
                'Funding Plan updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Revision
    |--------------------------------------------------------------------------
    */

    public function createRevision(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );


        abort_unless(
            $fundingPlan->status === 'Approved',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Next Version
        |--------------------------------------------------------------------------
        */

        $nextVersion = (
            $project
                ->fundingPlans()
                ->max('version_number')
            ?? 0
        ) + 1;


        /*
        |--------------------------------------------------------------------------
        | New Number
        |--------------------------------------------------------------------------
        */

        $fundingPlanNumber =
            $this->generateFundingPlanNumber(
                $project,
                $nextVersion
            );


        /*
        |--------------------------------------------------------------------------
        | Create Revision
        |--------------------------------------------------------------------------
        */

        $newPlan =
            $project
                ->fundingPlans()
                ->create([

                    'basis_budget_id' =>
                        $fundingPlan->basis_budget_id,

                    'funding_plan_number' =>
                        $fundingPlanNumber,

                    'title' =>
                        $fundingPlan->title .
                        ' - Revision ' .
                        $nextVersion,

                    'version_number' =>
                        $nextVersion,

                    'status' =>
                        'Draft',

                    'currency' =>
                        $fundingPlan->currency,

                    'total_funding_requirement' =>
                        $fundingPlan
                            ->total_funding_requirement,

                    'total_planned_funding' =>
                        0,

                    'total_committed_funding' =>
                        0,

                    'funding_gap' =>
                        $fundingPlan
                            ->total_funding_requirement,

                    'effective_date' =>
                        $fundingPlan->effective_date,

                    'remarks' =>
                        $fundingPlan->remarks,

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
                        $newPlan->id,
                ]
            )
            ->with(
                'success',
                'Funding Plan revision V' .
                $nextVersion .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );


        $this->validateEditablePlan(
            $fundingPlan
        );


        $fundingPlan->delete();


        return redirect()
            ->route(
                'admin.projects.funding-plan.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Funding Plan deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Recalculate
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
            (float)
            $fundingPlan
                ->total_funding_requirement;


        $gap =
            $requirement -
            $committed;


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
                    max(
                        $gap,
                        0
                    ),
                    2
                ),

            'updated_by' =>
                auth()->id(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Plan Ownership
    |--------------------------------------------------------------------------
    */

    private function validatePlanOwnership(
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
    | Editable Plan
    |--------------------------------------------------------------------------
    */

    private function validateEditablePlan(
        ProjectFundingPlan $fundingPlan
    ): void {

        abort_unless(
            $fundingPlan->status !== 'Approved',
            403
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Funding Plan Number
    |--------------------------------------------------------------------------
    */

    private function generateFundingPlanNumber(
        Project $project,
        int $version
    ): string {

        return 'FP-' .
            $project->id .
            '-V' .
            $version;
    }

    public function submit(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );

        abort_unless(
            $fundingPlan->status === 'Draft',
            422,
            'Only a Draft Funding Plan can be submitted.'
        );

        $fundingPlan->update([
            'status' => 'Under Review',
            'submitted_at' => now(),
            'submitted_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        ProjectFundingPlanHistory::create([
            'project_funding_plan_id' => $fundingPlan->id,
            'action' => 'Submitted',
            'old_status' => 'Draft',
            'new_status' => 'Submitted',
            'remarks' => $fundingPlan->remarks,
            'performed_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Funding Plan submitted for review.'
        );
    }

    public function approve(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );

        abort_unless(
            $fundingPlan->status === 'Under Review',
            422,
            'Only a Funding Plan under review can be approved.'
        );

        $fundingPlan->update([
            'status' => 'Approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        ProjectFundingPlanHistory::create([
            'project_funding_plan_id' => $fundingPlan->id,
            'action' => 'Approved',
            'old_status' => 'Under Review',
            'new_status' => 'Approved',
            'remarks' => $fundingPlan->remarks,
            'performed_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Funding Plan approved successfully.'
        );
    }
    public function reject(
        Request $request,
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );

        abort_unless(
            $fundingPlan->status === 'Under Review',
            422,
            'Only a Funding Plan under review can be rejected.'
        );

        $validated = $request->validate([
            'rejection_reason' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $fundingPlan->update([
            'status' => 'Rejected',
            'rejection_reason' =>
                $validated['rejection_reason'] ?? null,
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        ProjectFundingPlanHistory::create([
            'project_funding_plan_id' => $fundingPlan->id,
            'action' => 'Rejected',
            'old_status' => 'Under Review',
            'new_status' => 'Rejected',
            'remarks' => $fundingPlan->remarks,
            'performed_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Funding Plan rejected.'
        );
    }
    
    public function revision(
        Project $project,
        ProjectFundingPlan $fundingPlan
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Project Ownership
        |--------------------------------------------------------------------------
        */

        $this->validatePlanOwnership(
            $project,
            $fundingPlan
        );


        /*
        |--------------------------------------------------------------------------
        | Only Approved Plan Can Create Revision
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $fundingPlan->status === 'Approved',
            422,
            'Only an approved Funding Plan can create a revision.'
        );


        /*
        |--------------------------------------------------------------------------
        | Load Complete Funding Hierarchy
        |--------------------------------------------------------------------------
        */

        $fundingPlan->load([
            'sources.commitments.tranches',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Determine Next Version
        |--------------------------------------------------------------------------
        */

        $newVersion = ProjectFundingPlan::where(
            'project_id',
            $project->id
        )->max('version_number') + 1;


        /*
        |--------------------------------------------------------------------------
        | Generate New Funding Plan Number
        |--------------------------------------------------------------------------
        */

        $newFundingPlanNumber =
            'FP-' .
            $project->id .
            '-V' .
            $newVersion;


        /*
        |--------------------------------------------------------------------------
        | Start Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $project,
            $fundingPlan,
            $newVersion,
            $newFundingPlanNumber,
            &$newPlan
        ) {

            /*
            |--------------------------------------------------------------------------
            | 1. Copy Funding Plan
            |--------------------------------------------------------------------------
            */

            $newPlan = $fundingPlan->replicate([
                'id',
                'created_at',
                'updated_at',
            ]);


            $newPlan->funding_plan_number =
                $newFundingPlanNumber;

            $newPlan->version_number =
                $newVersion;

            $newPlan->status =
                'Draft';


            /*
            |--------------------------------------------------------------------------
            | Reset Approval Information
            |--------------------------------------------------------------------------
            */

            $newPlan->effective_date =
                null;

            $newPlan->approved_date =
                null;

            $newPlan->approved_by =
                null;


            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $newPlan->created_by =
                auth()->id();

            $newPlan->updated_by =
                auth()->id();


            $newPlan->save();


            /*
            |--------------------------------------------------------------------------
            | 2. Copy Funding Sources
            |--------------------------------------------------------------------------
            */

            foreach (
                $fundingPlan->sources
                as $source
            ) {

                $newSource = $source->replicate([
                    'id',
                    'created_at',
                    'updated_at',
                ]);


                $newSource->project_funding_plan_id =
                    $newPlan->id;

                $newSource->created_by =
                    auth()->id();

                $newSource->updated_by =
                    auth()->id();


                $newSource->save();


                /*
                |--------------------------------------------------------------------------
                | 3. Copy Commitments
                |--------------------------------------------------------------------------
                */

                foreach (
                    $source->commitments
                    as $commitment
                ) {

                    $newCommitment =
                        $commitment->replicate([
                            'id',
                            'created_at',
                            'updated_at',
                        ]);


                    $newCommitment->project_funding_plan_id =
                        $newPlan->id;

                    $newCommitment->project_funding_source_id =
                        $newSource->id;


                    /*
                    |--------------------------------------------------------------------------
                    | Commitment Number Must Be Unique
                    |--------------------------------------------------------------------------
                    */

                    $newCommitment->commitment_number =
                        $commitment->commitment_number .
                        '-V' .
                        $newVersion;


                    $newCommitment->created_by =
                        auth()->id();

                    $newCommitment->updated_by =
                        auth()->id();


                    $newCommitment->save();


                    /*
                    |--------------------------------------------------------------------------
                    | 4. Copy Tranches
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $commitment->tranches
                        as $tranche
                    ) {

                        $newTranche =
                            $tranche->replicate([
                                'id',
                                'created_at',
                                'updated_at',
                            ]);


                        $newTranche->project_funding_plan_id =
                            $newPlan->id;

                        $newTranche->project_funding_source_id =
                            $newSource->id;

                        $newTranche->project_funding_commitment_id =
                            $newCommitment->id;


                        $newTranche->created_by =
                            auth()->id();

                        $newTranche->updated_by =
                            auth()->id();


                        $newTranche->save();
                    }
                }
            }

            ProjectFundingPlanHistory::create([
                'project_funding_plan_id' => $newPlan->id,
                'action' => 'Revision Created',
                'old_status' => null,
                'new_status' => 'Draft',
                'remarks' =>
                    'Revision created from Funding Plan ' .
                    $fundingPlan->funding_plan_number,
                'performed_by' => auth()->id(),
            ]);
            
        });

        


        /*
        |--------------------------------------------------------------------------
        | Redirect to New Revision
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.funding-plan.edit',
                [
                    'project' =>
                        $project->id,

                    'fundingPlan' =>
                        $newPlan->id,
                ]
            )
            ->with(
                'success',
                'Funding Plan revision V' .
                $newVersion .
                ' created successfully with funding sources, commitments and tranches.'
            );
    }

}