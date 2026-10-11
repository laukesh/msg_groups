<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectProcurementStrategy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectProcurementStrategyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $procurementStrategies = ProjectProcurementStrategy::where(
            'project_id',
            $project->id
        )
        ->orderByDesc('version_number')
        ->get();

        return view(
            'projects.procurement-strategy.index',
            compact(
                'project',
                'procurementStrategies'
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
            ProjectProcurementStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;

        $strategyNumber =
            'PS-' .
            $project->id .
            '-V' .
            $nextVersion;

        return view(
            'projects.procurement-strategy.create',
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
                'unique:project_procurement_strategies,strategy_number',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'procurement_model' => [
                'required',
                'in:Traditional,Design-Bid-Build,Design-Build,EPC,EPCM,Turnkey,Framework Agreement,Direct Procurement,Competitive Tender,Negotiated Procurement,Other',
            ],

            'procurement_approach' => [
                'nullable',
                'string',
            ],

            'procurement_packages' => [
                'nullable',
                'string',
            ],

            'sourcing_strategy' => [
                'nullable',
                'string',
            ],

            'tendering_strategy' => [
                'nullable',
                'string',
            ],

            'vendor_selection_criteria' => [
                'nullable',
                'string',
            ],

            'procurement_schedule' => [
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
            ProjectProcurementStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $procurementStrategy = DB::transaction(
            function () use (
                $validated,
                $project,
                $versionNumber
            ) {

                return ProjectProcurementStrategy::create([

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

                    'procurement_model' =>
                        $validated['procurement_model'],

                    'procurement_approach' =>
                        $validated['procurement_approach']
                        ?? null,

                    'procurement_packages' =>
                        $validated['procurement_packages']
                        ?? null,

                    'sourcing_strategy' =>
                        $validated['sourcing_strategy']
                        ?? null,

                    'tendering_strategy' =>
                        $validated['tendering_strategy']
                        ?? null,

                    'vendor_selection_criteria' =>
                        $validated['vendor_selection_criteria']
                        ?? null,

                    'procurement_schedule' =>
                        $validated['procurement_schedule']
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
                'admin.projects.procurement-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'procurementStrategy' =>
                        $procurementStrategy->id,
                ]
            )
            ->with(
                'success',
                'Procurement Strategy created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        $revisions =
            ProjectProcurementStrategy::where(
                'project_id',
                $project->id
            )
            ->orderByDesc('version_number')
            ->get();

        return view(
            'projects.procurement-strategy.show',
            compact(
                'project',
                'procurementStrategy',
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
        ProjectProcurementStrategy $procurementStrategy
    ): View {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status !== 'Approved',
            422,
            'Approved Procurement Strategies are read-only. Create a revision to make changes.'
        );

        return view(
            'projects.procurement-strategy.edit',
            compact(
                'project',
                'procurementStrategy'
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
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status !== 'Approved',
            422,
            'Approved Procurement Strategies are read-only. Create a revision to make changes.'
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'procurement_model' => [
                'required',
                'in:Traditional,Design-Bid-Build,Design-Build,EPC,EPCM,Turnkey,Framework Agreement,Direct Procurement,Competitive Tender,Negotiated Procurement,Other',
            ],

            'procurement_approach' => [
                'nullable',
                'string',
            ],

            'procurement_packages' => [
                'nullable',
                'string',
            ],

            'sourcing_strategy' => [
                'nullable',
                'string',
            ],

            'tendering_strategy' => [
                'nullable',
                'string',
            ],

            'vendor_selection_criteria' => [
                'nullable',
                'string',
            ],

            'procurement_schedule' => [
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


        $procurementStrategy->update([

            'title' =>
                $validated['title'],

            'procurement_model' =>
                $validated['procurement_model'],

            'procurement_approach' =>
                $validated['procurement_approach']
                ?? null,

            'procurement_packages' =>
                $validated['procurement_packages']
                ?? null,

            'sourcing_strategy' =>
                $validated['sourcing_strategy']
                ?? null,

            'tendering_strategy' =>
                $validated['tendering_strategy']
                ?? null,

            'vendor_selection_criteria' =>
                $validated['vendor_selection_criteria']
                ?? null,

            'procurement_schedule' =>
                $validated['procurement_schedule']
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
                'admin.projects.procurement-strategy.show',
                [
                    'project' =>
                        $project->id,

                    'procurementStrategy' =>
                        $procurementStrategy->id,
                ]
            )
            ->with(
                'success',
                'Procurement Strategy updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status === 'Draft',
            422,
            'Only Draft Procurement Strategies can be submitted.'
        );


        $procurementStrategy->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Procurement Strategy submitted for review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status === 'Under Review',
            422,
            'Only Procurement Strategies under review can be approved.'
        );


        $procurementStrategy->update([

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
                'Procurement Strategy approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status === 'Under Review',
            422,
            'Only Procurement Strategies under review can be rejected.'
        );


        $procurementStrategy->update([

            'status' =>
                'Rejected',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Procurement Strategy rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REVISION
    |--------------------------------------------------------------------------
    */

    public function revision(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status === 'Approved',
            422,
            'Only an approved Procurement Strategy can create a revision.'
        );


        $newVersion = (
            ProjectProcurementStrategy::where(
                'project_id',
                $project->id
            )->max('version_number') ?? 0
        ) + 1;


        $newStrategyNumber =
            'PS-' .
            $project->id .
            '-V' .
            $newVersion;


        $newStrategy = DB::transaction(
            function () use (
                $procurementStrategy,
                $project,
                $newVersion,
                $newStrategyNumber
            ) {

                $newStrategy =
                    $procurementStrategy->replicate([
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
                'admin.projects.procurement-strategy.edit',
                [
                    'project' =>
                        $project->id,

                    'procurementStrategy' =>
                        $newStrategy->id,
                ]
            )
            ->with(
                'success',
                'Procurement Strategy revision V' .
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
        ProjectProcurementStrategy $procurementStrategy
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $procurementStrategy
        );

        abort_unless(
            $procurementStrategy->status !== 'Approved',
            422,
            'Approved Procurement Strategies cannot be deleted.'
        );


        $procurementStrategy->delete();


        return redirect()
            ->route(
                'admin.projects.procurement-strategy.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Procurement Strategy deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectProcurementStrategy $procurementStrategy
    ): void {

        abort_unless(
            (int) $procurementStrategy->project_id ===
            (int) $project->id,
            404
        );
    }
}