<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDeliveryStrategy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectDeliveryStrategyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $deliveryStrategies = ProjectDeliveryStrategy::where(
            'project_id',
            $project->id
        )
        ->orderByDesc('version_number')
        ->get();

        return view(
            'projects.delivery-strategy.index',
            compact(
                'project',
                'deliveryStrategies'
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
            ProjectDeliveryStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;

        $strategyNumber =
            'DS-' .
            $project->id .
            '-V' .
            $nextVersion;

        return view(
            'projects.delivery-strategy.create',
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
                'unique:project_delivery_strategies,strategy_number',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'delivery_model' => [
                'required',
                'in:Design-Bid-Build,Design-Build,EPC,EPCM,PMC,Turnkey,Management Contract,Other',
            ],

            'delivery_approach' => [
                'nullable',
                'string',
            ],

            'implementation_strategy' => [
                'nullable',
                'string',
            ],

            'project_packaging_strategy' => [
                'nullable',
                'string',
            ],

            'responsibility_matrix' => [
                'nullable',
                'string',
            ],

            'key_milestones' => [
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
            ProjectDeliveryStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $deliveryStrategy = DB::transaction(
            function () use (
                $validated,
                $project,
                $versionNumber
            ) {

                return ProjectDeliveryStrategy::create([

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

                    'delivery_model' =>
                        $validated['delivery_model'],

                    'delivery_approach' =>
                        $validated['delivery_approach']
                        ?? null,

                    'implementation_strategy' =>
                        $validated['implementation_strategy']
                        ?? null,

                    'project_packaging_strategy' =>
                        $validated['project_packaging_strategy']
                        ?? null,

                    'responsibility_matrix' =>
                        $validated['responsibility_matrix']
                        ?? null,

                    'key_milestones' =>
                        $validated['key_milestones']
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
                'admin.projects.delivery-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'deliveryStrategy' =>
                        $deliveryStrategy->id,
                ]
            )
            ->with(
                'success',
                'Delivery Strategy created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        $revisions =
            ProjectDeliveryStrategy::where(
                'project_id',
                $project->id
            )
            ->orderByDesc('version_number')
            ->get();

        return view(
            'projects.delivery-strategy.show',
            compact(
                'project',
                'deliveryStrategy',
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
        ProjectDeliveryStrategy $deliveryStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status !== 'Approved',
            422,
            'Approved Delivery Strategies are read-only. Create a revision to make changes.'
        );

        return view(
            'projects.delivery-strategy.edit',
            compact(
                'project',
                'deliveryStrategy'
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
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status !== 'Approved',
            422,
            'Approved Delivery Strategies are read-only. Create a revision to make changes.'
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'delivery_model' => [
                'required',
                'in:Design-Bid-Build,Design-Build,EPC,EPCM,PMC,Turnkey,Management Contract,Other',
            ],

            'delivery_approach' => [
                'nullable',
                'string',
            ],

            'implementation_strategy' => [
                'nullable',
                'string',
            ],

            'project_packaging_strategy' => [
                'nullable',
                'string',
            ],

            'responsibility_matrix' => [
                'nullable',
                'string',
            ],

            'key_milestones' => [
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


        $deliveryStrategy->update([

            'title' =>
                $validated['title'],

            'delivery_model' =>
                $validated['delivery_model'],

            'delivery_approach' =>
                $validated['delivery_approach']
                ?? null,

            'implementation_strategy' =>
                $validated['implementation_strategy']
                ?? null,

            'project_packaging_strategy' =>
                $validated['project_packaging_strategy']
                ?? null,

            'responsibility_matrix' =>
                $validated['responsibility_matrix']
                ?? null,

            'key_milestones' =>
                $validated['key_milestones']
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
                'admin.projects.delivery-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'deliveryStrategy' =>
                        $deliveryStrategy->id,
                ]
            )
            ->with(
                'success',
                'Delivery Strategy updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status === 'Draft',
            422,
            'Only Draft Delivery Strategies can be submitted.'
        );


        $deliveryStrategy->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Delivery Strategy submitted for review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status === 'Under Review',
            422,
            'Only Delivery Strategies under review can be approved.'
        );


        $deliveryStrategy->update([

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
                'Delivery Strategy approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status === 'Under Review',
            422,
            'Only Delivery Strategies under review can be rejected.'
        );


        $deliveryStrategy->update([

            'status' =>
                'Rejected',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Delivery Strategy rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REVISION
    |--------------------------------------------------------------------------
    */

    public function revision(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status === 'Approved',
            422,
            'Only an approved Delivery Strategy can create a revision.'
        );


        $newVersion = (
            ProjectDeliveryStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $newStrategyNumber =
            'DS-' .
            $project->id .
            '-V' .
            $newVersion;


        $newStrategy = DB::transaction(
            function () use (
                $deliveryStrategy,
                $project,
                $newVersion,
                $newStrategyNumber
            ) {

                $newStrategy =
                    $deliveryStrategy->replicate([
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
                'admin.projects.delivery-strategy.edit',
                [
                    'project' =>
                        $project->id,

                    'deliveryStrategy' =>
                        $newStrategy->id,
                ]
            )
            ->with(
                'success',
                'Delivery Strategy revision V' .
                $newVersion .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $deliveryStrategy
        );

        abort_unless(
            $deliveryStrategy->status !== 'Approved',
            422,
            'Approved Delivery Strategies cannot be deleted.'
        );


        $deliveryStrategy->delete();


        return redirect()
            ->route(
                'admin.projects.delivery-strategy.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Delivery Strategy deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectDeliveryStrategy $deliveryStrategy
    ): void {

        abort_unless(
            (int) $deliveryStrategy->project_id ===
            (int) $project->id,
            404
        );
    }
}