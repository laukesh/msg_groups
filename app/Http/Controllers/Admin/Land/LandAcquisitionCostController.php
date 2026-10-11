<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandAcquisitionCost;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetItem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LandAcquisitionCostController extends Controller
{
    /**
     * Display acquisition costs.
     */
    public function index(Land $land)
    {
        $acquisitionCosts = LandAcquisitionCost::with([
            'projectBudget',
            'budgetItem',
        ])
            ->where('land_id', $land->id)
            ->latest('id')
            ->get();

        $totalCost = $acquisitionCosts->sum('total_amount');

        $paidCost = $acquisitionCosts
            ->where('payment_status', 'Paid')
            ->sum('total_amount');

        $pendingCost = $acquisitionCosts
            ->whereIn('payment_status', [
                'Pending',
                'Partially Paid',
            ])
            ->sum('total_amount');

        return view(
            'land-acquisition.acquisition-costs.index',
            compact(
                'land',
                'acquisitionCosts',
                'totalCost',
                'paidCost',
                'pendingCost'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        $project = Project::where(
            'land_id',
            $land->id
        )->first();

        $budgets = $this->getApprovedBudgetsForLand($land);

        return view(
            'land-acquisition.acquisition-costs.create',
            compact(
                'land',
                'project',
                'budgets'
            )
        );
    }


    /**
     * Store acquisition cost.
     */
    public function store(Request $request, Land $land)
    {
        $validated = $request->validate([

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

            'cost_category' => [
                'required',
                'string',
                'max:255',
            ],

            'cost_description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'cost_date' => [
                'required',
                'date',
            ],

            'payment_status' => [
                'required',
                'in:Pending,Partially Paid,Paid',
            ],

            'paid_date' => [
                'nullable',
                'date',
            ],

            'reference_number' => [
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
        | Calculate total amount
        |--------------------------------------------------------------------------
        */
        $amount = (float) $validated['amount'];

        $taxAmount = (float) (
            $validated['tax_amount'] ?? 0
        );

        $totalAmount = $amount + $taxAmount;


        /*
        |--------------------------------------------------------------------------
        | Validate budget allocation
        |--------------------------------------------------------------------------
        */
        $this->validateBudgetAllocation(
            $land,
            $validated['project_budget_id'] ?? null,
            $validated['project_budget_item_id'] ?? null,
            $totalAmount,
            $validated['currency']
        );


        /*
        |--------------------------------------------------------------------------
        | Prepare data
        |--------------------------------------------------------------------------
        */
        $validated['land_id'] = $land->id;

        $validated['amount'] = $amount;
        $validated['tax_amount'] = $taxAmount;
        $validated['total_amount'] = $totalAmount;

        $validated['created_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create record
        |--------------------------------------------------------------------------
        */
        LandAcquisitionCost::create($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land->id
            )
            ->with(
                'success',
                'Land acquisition cost added successfully.'
            );
    }


    /**
     * Display acquisition cost.
     */
    public function show(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {
        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );

        $acquisitionCost->load([
            'land',
            'projectBudget',
            'budgetItem',
        ]);

        return view(
            'land-acquisition.acquisition-costs.show',
            compact(
                'land',
                'acquisitionCost'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {
        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );

        $project = Project::where(
            'land_id',
            $land->id
        )->first();

        $budgets = $this->getApprovedBudgetsForLand($land);

        return view(
            'land-acquisition.acquisition-costs.edit',
            compact(
                'land',
                'project',
                'budgets',
                'acquisitionCost'
            )
        );
    }


    /**
     * Update acquisition cost.
     */
    public function update(
        Request $request,
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {
        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );


        $validated = $request->validate([

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

            'cost_category' => [
                'required',
                'string',
                'max:255',
            ],

            'cost_description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'cost_date' => [
                'required',
                'date',
            ],

            'payment_status' => [
                'required',
                'in:Pending,Partially Paid,Paid',
            ],

            'paid_date' => [
                'nullable',
                'date',
            ],

            'reference_number' => [
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
        | Calculate total amount
        |--------------------------------------------------------------------------
        */
        $amount = (float) $validated['amount'];

        $taxAmount = (float) (
            $validated['tax_amount'] ?? 0
        );

        $totalAmount = $amount + $taxAmount;


        /*
        |--------------------------------------------------------------------------
        | Validate budget allocation
        |--------------------------------------------------------------------------
        | Exclude the current record from existing utilization.
        |--------------------------------------------------------------------------
        */
        $this->validateBudgetAllocation(
            $land,
            $validated['project_budget_id'] ?? null,
            $validated['project_budget_item_id'] ?? null,
            $totalAmount,
            $validated['currency'],
            $acquisitionCost->id
        );


        /*
        |--------------------------------------------------------------------------
        | Prepare data
        |--------------------------------------------------------------------------
        */
        $validated['amount'] = $amount;
        $validated['tax_amount'] = $taxAmount;
        $validated['total_amount'] = $totalAmount;

        $validated['updated_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update record
        |--------------------------------------------------------------------------
        */
        $acquisitionCost->update($validated);


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land->id
            )
            ->with(
                'success',
                'Land acquisition cost updated successfully.'
            );
    }


    /**
     * Delete acquisition cost.
     */
    public function destroy(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {
        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );

        $acquisitionCost->delete();

        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Acquisition cost deleted successfully.'
            );
    }


    /**
     * Validate that acquisition cost belongs to the selected land.
     */
    private function validateCostBelongsToLand(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ): void {
        abort_unless(
            (int) $acquisitionCost->land_id === (int) $land->id,
            404
        );
    }


    /**
     * Get approved development budgets for the land's project.
     */
    private function getApprovedBudgetsForLand(Land $land)
    {
        $project = Project::where(
            'land_id',
            $land->id
        )->first();

        if (!$project) {
            return collect();
        }

        return ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->with([
                'items' => function ($query) {
                    $query
                        ->orderBy('sequence')
                        ->orderBy('item_name');
                },
            ])
            ->orderByDesc('id')
            ->get();
    }


    /**
     * Validate the selected development budget and budget item.
     */
    private function validateBudgetAllocation(
        Land $land,
        ?int $projectBudgetId,
        ?int $projectBudgetItemId,
        float $totalAmount,
        string $currency,
        ?int $ignoreCostId = null
    ): void {

        /*
        |--------------------------------------------------------------------------
        | No budget or item selected
        |--------------------------------------------------------------------------
        */
        if (!$projectBudgetId && !$projectBudgetItemId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Budget item requires a budget
        |--------------------------------------------------------------------------
        */
        if ($projectBudgetItemId && !$projectBudgetId) {
            throw ValidationException::withMessages([
                'project_budget_item_id' =>
                    'Please select a Development Budget before selecting a Budget Item.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find project associated with the land
        |--------------------------------------------------------------------------
        */
        $project = Project::where(
            'land_id',
            $land->id
        )->first();

        if (!$project) {
            throw ValidationException::withMessages([
                'project_budget_id' =>
                    'No project is associated with this land.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate budget belongs to the project and is approved
        |--------------------------------------------------------------------------
        */
        $budget = ProjectBudget::query()
            ->where('id', $projectBudgetId)
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->first();

        if (!$budget) {
            throw ValidationException::withMessages([
                'project_budget_id' =>
                    'The selected budget is invalid or is not approved for this project.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate currency
        |--------------------------------------------------------------------------
        */
        if (
            strtoupper((string) $budget->currency)
            !== strtoupper((string) $currency)
        ) {
            throw ValidationException::withMessages([
                'currency' =>
                    'The acquisition cost currency must match the development budget currency.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | If only budget is selected, no item utilization validation needed
        |--------------------------------------------------------------------------
        */
        if (!$projectBudgetItemId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate budget item belongs to selected budget
        |--------------------------------------------------------------------------
        */
        $budgetItem = ProjectBudgetItem::query()
            ->where('id', $projectBudgetItemId)
            ->where('project_budget_id', $projectBudgetId)
            ->first();

        if (!$budgetItem) {
            throw ValidationException::withMessages([
                'project_budget_item_id' =>
                    'The selected budget item does not belong to the selected budget.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get existing land acquisition costs
        |--------------------------------------------------------------------------
        */
        $existingLandCostQuery = LandAcquisitionCost::query()
            ->where(
                'project_budget_item_id',
                $projectBudgetItemId
            );


        /*
        |--------------------------------------------------------------------------
        | Exclude current record during edit
        |--------------------------------------------------------------------------
        */
        if ($ignoreCostId) {
            $existingLandCostQuery
                ->where('id', '!=', $ignoreCostId);
        }


        $existingLandCostAmount = (float) $existingLandCostQuery
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Calculate other committed amounts
        |--------------------------------------------------------------------------
        */
        $otherCommittedAmount =
            (float) ($budgetItem->procurement_amount ?? 0)
            + (float) ($budgetItem->contract_amount ?? 0)
            + (float) ($budgetItem->work_order_amount ?? 0)
            + (float) ($budgetItem->variation_amount ?? 0)
            + (float) ($budgetItem->other_cost_amount ?? 0)
            + (float) ($budgetItem->design_cost_amount ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Calculate total committed amount
        |--------------------------------------------------------------------------
        */
        $totalAlreadyCommitted =
            $otherCommittedAmount
            + $existingLandCostAmount;


        /*
        |--------------------------------------------------------------------------
        | Calculate remaining budget
        |--------------------------------------------------------------------------
        */
        $remainingAmount =
            (float) $budgetItem->estimated_amount
            - $totalAlreadyCommitted;


        /*
        |--------------------------------------------------------------------------
        | Prevent over-budget allocation
        |--------------------------------------------------------------------------
        */
        if ($totalAmount > $remainingAmount) {
            throw ValidationException::withMessages([
                'amount' => sprintf(
                    'This cost exceeds the available budget. Available amount: %s %s.',
                    $budget->currency,
                    number_format(
                        max(0, $remainingAmount),
                        2
                    )
                ),
            ]);
        }
    }
}