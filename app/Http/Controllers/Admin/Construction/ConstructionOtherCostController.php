<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionOtherCost;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetItem;

class ConstructionOtherCostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $costs =
            ConstructionOtherCost::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                ])
                ->orderByDesc(
                    'cost_date'
                )
                ->orderByDesc('id')
                ->get();


        $summary = [

            'total' =>
                $costs->count(),

            'approved' =>
                $costs
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->sum('amount'),

            'draft' =>
                $costs
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->sum('amount'),

            'rejected' =>
                $costs
                    ->where(
                        'status',
                        'Rejected'
                    )
                    ->sum('amount'),
        ];


        return view(
            'construction.other-costs.index',
            compact(
                'project',
                'costs',
                'summary'
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
        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->with([
                'projectBudget',
                'budgetItem',
            ])
            ->orderBy('work_order_number')
            ->get();

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderBy('budget_number')
            ->get();

        $budgetItems = ProjectBudgetItem::query()
            ->whereIn('project_budget_id', $budgets->pluck('id'))
            ->orderBy('item_code')
            ->orderBy('item_name')
            ->get();

        return view('construction.other-costs.create', [
            'project' => $project,
            'workOrders' => $workOrders,
            'budgets' => $budgets,
            'budgetItems' => $budgetItems,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
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

            'cost_date' => [
                'required',
                'date',
            ],

            'cost_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Approved,Rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {
            $workOrder = null;

            if (!empty($validated['construction_work_order_id'])) {
                $workOrder = ConstructionWorkOrder::query()
                    ->where('id', $validated['construction_work_order_id'])
                    ->where('project_id', $project->id)
                    ->with([
                        'projectBudget',
                        'budgetItem',
                    ])
                    ->first();

                if (!$workOrder) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'construction_work_order_id' =>
                                'The selected Work Order does not belong to this project.',
                        ]);
                }
            }

            $this->validateBudgetLinkage(
                $request,
                $project,
                $validated,
                $workOrder
            );

            $validated['project_id'] = $project->id;
            $validated['cost_number'] = $this->generateCostNumber($project);
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();

            ConstructionOtherCost::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.projects.construction.other-costs.index', $project)
                ->with('success', 'Other construction cost created successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionOtherCost $cost
    ): View {

        $this->validateProject(
            $project,
            $cost
        );


        $cost->load([
            'workOrder',
        ]);


        return view(
            'construction.other-costs.show',
            compact(
                'project',
                'cost'
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
        ConstructionOtherCost $otherCost
    ): View {
        if ((int) $otherCost->project_id !== (int) $project->id) {
            abort(404);
        }

        if (!in_array($otherCost->status, ['Draft', 'Rejected'])) {
            return redirect()
                ->route(
                    'admin.projects.construction.other-costs.show',
                    [$project, $otherCost]
                )
                ->withErrors([
                    'error' => 'Only Draft or Rejected costs can be edited.',
                ]);
        }

        $otherCost->load([
            'projectBudget',
            'budgetItem',
            'workOrder.projectBudget',
            'workOrder.budgetItem',
        ]);

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->with([
                'projectBudget',
                'budgetItem',
            ])
            ->orderBy('work_order_number')
            ->get();

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderBy('budget_number')
            ->get();

        $budgetItems = ProjectBudgetItem::query()
            ->whereIn('project_budget_id', $budgets->pluck('id'))
            ->orderBy('item_code')
            ->orderBy('item_name')
            ->get();

        return view('construction.other-costs.edit', [
            'project' => $project,
            'cost' => $otherCost,
            'workOrders' => $workOrders,
            'budgets' => $budgets,
            'budgetItems' => $budgetItems,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionOtherCost $otherCost
    ): RedirectResponse {
        if ((int) $otherCost->project_id !== (int) $project->id) {
            abort(404);
        }

        if (!in_array($otherCost->status, ['Draft', 'Rejected'])) {
            return back()->withErrors([
                'error' => 'Only Draft or Rejected costs can be edited.',
            ]);
        }

        $validated = $request->validate([
            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
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

            'cost_date' => [
                'required',
                'date',
            ],

            'cost_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Approved,Rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {
            $workOrder = null;

            if (!empty($validated['construction_work_order_id'])) {
                $workOrder = ConstructionWorkOrder::query()
                    ->where('id', $validated['construction_work_order_id'])
                    ->where('project_id', $project->id)
                    ->with([
                        'projectBudget',
                        'budgetItem',
                    ])
                    ->first();

                if (!$workOrder) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'construction_work_order_id' =>
                                'The selected Work Order does not belong to this project.',
                        ]);
                }
            }

            $this->validateBudgetLinkage(
                $request,
                $project,
                $validated,
                $workOrder
            );

            unset(
                $validated['cost_number'],
                $validated['project_id'],
                $validated['created_by'],
                $validated['created_at']
            );

            $validated['updated_by'] = auth()->id();

            $otherCost->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.projects.construction.other-costs.index', $project)
                ->with('success', 'Other construction cost updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionOtherCost $cost
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $cost
        );


        if (
            !in_array(
                $cost->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Rejected costs can be deleted.'
                );
        }


        $number =
            $cost->cost_number;


        $cost->delete();


        return redirect()
            ->route(
                'admin.projects.construction.other-costs.index',
                $project
            )
            ->with(
                'success',
                'Other Cost '
                . $number
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateProject(
        Project $project,
        ConstructionOtherCost $cost
    ): void {

        if (
            (int) $cost->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Other Cost does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    protected function generateCostNumber(): string
    {
        do {

            $number =
                'OCOST-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            ConstructionOtherCost::query()
                ->where(
                    'cost_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }

    private function validateBudgetLinkage(
        Request $request,
        Project $project,
        array &$validated,
        ?ConstructionWorkOrder $workOrder = null
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Work Order Based Budget Inheritance
        |--------------------------------------------------------------------------
        */

        if ($workOrder) {
            if ((int) $workOrder->project_id !== (int) $project->id) {
                abort(422, 'The selected Work Order does not belong to this project.');
            }

            if (
                empty($workOrder->project_budget_id) ||
                empty($workOrder->project_budget_item_id)
            ) {
                abort(
                    422,
                    'The selected Work Order is not linked to a Development Budget Item.'
                );
            }

            $validated['project_budget_id'] =
                $workOrder->project_budget_id;

            $validated['project_budget_item_id'] =
                $workOrder->project_budget_item_id;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Manual Budget Selection
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['project_budget_id']) ||
            empty($validated['project_budget_item_id'])
        ) {
            abort(
                422,
                'Please select both Development Budget and Development Budget Item.'
            );
        }

        $budget = ProjectBudget::query()
            ->where('id', $validated['project_budget_id'])
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->first();

        if (!$budget) {
            abort(
                422,
                'The selected Development Budget is invalid for this project.'
            );
        }

        $budgetItem = ProjectBudgetItem::query()
            ->where('id', $validated['project_budget_item_id'])
            ->where('project_budget_id', $budget->id)
            ->first();

        if (!$budgetItem) {
            abort(
                422,
                'The selected Development Budget Item does not belong to the selected budget.'
            );
        }
    }
}