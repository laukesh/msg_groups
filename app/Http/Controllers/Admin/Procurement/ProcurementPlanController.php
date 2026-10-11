<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPlan;
use App\Models\Project;
use App\Models\ProjectProcurementStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementPlanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Project $project
    ): View {

        $query = ProcurementPlan::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'project',
                'procurementStrategy',
                'preparedBy',
                'approvedBy',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Procurement Year
        |--------------------------------------------------------------------------
        */

        if ($request->filled('procurement_year')) {

            $query->where(
                'procurement_year',
                $request->procurement_year
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'plan_number',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'plan_title',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Paginate
        |--------------------------------------------------------------------------
        */

        $plans = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'procurement.plans.index',
            compact(
                'project',
                'plans'
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

        $procurementStrategies =
            ProjectProcurementStrategy::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->latest('id')
                ->get();


        return view(
            'procurement.plans.create',
            compact(
                'project',
                'procurementStrategies'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Plan Number
    |--------------------------------------------------------------------------
    |
    | Format:
    |
    | PP-2026-0001
    | PP-2026-0002
    |
    */

    private function generatePlanNumber(
        int $procurementYear
    ): string {

        $prefix =
            'PP-' .
            $procurementYear .
            '-';


        $lastPlan =
            ProcurementPlan::query()
                ->where(
                    'procurement_year',
                    $procurementYear
                )
                ->where(
                    'plan_number',
                    'like',
                    $prefix . '%'
                )
                ->orderByDesc('id')
                ->first();


        if (!$lastPlan) {

            $nextNumber = 1;

        } else {

            $lastNumber =
                (int) substr(
                    $lastPlan->plan_number,
                    strlen($prefix)
                );

            $nextNumber =
                $lastNumber + 1;
        }


        return $prefix .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
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

            'procurement_strategy_id' => [
                'nullable',
                'integer',
                'exists:project_procurement_strategies,id',
            ],

            'plan_title' => [
                'required',
                'string',
                'max:255',
            ],

            'procurement_year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'procurement_objective' => [
                'nullable',
                'string',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_start_date',
            ],

            'total_estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ensure Strategy Belongs To Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['procurement_strategy_id']
            )
        ) {

            $strategyExists =
                ProjectProcurementStrategy::query()
                    ->where(
                        'id',
                        $validated[
                            'procurement_strategy_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();


            if (!$strategyExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_strategy_id' =>
                            'The selected Procurement Strategy does not belong to this Project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Plan Number
        |--------------------------------------------------------------------------
        */

        $planNumber =
            $this->generatePlanNumber(
                $validated['procurement_year']
            );


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $plan =
            ProcurementPlan::create([

                'project_id' =>
                    $project->id,

                'procurement_strategy_id' =>
                    $validated[
                        'procurement_strategy_id'
                    ] ?? null,

                'plan_number' =>
                    $planNumber,

                'plan_title' =>
                    $validated['plan_title'],

                'procurement_year' =>
                    $validated['procurement_year'],

                'description' =>
                    $validated['description']
                    ?? null,

                'procurement_objective' =>
                    $validated[
                        'procurement_objective'
                    ] ?? null,

                'planned_start_date' =>
                    $validated[
                        'planned_start_date'
                    ] ?? null,

                'planned_completion_date' =>
                    $validated[
                        'planned_completion_date'
                    ] ?? null,

                'total_estimated_value' =>
                    $validated[
                        'total_estimated_value'
                    ] ?? null,

                'currency' =>
                    $validated['currency'],

                'status' =>
                    'Draft',

                'prepared_by' =>
                    auth()->id(),

                'created_by' =>
                    auth()->id(),

                'remarks' =>
                    $validated['remarks']
                    ?? null,
            ]);


        return redirect()
            ->route(
                'admin.projects.procurement.plans.show',
                [
                    'project' =>
                        $project,

                    'procurementPlan' =>
                        $plan,
                ]
            )
            ->with(
                'success',
                'Procurement Plan ' .
                $plan->plan_number .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementPlan $procurementPlan
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Security / Project Consistency
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $procurementPlan->project_id === $project->id,
            404
        );


        $procurementPlan->load([
            'project',
            'procurementStrategy',
            'preparedBy',
            'reviewedBy',
            'approvedBy',
            'creator',
            'updater',
            'packages',
            'packages.responsibleUser',
            'tenders',
        ]);


        return view(
            'procurement.plans.show',
            compact(
                'project',
                'procurementPlan'
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
        ProcurementPlan $procurementPlan
    ): View {

        abort_unless(
            $procurementPlan->project_id === $project->id,
            404
        );


        if (
            $procurementPlan->status === 'Approved'
        ) {

            abort(
                403,
                'Approved Procurement Plans cannot be edited directly.'
            );
        }


        $procurementStrategies =
            ProjectProcurementStrategy::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->latest('id')
                ->get();


        return view(
            'procurement.plans.edit',
            compact(
                'project',
                'procurementPlan',
                'procurementStrategies'
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
        ProcurementPlan $procurementPlan
    ): RedirectResponse {

        abort_unless(
            $procurementPlan->project_id === $project->id,
            404
        );


        if (
            $procurementPlan->status === 'Approved'
        ) {

            return back()
                ->with(
                    'error',
                    'Approved Procurement Plans cannot be edited directly.'
                );
        }


        $validated = $request->validate([

            'procurement_strategy_id' => [
                'nullable',
                'integer',
                'exists:project_procurement_strategies,id',
            ],

            'plan_title' => [
                'required',
                'string',
                'max:255',
            ],

            'procurement_year' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'procurement_objective' => [
                'nullable',
                'string',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_completion_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_start_date',
            ],

            'total_estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Strategy Must Belong To Same Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['procurement_strategy_id']
            )
        ) {

            $strategyExists =
                ProjectProcurementStrategy::query()
                    ->where(
                        'id',
                        $validated[
                            'procurement_strategy_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();


            if (!$strategyExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_strategy_id' =>
                            'The selected Procurement Strategy does not belong to this Project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | project_id is deliberately NOT updated.
        |
        | A Procurement Plan cannot be moved from one Project
        | to another through the edit screen.
        |
        */

        $procurementPlan->update([

            'procurement_strategy_id' =>
                $validated[
                    'procurement_strategy_id'
                ] ?? null,

            'plan_title' =>
                $validated['plan_title'],

            'procurement_year' =>
                $validated['procurement_year'],

            'description' =>
                $validated['description']
                ?? null,

            'procurement_objective' =>
                $validated[
                    'procurement_objective'
                ] ?? null,

            'planned_start_date' =>
                $validated[
                    'planned_start_date'
                ] ?? null,

            'planned_completion_date' =>
                $validated[
                    'planned_completion_date'
                ] ?? null,

            'total_estimated_value' =>
                $validated[
                    'total_estimated_value'
                ] ?? null,

            'currency' =>
                $validated['currency'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.procurement.plans.show',
                [
                    'project' =>
                        $project,

                    'procurementPlan' =>
                        $procurementPlan,
                ]
            )
            ->with(
                'success',
                'Procurement Plan updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProcurementPlan $procurementPlan
    ): RedirectResponse {

        abort_unless(
            $procurementPlan->project_id === $project->id,
            404
        );


        if (
            $procurementPlan->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Procurement Plans can be deleted.'
                );
        }


        $procurementPlan->delete();


        return redirect()
            ->route(
                'admin.projects.procurement.plans.index',
                $project
            )
            ->with(
                'success',
                'Procurement Plan deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Strategies
    |--------------------------------------------------------------------------
    */

    public function strategies(
        Project $project
    ): JsonResponse {

        $strategies =
            ProjectProcurementStrategy::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->latest('id')
                ->get()
                ->map(
                    function ($strategy) {

                        return [

                            'id' =>
                                $strategy->id,

                            'name' =>
                                $strategy->title
                                ??
                                $strategy->strategy_title
                                ??
                                $strategy->name
                                ??
                                (
                                    'Strategy #' .
                                    $strategy->id
                                ),
                        ];
                    }
                );


        return response()->json(
            $strategies
        );
    }
}