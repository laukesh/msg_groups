<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetCategory;
use App\Models\ProjectBudgetItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectBudgetItemController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );

        $this->validateEditableBudget(
            $projectBudget
        );


        $validated = $request->validate([

            'project_budget_category_id' => [
                'nullable',
                'integer',
            ],

            'item_code' => [
                'required',
                'string',
                'max:50',
            ],

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_item_id' => [
                'nullable',
                'integer',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:30',
            ],

            'unit_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cost_type' => [
                'nullable',
                'in:Direct,Indirect,Contingency,Other',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Category
        |--------------------------------------------------------------------------
        */

        $category = null;

        if (
            !empty(
                $validated['project_budget_category_id']
            )
        ) {

            $category = $projectBudget
                ->categories()
                ->find(
                    $validated['project_budget_category_id']
                );

            abort_unless(
                $category,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Parent Item
        |--------------------------------------------------------------------------
        */

        $parentItem = null;

        if (
            !empty(
                $validated['parent_item_id']
            )
        ) {

            $parentItem = $projectBudget
                ->items()
                ->find(
                    $validated['parent_item_id']
                );

            abort_unless(
                $parentItem,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Item Code
        |--------------------------------------------------------------------------
        */

        $itemExists = $projectBudget
            ->items()
            ->where(
                'item_code',
                $validated['item_code']
            )
            ->exists();

        if ($itemExists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This item code already exists in this budget.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Sequence
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $validated['sequence']
            )
        ) {

            $validated['sequence'] =
                (
                    $projectBudget
                        ->items()
                        ->max('sequence')
                    ?? 0
                ) + 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        $quantity =
            (float) (
                $validated['quantity']
                ?? 0
            );

        $unitRate =
            (float) (
                $validated['unit_rate']
                ?? 0
            );


        $estimatedAmount =
            round(
                $quantity * $unitRate,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Create Item
        |--------------------------------------------------------------------------
        */

        $item = $projectBudget
            ->items()
            ->create([

                'project_budget_category_id' =>
                    $category?->id,

                'item_code' =>
                    $validated['item_code'],

                'item_name' =>
                    $validated['item_name'],

                'parent_item_id' =>
                    $parentItem?->id,

                'sequence' =>
                    $validated['sequence'],

                'quantity' =>
                    $quantity,

                'unit' =>
                    $validated['unit'] ?? null,

                'unit_rate' =>
                    $unitRate,

                'estimated_amount' =>
                    $estimatedAmount,

                'cost_type' =>
                    $validated['cost_type'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);


        /*
        |--------------------------------------------------------------------------
        | Recalculate Budget
        |--------------------------------------------------------------------------
        */

        $this->recalculateBudget(
            $projectBudget
        );


        return redirect()
            ->route(
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $projectBudget->id,
                ]
            )
            ->with(
                'success',
                'Budget item added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectBudget $projectBudget,
        ProjectBudgetItem $item
    ): View {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );

        $this->validateItemOwnership(
            $projectBudget,
            $item
        );

        $this->validateEditableBudget(
            $projectBudget
        );


        $categories = $projectBudget
            ->categories()
            ->orderBy('sequence')
            ->get();


        $items = $projectBudget
            ->items()
            ->where(
                'id',
                '!=',
                $item->id
            )
            ->orderBy('sequence')
            ->get();


        return view(
            'projects.budget.items.edit',
            compact(
                'project',
                'projectBudget',
                'item',
                'categories',
                'items'
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
        ProjectBudget $projectBudget,
        ProjectBudgetItem $item
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );

        $this->validateItemOwnership(
            $projectBudget,
            $item
        );

        $this->validateEditableBudget(
            $projectBudget
        );


        $validated = $request->validate([

            'project_budget_category_id' => [
                'nullable',
                'integer',
            ],

            'item_code' => [
                'required',
                'string',
                'max:50',
            ],

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'parent_item_id' => [
                'nullable',
                'integer',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:30',
            ],

            'unit_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cost_type' => [
                'nullable',
                'in:Direct,Indirect,Contingency,Other',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Category
        |--------------------------------------------------------------------------
        */

        $category = null;

        if (
            !empty(
                $validated['project_budget_category_id']
            )
        ) {

            $category = $projectBudget
                ->categories()
                ->find(
                    $validated['project_budget_category_id']
                );

            abort_unless(
                $category,
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Parent
        |--------------------------------------------------------------------------
        */

        $parentItem = null;

        if (
            !empty(
                $validated['parent_item_id']
            )
        ) {

            /*
            | Prevent item from being its own parent
            */

            abort_if(
                (int) $validated['parent_item_id'] ===
                (int) $item->id,
                422,
                'An item cannot be its own parent.'
            );


            $parentItem = $projectBudget
                ->items()
                ->find(
                    $validated['parent_item_id']
                );

            abort_unless(
                $parentItem,
                404
            );
        }

        $this->validateParentHierarchy(
            $projectBudget,
            $parentItem?->id,
            $item->id
        );


        /*
        |--------------------------------------------------------------------------
        | Duplicate Code
        |--------------------------------------------------------------------------
        */

        $itemExists = $projectBudget
            ->items()
            ->where(
                'item_code',
                $validated['item_code']
            )
            ->where(
                'id',
                '!=',
                $item->id
            )
            ->exists();


        if ($itemExists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This item code already exists in this budget.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        $quantity =
            (float) (
                $validated['quantity']
                ?? 0
            );

        $unitRate =
            (float) (
                $validated['unit_rate']
                ?? 0
            );


        $estimatedAmount =
            round(
                $quantity * $unitRate,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $item->update([

            'project_budget_category_id' =>
                $category?->id,

            'item_code' =>
                $validated['item_code'],

            'item_name' =>
                $validated['item_name'],

            'parent_item_id' =>
                $parentItem?->id,

            'sequence' =>
                $validated['sequence'] ?? 0,

            'quantity' =>
                $quantity,

            'unit' =>
                $validated['unit'] ?? null,

            'unit_rate' =>
                $unitRate,

            'estimated_amount' =>
                $estimatedAmount,

            'cost_type' =>
                $validated['cost_type'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Recalculate Budget
        |--------------------------------------------------------------------------
        */

        $this->recalculateBudget(
            $projectBudget
        );


        return redirect()
            ->route(
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $projectBudget->id,
                ]
            )
            ->with(
                'success',
                'Budget item updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectBudget $projectBudget,
        ProjectBudgetItem $item
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );

        $this->validateItemOwnership(
            $projectBudget,
            $item
        );

        $this->validateEditableBudget(
            $projectBudget
        );


        /*
        |--------------------------------------------------------------------------
        | Children
        |--------------------------------------------------------------------------
        */

        if (
            $item
                ->children()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This item cannot be deleted because it has child items.'
                );
        }


        $item->delete();


        /*
        |--------------------------------------------------------------------------
        | Recalculate
        |--------------------------------------------------------------------------
        */

        $this->recalculateBudget(
            $projectBudget
        );


        return redirect()
            ->route(
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $projectBudget->id,
                ]
            )
            ->with(
                'success',
                'Budget item deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Recalculate Budget
    |--------------------------------------------------------------------------
    */

    private function recalculateBudget(
        ProjectBudget $projectBudget
    ): void {

        $items = $projectBudget
            ->items()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Direct
        |--------------------------------------------------------------------------
        */

        $directCost = $items
            ->where(
                'cost_type',
                'Direct'
            )
            ->sum(
                'estimated_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Indirect
        |--------------------------------------------------------------------------
        */

        $indirectCost = $items
            ->where(
                'cost_type',
                'Indirect'
            )
            ->sum(
                'estimated_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Contingency
        |--------------------------------------------------------------------------
        */

        $contingencyAmount = $items
            ->where(
                'cost_type',
                'Contingency'
            )
            ->sum(
                'estimated_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $totalBudget =
            $directCost
            + $indirectCost
            + $contingencyAmount;


        $projectBudget->update([

            'direct_cost' =>
                round(
                    $directCost,
                    2
                ),

            'indirect_cost' =>
                round(
                    $indirectCost,
                    2
                ),

            'contingency_amount' =>
                round(
                    $contingencyAmount,
                    2
                ),

            'total_budget' =>
                round(
                    $totalBudget,
                    2
                ),

            'updated_by' =>
                auth()->id(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Project → Budget Ownership
    |--------------------------------------------------------------------------
    */

    private function validateBudgetOwnership(
        Project $project,
        ProjectBudget $projectBudget
    ): void {

        abort_unless(
            (int) $projectBudget->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Budget → Item Ownership
    |--------------------------------------------------------------------------
    */

    private function validateItemOwnership(
        ProjectBudget $projectBudget,
        ProjectBudgetItem $item
    ): void {

        abort_unless(
            (int) $item->project_budget_id ===
            (int) $projectBudget->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Editable Budget
    |--------------------------------------------------------------------------
    */

    private function validateEditableBudget(
        ProjectBudget $projectBudget
    ): void {

        abort_unless(
            $projectBudget->status !== 'Approved',
            403
        );
    }

    private function validateParentHierarchy(
        ProjectBudget $projectBudget,
        ?int $parentItemId,
        int $currentItemId
    ): void {

        if (!$parentItemId) {
            return;
        }

        $visited = [];

        $currentParentId = $parentItemId;

        while ($currentParentId) {

            if (
                $currentParentId === $currentItemId
            ) {

                abort(
                    422,
                    'Circular parent-child relationship is not allowed.'
                );
            }

            if (
                in_array(
                    $currentParentId,
                    $visited,
                    true
                )
            ) {

                abort(
                    422,
                    'Circular parent-child relationship is not allowed.'
                );
            }

            $visited[] = $currentParentId;


            $parent = $projectBudget
                ->items()
                ->select([
                    'id',
                    'parent_item_id',
                ])
                ->find($currentParentId);


            if (!$parent) {

                abort(
                    404
                );
            }


            $currentParentId =
                $parent->parent_item_id;
        }
    }
}