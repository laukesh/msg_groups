<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectBudgetItem;
class ProjectBudgetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $budgets = $project
            ->budgets()
            ->withCount([
                'categories',
                'items',
            ])
            ->orderByDesc('version_number')
            ->get();

        return view(
            'projects.budget.index',
            compact(
                'project',
                'budgets'
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
        /*
        |--------------------------------------------------------------------------
        | Determine next version
        |--------------------------------------------------------------------------
        */

        $nextVersion = (
            $project
                ->budgets()
                ->max('version_number')
            ?? 0
        ) + 1;


        return view(
            'projects.budget.create',
            compact(
                'project',
                'nextVersion'
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

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'budget_type' => [
                'required',
                'string',
                'max:50',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'budget_start_date' => [
                'nullable',
                'date',
            ],

            'budget_end_date' => [
                'nullable',
                'date',
                'after_or_equal:budget_start_date',
            ],

            'direct_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'indirect_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contingency_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | New Budget Is ALWAYS Draft
        |--------------------------------------------------------------------------
        |
        | User must not be able to create an Approved budget
        | directly from the form.
        |
        */

        $budget = \DB::transaction(
            function () use (
                $validated,
                $project
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Project Budget Versions
                |--------------------------------------------------------------------------
                */

                $latestBudget =
                    ProjectBudget::query()
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->lockForUpdate()
                        ->orderByDesc(
                            'version_number'
                        )
                        ->first();


                $versionNumber =
                    $latestBudget
                        ? (
                            (int)
                            $latestBudget->version_number
                        ) + 1
                        : 1;


                /*
                |--------------------------------------------------------------------------
                | Generate Budget Number
                |--------------------------------------------------------------------------
                */

                $budgetNumber =
                    $this->generateBudgetNumber(
                        $project,
                        $versionNumber
                    );


                /*
                |--------------------------------------------------------------------------
                | Costs
                |--------------------------------------------------------------------------
                */

                $directCost =
                    (float) (
                        $validated['direct_cost']
                        ?? 0
                    );


                $indirectCost =
                    (float) (
                        $validated['indirect_cost']
                        ?? 0
                    );


                $contingencyAmount =
                    (float) (
                        $validated['contingency_amount']
                        ?? 0
                    );


                $totalBudget =
                    $directCost
                    +
                    $indirectCost
                    +
                    $contingencyAmount;


                /*
                |--------------------------------------------------------------------------
                | Create Draft Budget
                |--------------------------------------------------------------------------
                */

                return $project
                    ->budgets()
                    ->create([

                        'budget_number' =>
                            $budgetNumber,

                        'title' =>
                            $validated['title'],

                        'budget_type' =>
                            $validated['budget_type'],

                        'version_number' =>
                            $versionNumber,

                        'status' =>
                            'Draft',

                        'currency' =>
                            $validated['currency'],

                        'budget_start_date' =>
                            $validated['budget_start_date']
                            ?? null,

                        'budget_end_date' =>
                            $validated['budget_end_date']
                            ?? null,

                        'direct_cost' =>
                            $directCost,

                        'indirect_cost' =>
                            $indirectCost,

                        'contingency_amount' =>
                            $contingencyAmount,

                        'total_budget' =>
                            $totalBudget,

                        /*
                        |--------------------------------------------------------------------------
                        | Approval fields intentionally NULL
                        |--------------------------------------------------------------------------
                        */

                        'approved_date' =>
                            null,

                        'approved_by' =>
                            null,

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
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $budget->id,
                ]
            )
            ->with(
                'success',
                'Budget created successfully as Draft.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectBudget $projectBudget
    ): View {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        $projectBudget->load([
            'categories.items',
            'items.parent',
            'items.category',
        ]);


        return view(
            'projects.budget.show',
            compact(
                'project',
                'projectBudget'
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
        ProjectBudget $projectBudget
    ): View {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        return view(
            'projects.budget.edit',
            compact(
                'project',
                'projectBudget'
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
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft / Rejected Can Be Edited
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $projectBudget->status,
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
                    'Only Draft or Rejected budgets can be edited.'
                );
        }


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'budget_type' => [
                'required',
                'string',
                'max:50',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'budget_start_date' => [
                'nullable',
                'date',
            ],

            'budget_end_date' => [
                'nullable',
                'date',
                'after_or_equal:budget_start_date',
            ],

            'direct_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'indirect_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contingency_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $directCost =
            (float) (
                $validated['direct_cost']
                ?? 0
            );


        $indirectCost =
            (float) (
                $validated['indirect_cost']
                ?? 0
            );


        $contingencyAmount =
            (float) (
                $validated['contingency_amount']
                ?? 0
            );


        $totalBudget =
            $directCost
            +
            $indirectCost
            +
            $contingencyAmount;


        /*
        |--------------------------------------------------------------------------
        | Never Allow These To Come From Request
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['project_id'],
            $validated['version_number'],
            $validated['budget_number'],
            $validated['total_budget'],
            $validated['status'],
            $validated['approved_date'],
            $validated['approved_by']
        );


        $projectBudget->update([

            ...$validated,

            'direct_cost' =>
                $directCost,

            'indirect_cost' =>
                $indirectCost,

            'contingency_amount' =>
                $contingencyAmount,

            'total_budget' =>
                $totalBudget,

            /*
            |--------------------------------------------------------------------------
            | Rejected → Draft after editing
            |--------------------------------------------------------------------------
            */

            'status' =>
                'Draft',

            'approved_date' =>
                null,

            'approved_by' =>
                null,

            'updated_by' =>
                auth()->id(),

        ]);


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
                'Budget updated successfully.'
            );
    }

    public function submit(
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        if (
            !in_array(
                $projectBudget->status,
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
                    'Only Draft or Rejected budgets can be submitted.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Basic Financial Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $projectBudget->total_budget
            <= 0
        ) {

            return back()
                ->with(
                    'error',
                    'Budget total must be greater than zero before submission.'
                );
        }


        $projectBudget->update([

            'status' =>
                'Submitted',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Budget submitted for approval.'
            );
    }

    public function approve(
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        /*
        |--------------------------------------------------------------------------
        | Only Submitted Can Be Approved
        |--------------------------------------------------------------------------
        */

        if (
            $projectBudget->status !== 'Submitted'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Submitted budgets can be approved.'
                );
        }


        \DB::transaction(
            function () use (
                $project,
                $projectBudget
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Current Budget
                |--------------------------------------------------------------------------
                */

                $budget =
                    ProjectBudget::query()
                        ->whereKey(
                            $projectBudget->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Double Check Status
                |--------------------------------------------------------------------------
                */

                if (
                    $budget->status !== 'Submitted'
                ) {

                    abort(
                        422,
                        'Budget is no longer available for approval.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Supersede Previous Approved Budget
                |--------------------------------------------------------------------------
                */

                ProjectBudget::query()
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->where(
                        'id',
                        '!=',
                        $budget->id
                    )
                    ->update([

                        'status' =>
                            'Superseded',

                        'updated_by' =>
                            auth()->id(),

                        'updated_at' =>
                            now(),

                    ]);


                /*
                |--------------------------------------------------------------------------
                | Approve Current Budget
                |--------------------------------------------------------------------------
                */

                $budget->update([

                    'status' =>
                        'Approved',

                    'approved_date' =>
                        now()->toDateString(),

                    'approved_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);
            }
        );


        return back()
            ->with(
                'success',
                'Budget approved successfully.'
            );
    }

    public function reject(
        Request $request,
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        if (
            $projectBudget->status !== 'Submitted'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Submitted budgets can be rejected.'
                );
        }


        $validated = $request->validate([

            'rejection_remarks' => [
                'required',
                'string',
                'max:5000',
            ],

        ]);


        $existingRemarks =
            $projectBudget->remarks
            ? $projectBudget->remarks
            . "\n\n"
            : '';


        $projectBudget->update([

            'status' =>
                'Rejected',

            'remarks' =>
                $existingRemarks
                . 'Rejection Reason: '
                . $validated['rejection_remarks'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Budget rejected and returned for correction.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        /*
        |--------------------------------------------------------------------------
        | Approved budgets should not be deleted
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $projectBudget->status,
                [
                    'Approved',
                    'Superseded',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Approved or Superseded budgets cannot be deleted.'
                );
        }


        $projectBudget->delete();


        return redirect()
            ->route(
                'admin.projects.budget.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Budget deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Budget Number
    |--------------------------------------------------------------------------
    */

    private function generateBudgetNumber(
        Project $project,
        int $versionNumber
    ): string {

        $projectNumber =
            $project->project_number
            ?: 'PROJECT-' . $project->id;


        return 'BUD-' .
            $projectNumber .
            '-V' .
            $versionNumber;
    }


    /*
    |--------------------------------------------------------------------------
    | Ownership Validation
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

    public function createRevision(
        Project $project,
        ProjectBudget $projectBudget
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        if (
            $projectBudget->status !== 'Approved'
        ) {

            return back()
                ->with(
                    'error',
                    'Only an Approved budget can be revised.'
                );
        }


        $newBudget = \DB::transaction(
            function () use (
                $project,
                $projectBudget
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Latest Version
                |--------------------------------------------------------------------------
                */

                $latestBudget =
                    ProjectBudget::query()
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->lockForUpdate()
                        ->orderByDesc(
                            'version_number'
                        )
                        ->first();


                $nextVersion =
                    $latestBudget
                        ? (
                            (int)
                            $latestBudget->version_number
                        ) + 1
                        : (
                            (int)
                            $projectBudget->version_number
                        ) + 1;


                /*
                |--------------------------------------------------------------------------
                | Generate Number
                |--------------------------------------------------------------------------
                */

                $budgetNumber =
                    $this->generateBudgetNumber(
                        $project,
                        $nextVersion
                    );


                /*
                |--------------------------------------------------------------------------
                | Create Revision
                |--------------------------------------------------------------------------
                */

                $newBudget =
                    $project
                        ->budgets()
                        ->create([

                            'budget_number' =>
                                $budgetNumber,

                            'title' =>
                                $projectBudget->title
                                . ' - Revision '
                                . $nextVersion,

                            'budget_type' =>
                                $projectBudget->budget_type,

                            'version_number' =>
                                $nextVersion,

                            'status' =>
                                'Draft',

                            'currency' =>
                                $projectBudget->currency,

                            'budget_start_date' =>
                                $projectBudget->budget_start_date,

                            'budget_end_date' =>
                                $projectBudget->budget_end_date,

                            'direct_cost' =>
                                $projectBudget->direct_cost,

                            'indirect_cost' =>
                                $projectBudget->indirect_cost,

                            'contingency_amount' =>
                                $projectBudget->contingency_amount,

                            'total_budget' =>
                                $projectBudget->total_budget,

                            'approved_date' =>
                                null,

                            'approved_by' =>
                                null,

                            'remarks' =>
                                'Revision of '
                                . $projectBudget->budget_number,

                            'created_by' =>
                                auth()->id(),

                            'updated_by' =>
                                auth()->id(),

                        ]);


                /*
                |--------------------------------------------------------------------------
                | Copy Categories
                |--------------------------------------------------------------------------
                */

                $categoryMap = [];


                foreach (
                    $projectBudget
                        ->categories()
                        ->orderBy('sequence')
                        ->get()
                    as $category
                ) {

                    $newCategory =
                        $newBudget
                            ->categories()
                            ->create([

                                'category_code' =>
                                    $category->category_code,

                                'category_name' =>
                                    $category->category_name,

                                'sequence' =>
                                    $category->sequence,

                                'description' =>
                                    $category->description,

                            ]);


                    $categoryMap[
                        $category->id
                    ] = $newCategory->id;
                }


                /*
                |--------------------------------------------------------------------------
                | Copy Items
                |--------------------------------------------------------------------------
                */

                $itemMap = [];


                $items =
                    $projectBudget
                        ->items()
                        ->orderBy('sequence')
                        ->get();


                /*
                |--------------------------------------------------------------------------
                | First Pass
                |--------------------------------------------------------------------------
                |
                | Create all items without parent relationship.
                |
                */

                foreach (
                    $items as $item
                ) {

                    $newItem =
                        $newBudget
                            ->items()
                            ->create([

                                'project_budget_category_id' =>
                                    isset(
                                        $categoryMap[
                                            $item->project_budget_category_id
                                        ]
                                    )
                                        ? $categoryMap[
                                            $item->project_budget_category_id
                                        ]
                                        : null,

                                'item_code' =>
                                    $item->item_code,

                                'item_name' =>
                                    $item->item_name,

                                'parent_item_id' =>
                                    null,

                                'sequence' =>
                                    $item->sequence,

                                'quantity' =>
                                    $item->quantity,

                                'unit' =>
                                    $item->unit,

                                'unit_rate' =>
                                    $item->unit_rate,

                                'estimated_amount' =>
                                    $item->estimated_amount,

                                'cost_type' =>
                                    $item->cost_type,

                                'remarks' =>
                                    $item->remarks,

                                'created_by' =>
                                    auth()->id(),

                                'updated_by' =>
                                    auth()->id(),

                            ]);


                    $itemMap[
                        $item->id
                    ] = $newItem->id;
                }


                /*
                |--------------------------------------------------------------------------
                | Second Pass
                |--------------------------------------------------------------------------
                |
                | Restore parent-child relationships.
                |
                */

                foreach (
                    $items as $item
                ) {

                    if (
                        !$item->parent_item_id
                    ) {
                        continue;
                    }


                    if (
                        !isset(
                            $itemMap[
                                $item->id
                            ]
                        )
                    ) {
                        continue;
                    }


                    if (
                        !isset(
                            $itemMap[
                                $item->parent_item_id
                            ]
                        )
                    ) {
                        continue;
                    }


                    ProjectBudgetItem::query()
                        ->where(
                            'id',
                            $itemMap[
                                $item->id
                            ]
                        )
                        ->update([

                            'parent_item_id' =>
                                $itemMap[
                                    $item->parent_item_id
                                ],

                        ]);
                }


                return $newBudget;
            }
        );


        return redirect()
            ->route(
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $newBudget->id,
                ]
            )
            ->with(
                'success',
                'Budget revision V'
                . $newBudget->version_number
                . ' created successfully as Draft.'
            );
    }
}