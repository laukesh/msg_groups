<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetItem;

class ConstructionWorkOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
                'assignedUser',
            ])
            ->orderByDesc('id')
            ->get();


        $summary = [

            'total' =>
                $workOrders->count(),

            'draft' =>
                $workOrders
                    ->where('status', 'Draft')
                    ->count(),

            'active' =>
                $workOrders
                    ->whereIn(
                        'status',
                        [
                            'Issued',
                            'In Progress',
                        ]
                    )
                    ->count(),

            'completed' =>
                $workOrders
                    ->where(
                        'status',
                        'Completed'
                    )
                    ->count(),

            'total_value' =>
                (float) $workOrders->sum(
                    'work_order_value'
                ),
        ];


        return view(
            'construction.work-orders.index',
            compact(
                'project',
                'workOrders',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $contracts = ProcurementContract::query()
            ->whereHas('tender', function ($query) use ($project) {
                $query->whereHas('package', function ($query) use ($project) {
                    $query->whereHas('procurementPlan', function ($query) use ($project) {
                        $query->where('project_id', $project->id);
                    });
                });
            })
            ->with([
                'bidder',
                'tender.package',
            ])
            ->whereIn('status', [
                'Approved',
                'Active',
                'In Progress',
            ])
            ->orderBy('contract_number')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Approved Development Budgets
        |--------------------------------------------------------------------------
        */

        $budgets = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->with([
                'items.category',
            ])
            ->orderByDesc('id')
            ->get();

        $budgetItems = ProjectBudgetItem::query()
            ->whereHas('budget', function ($query) use ($project) {
                $query
                    ->where('project_id', $project->id)
                    ->where('status', 'Approved');
            })
            ->with([
                'budget',
                'category',
            ])
            ->orderBy('item_name')
            ->get();

        return view(
            'construction.work-orders.create',
            compact(
                'project',
                'contracts',
                'users',
                'budgets',
                'budgetItems'
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

            'procurement_contract_id' =>
                'required|integer',

            'project_budget_id' =>
    'nullable|integer|exists:project_budgets,id',

'project_budget_item_id' =>
    'nullable|integer|exists:project_budget_items,id',

            'work_order_title' =>
                'required|string|max:255',

            'work_order_type' =>
                'nullable|string|max:100',

            'issue_date' =>
                'nullable|date',

            'start_date' =>
                'nullable|date',

            'expected_completion_date' =>
                'nullable|date|after_or_equal:start_date',

            'actual_completion_date' =>
                'nullable|date',

            'work_order_value' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'scope_of_work' =>
                'nullable|string',

            'priority' =>
                'required|string|max:50',

            'status' =>
                'required|string|max:50',

            'assigned_to' =>
                'nullable|integer|exists:users,id',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Contract Belongs To Project
        |--------------------------------------------------------------------------
        */

        $contract =
            ProcurementContract::query()
                ->whereKey(
                    $validated[
                        'procurement_contract_id'
                    ]
                )
                ->whereHas(
                    'tender',
                    function ($query) use ($project) {

                        $query->whereHas(
                            'package',
                            function ($query) use ($project) {

                                $query->whereHas(
                                    'procurementPlan',
                                    function ($query) use ($project) {

                                        $query->where(
                                            'project_id',
                                            $project->id
                                        );
                                    }
                                );
                            }
                        );
                    }
                )
                ->first();


        if (!$contract) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_contract_id' =>
                        'The selected contract does not belong to this project.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Development Budget Linkage
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['project_budget_item_id'])) {

            $budgetItem = ProjectBudgetItem::query()
                ->with('budget')
                ->whereKey($validated['project_budget_item_id'])
                ->first();

            if (!$budgetItem) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item does not exist.',
                    ]);
            }

            if (!$budgetItem->budget) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item is not linked to a budget.',
                    ]);
            }

            if ((int) $budgetItem->budget->project_id !== (int) $project->id) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item does not belong to this project.',
                    ]);
            }

            if ($budgetItem->budget->status !== 'Approved') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'Only items from an approved budget can be selected.',
                    ]);
            }

            if (
                !empty($validated['project_budget_id']) &&
                (int) $validated['project_budget_id'] !==
                (int) $budgetItem->budget->id
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_budget_item_id' =>
                            'The selected budget item does not belong to the selected budget.',
                    ]);
            }

            // Automatically assign the parent budget.
            $validated['project_budget_id'] =
                $budgetItem->budget->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;

        $validated['currency'] =
            $validated['currency']
            ??
            'INR';

        $validated['work_order_value'] =
            $validated['work_order_value']
            ??
            0;

        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Work Order Number
        |--------------------------------------------------------------------------
        */

        do {

            $workOrderNumber =
                'WO-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            ConstructionWorkOrder::query()
                ->where(
                    'work_order_number',
                    $workOrderNumber
                )
                ->exists()
        );


        $validated['work_order_number'] =
            $workOrderNumber;


        /*
        |--------------------------------------------------------------------------
        | Create Work Order
        |--------------------------------------------------------------------------
        */

        $workOrder =
            ConstructionWorkOrder::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.construction.work-orders.index',
                $project
            )
            ->with(
                'success',
                'Work Order '
                . $workOrder->work_order_number
                . ' created successfully.'
            );
    }
}