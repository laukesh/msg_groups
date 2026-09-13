<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectContractStrategy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectContractStrategyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $contractStrategies = ProjectContractStrategy::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('version_number')
            ->get();

        return view(
            'projects.contract-strategy.index',
            compact(
                'project',
                'contractStrategies'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $nextVersion = (
            ProjectContractStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;

        $strategyNumber =
            'CS-' .
            $project->id .
            '-V' .
            $nextVersion;

        return view(
            'projects.contract-strategy.create',
            compact(
                'project',
                'nextVersion',
                'strategyNumber'
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

        $validated = $request->validate([

            'strategy_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_contract_strategies,strategy_number',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'contracting_model' => [
                'required',
                'in:Single Main Contract,Multiple Package Contracts,EPC,EPCM,Design-Build,Design-Bid-Build,Turnkey,Management Contract,Framework Agreement,Other',
            ],

            'contract_type' => [
                'required',
                'in:Lump Sum,Item Rate,Cost Plus,Time and Material,Target Cost,Guaranteed Maximum Price,Hybrid,Other',
            ],

            'commercial_model' => [
                'nullable',
                'string',
            ],

            'contract_packaging' => [
                'nullable',
                'string',
            ],

            'payment_strategy' => [
                'nullable',
                'string',
            ],

            'risk_allocation_strategy' => [
                'nullable',
                'string',
            ],

            'performance_security_strategy' => [
                'nullable',
                'string',
            ],

            'retention_strategy' => [
                'nullable',
                'string',
            ],

            'liquidated_damages_strategy' => [
                'nullable',
                'string',
            ],

            'insurance_strategy' => [
                'nullable',
                'string',
            ],

            'variation_change_strategy' => [
                'nullable',
                'string',
            ],

            'claims_strategy' => [
                'nullable',
                'string',
            ],

            'dispute_resolution_strategy' => [
                'nullable',
                'string',
            ],

            'defect_liability_strategy' => [
                'nullable',
                'string',
            ],

            'assumptions' => [
                'nullable',
                'string',
            ],

            'constraints' => [
                'nullable',
                'string',
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


        $versionNumber = (
            ProjectContractStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $contractStrategy = DB::transaction(
            function () use (
                $validated,
                $project,
                $versionNumber
            ) {

                return ProjectContractStrategy::create([

                    'project_id' =>
                        $project->id,

                    'strategy_number' =>
                        $validated['strategy_number'],

                    'title' =>
                        $validated['title'],

                    'version_number' =>
                        $versionNumber,

                    'status' =>
                        'Draft',

                    'contracting_model' =>
                        $validated['contracting_model'],

                    'contract_type' =>
                        $validated['contract_type'],

                    'commercial_model' =>
                        $validated['commercial_model']
                        ?? null,

                    'contract_packaging' =>
                        $validated['contract_packaging']
                        ?? null,

                    'payment_strategy' =>
                        $validated['payment_strategy']
                        ?? null,

                    'risk_allocation_strategy' =>
                        $validated['risk_allocation_strategy']
                        ?? null,

                    'performance_security_strategy' =>
                        $validated['performance_security_strategy']
                        ?? null,

                    'retention_strategy' =>
                        $validated['retention_strategy']
                        ?? null,

                    'liquidated_damages_strategy' =>
                        $validated['liquidated_damages_strategy']
                        ?? null,

                    'insurance_strategy' =>
                        $validated['insurance_strategy']
                        ?? null,

                    'variation_change_strategy' =>
                        $validated['variation_change_strategy']
                        ?? null,

                    'claims_strategy' =>
                        $validated['claims_strategy']
                        ?? null,

                    'dispute_resolution_strategy' =>
                        $validated['dispute_resolution_strategy']
                        ?? null,

                    'defect_liability_strategy' =>
                        $validated['defect_liability_strategy']
                        ?? null,

                    'assumptions' =>
                        $validated['assumptions']
                        ?? null,

                    'constraints' =>
                        $validated['constraints']
                        ?? null,

                    'effective_date' =>
                        $validated['effective_date']
                        ?? null,

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);
            }
        );


        return redirect()
            ->route(
                'admin.projects.contract-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'contractStrategy' =>
                        $contractStrategy->id,
                ]
            )
            ->with(
                'success',
                'Contract Strategy created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        $revisions =
            ProjectContractStrategy::where(
                'project_id',
                $project->id
            )
            ->orderByDesc('version_number')
            ->get();

        return view(
            'projects.contract-strategy.show',
            compact(
                'project',
                'contractStrategy',
                'revisions'
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
        ProjectContractStrategy $contractStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status !== 'Approved',
            422,
            'Approved Contract Strategies are read-only. Create a revision to make changes.'
        );

        return view(
            'projects.contract-strategy.edit',
            compact(
                'project',
                'contractStrategy'
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
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status !== 'Approved',
            422,
            'Approved Contract Strategies are read-only. Create a revision to make changes.'
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'contracting_model' => [
                'required',
                'in:Single Main Contract,Multiple Package Contracts,EPC,EPCM,Design-Build,Design-Bid-Build,Turnkey,Management Contract,Framework Agreement,Other',
            ],

            'contract_type' => [
                'required',
                'in:Lump Sum,Item Rate,Cost Plus,Time and Material,Target Cost,Guaranteed Maximum Price,Hybrid,Other',
            ],

            'commercial_model' => [
                'nullable',
                'string',
            ],

            'contract_packaging' => [
                'nullable',
                'string',
            ],

            'payment_strategy' => [
                'nullable',
                'string',
            ],

            'risk_allocation_strategy' => [
                'nullable',
                'string',
            ],

            'performance_security_strategy' => [
                'nullable',
                'string',
            ],

            'retention_strategy' => [
                'nullable',
                'string',
            ],

            'liquidated_damages_strategy' => [
                'nullable',
                'string',
            ],

            'insurance_strategy' => [
                'nullable',
                'string',
            ],

            'variation_change_strategy' => [
                'nullable',
                'string',
            ],

            'claims_strategy' => [
                'nullable',
                'string',
            ],

            'dispute_resolution_strategy' => [
                'nullable',
                'string',
            ],

            'defect_liability_strategy' => [
                'nullable',
                'string',
            ],

            'assumptions' => [
                'nullable',
                'string',
            ],

            'constraints' => [
                'nullable',
                'string',
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


        $contractStrategy->update([

            'title' =>
                $validated['title'],

            'contracting_model' =>
                $validated['contracting_model'],

            'contract_type' =>
                $validated['contract_type'],

            'commercial_model' =>
                $validated['commercial_model']
                ?? null,

            'contract_packaging' =>
                $validated['contract_packaging']
                ?? null,

            'payment_strategy' =>
                $validated['payment_strategy']
                ?? null,

            'risk_allocation_strategy' =>
                $validated['risk_allocation_strategy']
                ?? null,

            'performance_security_strategy' =>
                $validated['performance_security_strategy']
                ?? null,

            'retention_strategy' =>
                $validated['retention_strategy']
                ?? null,

            'liquidated_damages_strategy' =>
                $validated['liquidated_damages_strategy']
                ?? null,

            'insurance_strategy' =>
                $validated['insurance_strategy']
                ?? null,

            'variation_change_strategy' =>
                $validated['variation_change_strategy']
                ?? null,

            'claims_strategy' =>
                $validated['claims_strategy']
                ?? null,

            'dispute_resolution_strategy' =>
                $validated['dispute_resolution_strategy']
                ?? null,

            'defect_liability_strategy' =>
                $validated['defect_liability_strategy']
                ?? null,

            'assumptions' =>
                $validated['assumptions']
                ?? null,

            'constraints' =>
                $validated['constraints']
                ?? null,

            'effective_date' =>
                $validated['effective_date']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.contract-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'contractStrategy' =>
                        $contractStrategy->id,
                ]
            )
            ->with(
                'success',
                'Contract Strategy updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status === 'Draft',
            422,
            'Only Draft Contract Strategies can be submitted.'
        );


        $contractStrategy->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Contract Strategy submitted for review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status === 'Under Review',
            422,
            'Only Contract Strategies under review can be approved.'
        );


        $contractStrategy->update([

            'status' =>
                'Approved',

            'approved_date' =>
                now()->toDateString(),

            'approved_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Contract Strategy approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status === 'Under Review',
            422,
            'Only Contract Strategies under review can be rejected.'
        );


        $contractStrategy->update([

            'status' =>
                'Rejected',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Contract Strategy rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REVISION
    |--------------------------------------------------------------------------
    */

    public function revision(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status === 'Approved',
            422,
            'Only an approved Contract Strategy can create a revision.'
        );


        $newVersion = (
            ProjectContractStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $newStrategyNumber =
            'CS-' .
            $project->id .
            '-V' .
            $newVersion;


        $newStrategy = DB::transaction(
            function () use (
                $contractStrategy,
                $project,
                $newVersion,
                $newStrategyNumber
            ) {

                $newStrategy =
                    $contractStrategy->replicate([
                        'id',
                        'created_at',
                        'updated_at',
                    ]);


                $newStrategy->project_id =
                    $project->id;

                $newStrategy->strategy_number =
                    $newStrategyNumber;

                $newStrategy->version_number =
                    $newVersion;

                $newStrategy->status =
                    'Draft';

                $newStrategy->effective_date =
                    null;

                $newStrategy->approved_date =
                    null;

                $newStrategy->approved_by =
                    null;

                $newStrategy->created_by =
                    auth()->id();

                $newStrategy->updated_by =
                    auth()->id();

                $newStrategy->save();


                return $newStrategy;
            }
        );


        return redirect()
            ->route(
                'admin.projects.contract-strategy.edit',
                [
                    'project' =>
                        $project->id,

                    'contractStrategy' =>
                        $newStrategy->id,
                ]
            )
            ->with(
                'success',
                'Contract Strategy revision V' .
                $newVersion .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $contractStrategy
        );

        abort_unless(
            $contractStrategy->status !== 'Approved',
            422,
            'Approved Contract Strategies cannot be deleted.'
        );


        $contractStrategy->delete();


        return redirect()
            ->route(
                'admin.projects.contract-strategy.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Contract Strategy deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectContractStrategy $contractStrategy
    ): void {

        abort_unless(
            (int) $contractStrategy->project_id ===
            (int) $project->id,
            404
        );
    }
}