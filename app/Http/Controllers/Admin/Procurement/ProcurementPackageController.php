<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPackage;
use App\Models\ProcurementPlan;
use App\Models\ProjectBudget;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementPackageController extends Controller
{
    /**
     * Display all procurement packages.
     */
    public function index(Request $request): View
    {
        $query = ProcurementPackage::query()
            ->with([
                'procurementPlan.project',
                'projectBudget',
                'responsibleUser',
            ]);

        if ($request->filled('procurement_plan_id')) {

            $query->where(
                'procurement_plan_id',
                $request->procurement_plan_id
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('package_type')) {

            $query->where(
                'package_type',
                $request->package_type
            );
        }

        if ($request->filled('procurement_method')) {

            $query->where(
                'procurement_method',
                $request->procurement_method
            );
        }

        $packages = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $procurementPlans = ProcurementPlan::query()
            ->with('project')
            ->orderBy('plan_number')
            ->get();

        return view(
            'procurement.packages.index',
            compact(
                'packages',
                'procurementPlans'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Request $request): View
    {
        $procurementPlans = ProcurementPlan::query()
            ->with('project')
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Submitted',
                    'Under Review',
                    'Revision Required',
                    'Approved',
                ]
            )
            ->orderBy('plan_number')
            ->get();

        $selectedPlanId = $request->integer(
            'procurement_plan_id'
        );

        /*
        |--------------------------------------------------------------------------
        | Budgets
        |--------------------------------------------------------------------------
        */

        $budgets = collect();

        if ($selectedPlanId) {

            $selectedPlan = $procurementPlans->firstWhere(
                'id',
                $selectedPlanId
            );

            if ($selectedPlan && $selectedPlan->project_id) {

                $budgets = ProjectBudget::query()
                    ->where(
                        'project_id',
                        $selectedPlan->project_id
                    )
                    ->orderByDesc('version_number')
                    ->orderBy('title')
                    ->get();
            }
        }

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'procurement.packages.create',
            compact(
                'procurementPlans',
                'budgets',
                'users',
                'selectedPlanId'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Package Number
    |--------------------------------------------------------------------------
    |
    | Format:
    |
    | PKG-2026-0001
    | PKG-2026-0002
    | PKG-2026-0003
    |
    */

    private function generatePackageNumber(
        ProcurementPlan $plan
    ): string {

        $year = $plan->procurement_year;

        $prefix = 'PKG-' . $year . '-';

        $lastPackage = ProcurementPackage::query()
            ->where(
                'procurement_plan_id',
                $plan->id
            )
            ->where(
                'package_number',
                'like',
                $prefix . '%'
            )
            ->orderByDesc('id')
            ->first();

        if (!$lastPackage) {

            $nextNumber = 1;

        } else {

            $lastNumber = (int) substr(
                $lastPackage->package_number,
                strlen($prefix)
            );

            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad(
            $nextNumber,
            4,
            '0',
            STR_PAD_LEFT
        );
    }


    /**
     * Store package.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            'procurement_plan_id' => [
                'required',
                'integer',
                'exists:procurement_plans,id',
            ],

            'project_budget_id' => [
                'required',
                'integer',
                'exists:project_budgets,id',
            ],

            'package_title' => [
                'required',
                'string',
                'max:255',
            ],

            'package_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'scope_of_work' => [
                'nullable',
                'string',
            ],

            'estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'planned_tender_date' => [
                'nullable',
                'date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_tender_date',
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

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Load Procurement Plan
        |--------------------------------------------------------------------------
        */

        $plan = ProcurementPlan::query()
            ->with('project')
            ->findOrFail(
                $validated['procurement_plan_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Budget belongs to Plan's Project
        |--------------------------------------------------------------------------
        */

        $budgetBelongsToProject = ProjectBudget::query()
            ->where(
                'id',
                $validated['project_budget_id']
            )
            ->where(
                'project_id',
                $plan->project_id
            )
            ->exists();

        if (!$budgetBelongsToProject) {

            return back()
                ->withInput()
                ->withErrors([
                    'project_budget_id' =>
                        'The selected budget does not belong to the project of the selected Procurement Plan.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Package Number Automatically
        |--------------------------------------------------------------------------
        */

        $packageNumber = $this->generatePackageNumber(
            $plan
        );


        /*
        |--------------------------------------------------------------------------
        | Create Package
        |--------------------------------------------------------------------------
        */

        $package = ProcurementPackage::create([

            'procurement_plan_id' =>
                $plan->id,

            'project_budget_id' =>
                $validated['project_budget_id'],

            'package_number' =>
                $packageNumber,

            'package_title' =>
                $validated['package_title'],

            'package_type' =>
                $validated['package_type'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'scope_of_work' =>
                $validated['scope_of_work'] ?? null,

            'estimated_value' =>
                $validated['estimated_value'] ?? 0,

            'currency' =>
                $validated['currency'],

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'planned_tender_date' =>
                $validated['planned_tender_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'planned_start_date' =>
                $validated['planned_start_date'] ?? null,

            'planned_completion_date' =>
                $validated['planned_completion_date'] ?? null,

            'status' =>
                'Draft',

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'responsible_name' =>
                $validated['responsible_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.packages.show',
                $package
            )
            ->with(
                'success',
                'Procurement Package ' .
                $package->package_number .
                ' created successfully.'
            );
    }


    /**
     * Display package.
     */
    public function show(
        ProcurementPackage $procurementPackage
    ): View {

        $procurementPackage->load([
            'procurementPlan.project',
            'procurementPlan.procurementStrategy',
            'projectBudget',
            'responsibleUser',
            'tenders',
            'creator',
            'updater',
        ]);

        return view(
            'procurement.packages.show',
            compact(
                'procurementPackage'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        ProcurementPackage $procurementPackage
    ): View {

        if (
            !in_array(
                $procurementPackage->status,
                [
                    'Draft',
                    'Planned',
                ],
                true
            )
        ) {

            abort(
                403,
                'This Procurement Package can no longer be edited directly.'
            );
        }

        $procurementPlans = ProcurementPlan::query()
            ->with('project')
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Submitted',
                    'Under Review',
                    'Revision Required',
                    'Approved',
                ]
            )
            ->orderBy('plan_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Load budgets for current plan
        |--------------------------------------------------------------------------
        */

        $budgets = collect();

        $selectedPlan = $procurementPlans->firstWhere(
            'id',
            $procurementPackage->procurement_plan_id
        );

        if ($selectedPlan && $selectedPlan->project_id) {

            $budgets = ProjectBudget::query()
                ->where(
                    'project_id',
                    $selectedPlan->project_id
                )
                ->orderByDesc('version_number')
                ->orderBy('title')
                ->get();
        }


        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'procurement.packages.edit',
            compact(
                'procurementPackage',
                'procurementPlans',
                'budgets',
                'users'
            )
        );
    }


    /**
     * Update package.
     */
    public function update(
        Request $request,
        ProcurementPackage $procurementPackage
    ): RedirectResponse {

        if (
            !in_array(
                $procurementPackage->status,
                [
                    'Draft',
                    'Planned',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This Procurement Package cannot be edited in its current status.'
                );
        }


        $validated = $request->validate([

            'procurement_plan_id' => [
                'required',
                'integer',
                'exists:procurement_plans,id',
            ],

            'project_budget_id' => [
                'required',
                'integer',
                'exists:project_budgets,id',
            ],

            'package_title' => [
                'required',
                'string',
                'max:255',
            ],

            'package_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'scope_of_work' => [
                'nullable',
                'string',
            ],

            'estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'planned_tender_date' => [
                'nullable',
                'date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_tender_date',
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

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Budget belongs to selected Plan's Project
        |--------------------------------------------------------------------------
        */

        $plan = ProcurementPlan::query()
            ->with('project')
            ->findOrFail(
                $validated['procurement_plan_id']
            );

        $budgetBelongsToProject = ProjectBudget::query()
            ->where(
                'id',
                $validated['project_budget_id']
            )
            ->where(
                'project_id',
                $plan->project_id
            )
            ->exists();

        if (!$budgetBelongsToProject) {

            return back()
                ->withInput()
                ->withErrors([
                    'project_budget_id' =>
                        'The selected budget does not belong to the project of the selected Procurement Plan.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Package Number is NOT updated.
        |--------------------------------------------------------------------------
        */

        $procurementPackage->update([

            'procurement_plan_id' =>
                $validated['procurement_plan_id'],

            'project_budget_id' =>
                $validated['project_budget_id'],

            'package_title' =>
                $validated['package_title'],

            'package_type' =>
                $validated['package_type'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'scope_of_work' =>
                $validated['scope_of_work'] ?? null,

            'estimated_value' =>
                $validated['estimated_value'] ?? 0,

            'currency' =>
                $validated['currency'],

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'planned_tender_date' =>
                $validated['planned_tender_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'planned_start_date' =>
                $validated['planned_start_date'] ?? null,

            'planned_completion_date' =>
                $validated['planned_completion_date'] ?? null,

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'responsible_name' =>
                $validated['responsible_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.packages.show',
                $procurementPackage
            )
            ->with(
                'success',
                'Procurement Package updated successfully.'
            );
    }


    /**
     * Delete package.
     */
    public function destroy(
        ProcurementPackage $procurementPackage
    ): RedirectResponse {

        if (
            $procurementPackage->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Procurement Packages can be deleted.'
                );
        }

        $procurementPackage->delete();

        return redirect()
            ->route(
                'admin.procurement.packages.index'
            )
            ->with(
                'success',
                'Procurement Package deleted successfully.'
            );
    }


    /**
     * Get budgets for Procurement Plan.
     */
    public function getBudgetsByPlan(
        ProcurementPlan $procurementPlan
    ) {

        $budgets = ProjectBudget::query()
            ->where(
                'project_id',
                $procurementPlan->project_id
            )
            ->orderByDesc('version_number')
            ->orderBy('title')
            ->get([
                'id',
                'budget_number',
                'title',
                'version_number',
                'currency',
                'total_budget',
                'status',
            ]);

        return response()->json(
            $budgets
        );
    }
}