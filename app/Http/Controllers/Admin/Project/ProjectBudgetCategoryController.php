<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectBudgetCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectBudgetCategoryController extends Controller
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

            'category_code' => [
                'required',
                'string',
                'max:50',
            ],

            'category_name' => [
                'required',
                'string',
                'max:255',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Sequence
        |--------------------------------------------------------------------------
        */

        if (
            ! isset($validated['sequence'])
        ) {

            $validated['sequence'] =
                (
                    $projectBudget
                        ->categories()
                        ->max('sequence')
                    ?? 0
                ) + 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate category code
        |--------------------------------------------------------------------------
        */

        $exists = $projectBudget
            ->categories()
            ->where(
                'category_code',
                $validated['category_code']
            )
            ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This category code already exists in this budget.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $projectBudget
            ->categories()
            ->create($validated);


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
                'Budget category added successfully.'
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
        ProjectBudgetCategory $category
    ): View {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        $this->validateCategoryOwnership(
            $projectBudget,
            $category
        );


        $this->validateEditableBudget(
            $projectBudget
        );


        return view(
            'projects.budget.categories.edit',
            compact(
                'project',
                'projectBudget',
                'category'
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
        ProjectBudgetCategory $category
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        $this->validateCategoryOwnership(
            $projectBudget,
            $category
        );


        $this->validateEditableBudget(
            $projectBudget
        );


        $validated = $request->validate([

            'category_code' => [
                'required',
                'string',
                'max:50',
            ],

            'category_name' => [
                'required',
                'string',
                'max:255',
            ],

            'sequence' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Duplicate Code Check
        |--------------------------------------------------------------------------
        */

        $exists = $projectBudget
            ->categories()
            ->where(
                'category_code',
                $validated['category_code']
            )
            ->where(
                'id',
                '!=',
                $category->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This category code already exists in this budget.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $category->update($validated);


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
                'Budget category updated successfully.'
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
        ProjectBudgetCategory $category
    ): RedirectResponse {

        $this->validateBudgetOwnership(
            $project,
            $projectBudget
        );


        $this->validateCategoryOwnership(
            $projectBudget,
            $category
        );


        $this->validateEditableBudget(
            $projectBudget
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent deletion when items exist
        |--------------------------------------------------------------------------
        */

        if ($category->items()->exists()) {

            return back()
                ->with(
                    'error',
                    'This category cannot be deleted because budget items are assigned to it.'
                );
        }


        $category->delete();


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
                'Budget category deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Budget Ownership
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
    | Category Ownership
    |--------------------------------------------------------------------------
    */

    private function validateCategoryOwnership(
        ProjectBudget $projectBudget,
        ProjectBudgetCategory $category
    ): void {

        abort_unless(
            (int) $category->project_budget_id ===
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
}