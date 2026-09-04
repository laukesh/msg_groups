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

    public function index(Request $request): View
    {
        $query = ProcurementPlan::query()
            ->with([
                'project',
                'procurementStrategy',
                'preparedBy',
                'approvedBy',
            ]);

        if ($request->filled('project_id')) {

            $query->where(
                'project_id',
                $request->project_id
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('procurement_year')) {

            $query->where(
                'procurement_year',
                $request->procurement_year
            );
        }

        $plans = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $projects = Project::query()
            ->orderBy('project_name')
            ->get([
                'id',
                'project_name',
                'project_code',
            ]);

        return view(
            'procurement.plans.index',
            compact(
                'plans',
                'projects'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $projects = Project::query()
            ->orderBy('project_name')
            ->get([
                'id',
                'project_name',
                'project_code',
            ]);

        return view(
            'procurement.plans.create',
            compact('projects')
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
    | PP-2026-0003
    |
    */

    private function generatePlanNumber(
        int $procurementYear
    ): string {

        $prefix = 'PP-' . $procurementYear . '-';

        $lastPlan = ProcurementPlan::query()
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

            $lastNumber = (int) substr(
                $lastPlan->plan_number,
                strlen($prefix)
            );

            $nextNumber = $lastNumber + 1;
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
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            'project_id' => [
                'required',
                'integer',
                'exists:projects,id',
            ],

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
        | Ensure Procurement Strategy belongs to Project
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
                        $validated['procurement_strategy_id']
                    )
                    ->where(
                        'project_id',
                        $validated['project_id']
                    )
                    ->exists();

            if (!$strategyExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_strategy_id' =>
                            'The selected Procurement Strategy does not belong to the selected Project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Automatically Generate Plan Number
        |--------------------------------------------------------------------------
        */

        $planNumber = $this->generatePlanNumber(
            $validated['procurement_year']
        );


        /*
        |--------------------------------------------------------------------------
        | Create Procurement Plan
        |--------------------------------------------------------------------------
        */

        $plan = ProcurementPlan::create([

            'project_id' =>
                $validated['project_id'],

            'procurement_strategy_id' =>
                $validated['procurement_strategy_id']
                ?? null,

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
                $validated['procurement_objective']
                ?? null,

            'planned_start_date' =>
                $validated['planned_start_date']
                ?? null,

            'planned_completion_date' =>
                $validated['planned_completion_date']
                ?? null,

            'total_estimated_value' =>
                $validated['total_estimated_value']
                ?? null,

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
                'admin.procurement.plans.show',
                $plan
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
        ProcurementPlan $procurementPlan
    ): View {

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
        ]);

        return view(
            'procurement.plans.show',
            compact('procurementPlan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementPlan $procurementPlan
    ): View {

        if (
            $procurementPlan->status === 'Approved'
        ) {

            abort(
                403,
                'Approved Procurement Plans cannot be edited directly.'
            );
        }

        $projects = Project::query()
            ->orderBy('project_name')
            ->get([
                'id',
                'project_name',
                'project_code',
            ]);

        $procurementStrategies =
            ProjectProcurementStrategy::query()
                ->where(
                    'project_id',
                    $procurementPlan->project_id
                )
                ->latest('id')
                ->get();

        return view(
            'procurement.plans.edit',
            compact(
                'procurementPlan',
                'projects',
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
        ProcurementPlan $procurementPlan
    ): RedirectResponse {

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

            'project_id' => [
                'required',
                'integer',
                'exists:projects,id',
            ],

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
        | Strategy must belong to selected Project
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
                        $validated['procurement_strategy_id']
                    )
                    ->where(
                        'project_id',
                        $validated['project_id']
                    )
                    ->exists();

            if (!$strategyExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_strategy_id' =>
                            'The selected Procurement Strategy does not belong to the selected Project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Plan Number
        |--------------------------------------------------------------------------
        |
        | Existing plan number is NOT changed during edit.
        |
        */

        $procurementPlan->update([

            'project_id' =>
                $validated['project_id'],

            'procurement_strategy_id' =>
                $validated['procurement_strategy_id']
                ?? null,

            'plan_title' =>
                $validated['plan_title'],

            'procurement_year' =>
                $validated['procurement_year'],

            'description' =>
                $validated['description']
                ?? null,

            'procurement_objective' =>
                $validated['procurement_objective']
                ?? null,

            'planned_start_date' =>
                $validated['planned_start_date']
                ?? null,

            'planned_completion_date' =>
                $validated['planned_completion_date']
                ?? null,

            'total_estimated_value' =>
                $validated['total_estimated_value']
                ?? null,

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
                'admin.procurement.plans.show',
                $procurementPlan
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
        ProcurementPlan $procurementPlan
    ): RedirectResponse {

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
                'admin.procurement.plans.index'
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
                ->map(function ($strategy) {

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
                            ('Strategy #' . $strategy->id),
                    ];
                });

        return response()->json(
            $strategies
        );
    }
}