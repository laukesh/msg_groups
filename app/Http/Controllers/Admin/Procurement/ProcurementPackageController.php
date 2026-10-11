<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetItem;
use App\Models\ProcurementPackage;
use App\Models\ProcurementPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcurementPackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, Project $project)
    {
        $query = ProcurementPackage::query()
            ->with([
                'project',
                'procurementPlan',
                'projectBudget',
                'budgetItem.category',
            ])
            ->where('project_id', $project->id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('package_number', 'like', "%{$search}%")
                    ->orWhere('package_title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('budgetItem', function ($budgetItemQuery) use ($search) {
                        $budgetItemQuery
                            ->where('item_code', 'like', "%{$search}%")
                            ->orWhere('item_name', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Procurement Plan Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('procurement_plan_id')) {
            $query->where(
                'procurement_plan_id',
                $request->procurement_plan_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('package_status')) {
            $query->where(
                'package_status',
                $request->package_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Budget Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('project_budget_id')) {
            $query->where(
                'project_budget_id',
                $request->project_budget_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Budget Item Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('project_budget_item_id')) {
            $query->where(
                'project_budget_item_id',
                $request->project_budget_item_id
            );
        }

        $packages = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Project Procurement Plans
        |--------------------------------------------------------------------------
        */

        $procurementPlans = ProcurementPlan::query()
            ->where('project_id', $project->id)
            ->orderBy('plan_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Project Budgets
        |--------------------------------------------------------------------------
        */

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->orderByDesc('version_number')
            ->orderByDesc('id')
            ->get();

        return view(
            'procurement.packages.index',
            compact(
                'project',
                'packages',
                'procurementPlans',
                'budgets'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project)
    {
        $procurementPlans = ProcurementPlan::query()
            ->where('project_id', $project->id)
            ->orderBy('plan_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Load Approved Project Budgets
        |--------------------------------------------------------------------------
        */

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderByDesc('version_number')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Load Budget Items from Approved Project Budgets
        |--------------------------------------------------------------------------
        */

        $budgetItems = ProjectBudgetItem::query()
            ->whereHas('budget', function ($query) use ($project) {
                $query
                    ->where('project_id', $project->id)
                    ->where('status', 'Approved');
            })
            ->with([
                'budget',
                'category',
                'procurementPackages',
                'contracts',
                'workOrders',
                'variations',
                'otherCosts',
                'landCosts',
                'designCostImpacts',
            ])
            ->orderBy('item_code')
            ->get();

        return view(
            'procurement.packages.create',
            compact(
                'project',
                'procurementPlans',
                'budgets',
                'budgetItems'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'procurement_plan_id' => [
                'required',
                'integer',
                'exists:procurement_plans,id',
            ],

            'project_budget_id' => [
                'nullable',
                'integer',
                'exists:project_budgets,id',
            ],

            'project_budget_item_id' => [
                'nullable',
                'integer',
                'exists:project_budget_items,id',
            ],

            'package_title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'procurement_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'estimated_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'budget_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tender_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tender_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'emd_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'performance_security' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_publish_date' => [
                'nullable',
                'date',
            ],

            'planned_bid_submission_date' => [
                'nullable',
                'date',
            ],

            'planned_opening_date' => [
                'nullable',
                'date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
            ],

            'package_status' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Procurement Plan Belongs To Current Project
        |--------------------------------------------------------------------------
        */

        $plan = ProcurementPlan::query()
            ->where('id', $validated['procurement_plan_id'])
            ->where('project_id', $project->id)
            ->first();

        if (!$plan) {
            return back()
                ->withInput()
                ->withErrors([
                    'procurement_plan_id' =>
                        'The selected procurement plan does not belong to this project.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Budget Belongs To Current Project
        |--------------------------------------------------------------------------
        */

        $budget = null;

        if (!empty($validated['project_budget_id'])) {
            $budget = ProjectBudget::query()
                ->where('id', $validated['project_budget_id'])
                ->where('project_id', $project->id)
                ->where('status', 'Approved')
                ->first();

            if (!$budget) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_id' =>
                            'The selected budget is invalid or does not belong to this project.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Budget Item
        |--------------------------------------------------------------------------
        */

        $budgetItem = null;

        if (!empty($validated['project_budget_item_id'])) {
            $budgetItem = ProjectBudgetItem::query()
                ->where('id', $validated['project_budget_item_id'])
                ->whereHas('budget', function ($query) use ($project) {
                    $query
                        ->where('project_id', $project->id)
                        ->where('status', 'Approved');
                })
                ->first();

            if (!$budgetItem) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item is invalid or does not belong to this project.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Ensure Budget Item Belongs To Selected Budget
            |--------------------------------------------------------------------------
            */

            if (
                !empty($validated['project_budget_id']) &&
                (int) $budgetItem->project_budget_id !==
                (int) $validated['project_budget_id']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item does not belong to the selected budget.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Automatically Set Budget From Budget Item
            |--------------------------------------------------------------------------
            */

            if (empty($validated['project_budget_id'])) {
                $validated['project_budget_id'] =
                    $budgetItem->project_budget_id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Package Number
        |--------------------------------------------------------------------------
        */

        $packageNumber = $this->generatePackageNumber(
            $project,
            $plan
        );

        /*
        |--------------------------------------------------------------------------
        | Create Procurement Package
        |--------------------------------------------------------------------------
        */

        $package = ProcurementPackage::create([
            'project_id' => $project->id,

            'procurement_plan_id' =>
                $validated['procurement_plan_id'],

            'project_budget_id' =>
                $validated['project_budget_id'] ?? null,

            'project_budget_item_id' =>
                $validated['project_budget_item_id'] ?? null,

            'package_number' =>
                $packageNumber,

            'package_title' =>
                $validated['package_title'],

            'description' =>
                $validated['description'] ?? null,

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'procurement_type' =>
                $validated['procurement_type'] ?? null,

            'estimated_cost' =>
                $validated['estimated_cost'] ?? null,

            'budget_amount' =>
                $validated['budget_amount'] ?? null,

            'tender_type' =>
                $validated['tender_type'] ?? null,

            'tender_fee' =>
                $validated['tender_fee'] ?? null,

            'emd_amount' =>
                $validated['emd_amount'] ?? null,

            'performance_security' =>
                $validated['performance_security'] ?? null,

            'planned_publish_date' =>
                $validated['planned_publish_date'] ?? null,

            'planned_bid_submission_date' =>
                $validated['planned_bid_submission_date'] ?? null,

            'planned_opening_date' =>
                $validated['planned_opening_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'package_status' =>
                $validated['package_status'] ?? 'Draft',

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.procurement.packages.show',
                [
                    'project' => $project->id,
                    'procurementPackage' => $package->id,
                ]
            )
            ->with(
                'success',
                'Procurement package created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementPackage $procurementPackage
    ) {
        $this->ensurePackageBelongsToProject(
            $project,
            $procurementPackage
        );

        $procurementPackage->load([
            'project',
            'procurementPlan',
            'projectBudget',
            'budgetItem.category',
            'budgetItem.budget',
            'tenders',
        ]);

        return view(
            'procurement.packages.show',
            compact(
                'project',
                'procurementPackage'
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
        ProcurementPackage $procurementPackage
    ) {
        $this->ensurePackageBelongsToProject(
            $project,
            $procurementPackage
        );

        if (
            !in_array(
                $procurementPackage->package_status,
                ['Draft', 'Planned']
            )
        ) {
            return redirect()
                ->route(
                    'admin.projects.procurement.packages.show',
                    [
                        'project' => $project->id,
                        'procurementPackage' =>
                            $procurementPackage->id,
                    ]
                )
                ->with(
                    'error',
                    'This package cannot be edited in its current status.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Procurement Plans
        |--------------------------------------------------------------------------
        */

        $procurementPlans = ProcurementPlan::query()
            ->where('project_id', $project->id)
            ->orderBy('plan_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Approved Project Budgets
        |--------------------------------------------------------------------------
        */

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderByDesc('version_number')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Budget Items
        |--------------------------------------------------------------------------
        */

        $budgetItems = ProjectBudgetItem::query()
            ->whereHas('budget', function ($query) use ($project) {
                $query
                    ->where('project_id', $project->id)
                    ->where('status', 'Approved');
            })
            ->with([
                'budget',
                'category',
                'procurementPackages',
                'contracts',
                'workOrders',
                'variations',
                'otherCosts',
                'landCosts',
                'designCostImpacts',
            ])
            ->orderBy('item_code')
            ->get();

        $procurementPackage->load([
            'projectBudget',
            'budgetItem.category',
            'budgetItem.budget',
        ]);

        return view(
            'procurement.packages.edit',
            compact(
                'project',
                'procurementPackage',
                'procurementPlans',
                'budgets',
                'budgetItems'
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
        ProcurementPackage $procurementPackage
    ) {
        $this->ensurePackageBelongsToProject(
            $project,
            $procurementPackage
        );

        if (
            !in_array(
                $procurementPackage->package_status,
                ['Draft', 'Planned']
            )
        ) {
            return back()
                ->with(
                    'error',
                    'This package cannot be updated in its current status.'
                );
        }

        $validated = $request->validate([
            'procurement_plan_id' => [
                'required',
                'integer',
                'exists:procurement_plans,id',
            ],

            'project_budget_id' => [
                'nullable',
                'integer',
                'exists:project_budgets,id',
            ],

            'project_budget_item_id' => [
                'nullable',
                'integer',
                'exists:project_budget_items,id',
            ],

            'package_title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'procurement_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'estimated_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'budget_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tender_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tender_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'emd_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'performance_security' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_publish_date' => [
                'nullable',
                'date',
            ],

            'planned_bid_submission_date' => [
                'nullable',
                'date',
            ],

            'planned_opening_date' => [
                'nullable',
                'date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
            ],

            'package_status' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Procurement Plan
        |--------------------------------------------------------------------------
        */

        $plan = ProcurementPlan::query()
            ->where('id', $validated['procurement_plan_id'])
            ->where('project_id', $project->id)
            ->first();

        if (!$plan) {
            return back()
                ->withInput()
                ->withErrors([
                    'procurement_plan_id' =>
                        'The selected procurement plan does not belong to this project.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Budget
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['project_budget_id'])) {
            $budget = ProjectBudget::query()
                ->where('id', $validated['project_budget_id'])
                ->where('project_id', $project->id)
                ->where('status', 'Approved')
                ->first();

            if (!$budget) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_id' =>
                            'The selected budget is invalid or does not belong to this project.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Budget Item
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['project_budget_item_id'])) {
            $budgetItem = ProjectBudgetItem::query()
                ->where('id', $validated['project_budget_item_id'])
                ->whereHas('budget', function ($query) use ($project) {
                    $query
                        ->where('project_id', $project->id)
                        ->where('status', 'Approved');
                })
                ->first();

            if (!$budgetItem) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item is invalid or does not belong to this project.',
                    ]);
            }

            if (
                !empty($validated['project_budget_id']) &&
                (int) $budgetItem->project_budget_id !==
                (int) $validated['project_budget_id']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item does not belong to the selected budget.',
                    ]);
            }

            if (empty($validated['project_budget_id'])) {
                $validated['project_budget_id'] =
                    $budgetItem->project_budget_id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Package
        |--------------------------------------------------------------------------
        */

        $procurementPackage->update([
            'project_id' => $project->id,

            'procurement_plan_id' =>
                $validated['procurement_plan_id'],

            'project_budget_id' =>
                $validated['project_budget_id'] ?? null,

            'project_budget_item_id' =>
                $validated['project_budget_item_id'] ?? null,

            'package_title' =>
                $validated['package_title'],

            'description' =>
                $validated['description'] ?? null,

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'procurement_type' =>
                $validated['procurement_type'] ?? null,

            'estimated_cost' =>
                $validated['estimated_cost'] ?? null,

            'budget_amount' =>
                $validated['budget_amount'] ?? null,

            'tender_type' =>
                $validated['tender_type'] ?? null,

            'tender_fee' =>
                $validated['tender_fee'] ?? null,

            'emd_amount' =>
                $validated['emd_amount'] ?? null,

            'performance_security' =>
                $validated['performance_security'] ?? null,

            'planned_publish_date' =>
                $validated['planned_publish_date'] ?? null,

            'planned_bid_submission_date' =>
                $validated['planned_bid_submission_date'] ?? null,

            'planned_opening_date' =>
                $validated['planned_opening_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'package_status' =>
                $validated['package_status']
                ?? $procurementPackage->package_status,

            'updated_by' =>
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.procurement.packages.show',
                [
                    'project' => $project->id,
                    'procurementPackage' =>
                        $procurementPackage->id,
                ]
            )
            ->with(
                'success',
                'Procurement package updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProcurementPackage $procurementPackage
    ) {
        $this->ensurePackageBelongsToProject(
            $project,
            $procurementPackage
        );

        if ($procurementPackage->package_status !== 'Draft') {
            return back()
                ->with(
                    'error',
                    'Only draft packages can be deleted.'
                );
        }

        $procurementPackage->delete();

        return redirect()
            ->route(
                'admin.projects.procurement.packages.index',
                [
                    'project' => $project->id,
                ]
            )
            ->with(
                'success',
                'Procurement package deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GET BUDGETS BY PLAN
    |--------------------------------------------------------------------------
    */

    public function getBudgetsByPlan(
        Project $project,
        ProcurementPlan $procurementPlan
    ) {
        abort_unless(
            (int) $procurementPlan->project_id ===
            (int) $project->id,
            404
        );

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderByDesc('version_number')
            ->orderByDesc('id')
            ->get([
                'id',
                'budget_number',
                'title',
                'version_number',
                'currency',
                'total_budget',
                'status',
            ]);

        return response()->json($budgets);
    }


    /*
    |--------------------------------------------------------------------------
    | GET BUDGET ITEMS BY BUDGET
    |--------------------------------------------------------------------------
    */

    public function getBudgetItems(
        Project $project,
        ProjectBudget $projectBudget
    ) {
        abort_unless(
            (int) $projectBudget->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            $projectBudget->status === 'Approved',
            404
        );

        $budgetItems = ProjectBudgetItem::query()
            ->where('project_budget_id', $projectBudget->id)
            ->with('category')
            ->orderBy('item_code')
            ->get([
                'id',
                'project_budget_id',
                'project_budget_category_id',
                'item_code',
                'item_name',
                'quantity',
                'unit',
                'unit_rate',
                'estimated_amount',
                'cost_type',
            ]);

        return response()->json($budgetItems);
    }


    /*
    |--------------------------------------------------------------------------
    | ENSURE PACKAGE BELONGS TO PROJECT
    |--------------------------------------------------------------------------
    */

    private function ensurePackageBelongsToProject(
        Project $project,
        ProcurementPackage $procurementPackage
    ): void {
        abort_unless(
            (int) $procurementPackage->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE PACKAGE NUMBER
    |--------------------------------------------------------------------------
    */

    private function generatePackageNumber(
        Project $project,
        ProcurementPlan $plan
    ): string {
        $year = $plan->procurement_year ?? now()->year;

        $prefix = 'PKG-' . $year . '-';

        $lastPackage = ProcurementPackage::query()
            ->where('project_id', $project->id)
            ->where('package_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        if (!$lastPackage) {
            return $prefix . '0001';
        }

        $lastNumber = (int) str_replace(
            $prefix,
            '',
            $lastPackage->package_number
        );

        return $prefix . str_pad(
            $lastNumber + 1,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}