<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementPrequalification;
use App\Models\ProcurementPrequalificationCriterion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProcurementPrequalificationCriterionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Ensure Prequalification Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function ensurePrequalificationBelongsToProject(
        Project $project,
        ProcurementPrequalification $prequalification
    ): void {

        abort_unless(
            (int) $prequalification->project_id ===
            (int) $project->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ensure Criterion Belongs To Project + Prequalification
    |--------------------------------------------------------------------------
    */

    private function ensureCriterionBelongsToProject(
        Project $project,
        ProcurementPrequalification $prequalification,
        ProcurementPrequalificationCriterion $criterion
    ): void {

        abort_unless(
            (int) $criterion->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            (int) $criterion->procurement_prequalification_id ===
            (int) $prequalification->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $prequalification
        );

        $validated = $request->validate([

            'criterion_name' => [
                'required',
                'string',
                'max:255',
            ],

            'criterion_description' => [
                'nullable',
                'string',
            ],

            'criterion_type' => [
                'required',
                'in:Technical,Financial,Legal,Experience,Manpower,Equipment,Other',
            ],

            'requirement' => [
                'nullable',
                'string',
                'max:500',
            ],

            'bidder_response' => [
                'nullable',
                'string',
            ],

            'evaluation_result' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Partially Compliant,Not Applicable',
            ],

            'evaluator_remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Next Criterion Number
        |--------------------------------------------------------------------------
        */

        $nextNo = (
            ProcurementPrequalificationCriterion::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_prequalification_id',
                    $prequalification->id
                )
                ->max('criterion_no')
            ?? 0
        ) + 1;

        /*
        |--------------------------------------------------------------------------
        | Create Criterion
        |--------------------------------------------------------------------------
        */

        $prequalification->criteria()->create([

            'project_id' =>
                $project->id,

            'criterion_no' =>
                $nextNo,

            'criterion_name' =>
                $validated['criterion_name'],

            'criterion_description' =>
                $validated['criterion_description']
                ?? null,

            'criterion_type' =>
                $validated['criterion_type'],

            'requirement' =>
                $validated['requirement']
                ?? null,

            'bidder_response' =>
                $validated['bidder_response']
                ?? null,

            'evaluation_result' =>
                $validated['evaluation_result'],

            'evaluator_remarks' =>
                $validated['evaluator_remarks']
                ?? null,

            'evaluated_by' =>
                auth()->id(),

            'evaluated_at' =>
                $validated['evaluation_result'] !== 'Pending'
                    ? now()->toDateString()
                    : null,

            'created_by' =>
                auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recalculate Overall Result
        |--------------------------------------------------------------------------
        */

        $prequalification->update([
            'status' =>
                $prequalification->calculateResult(),
        ]);

        return back()->with(
            'success',
            'Prequalification criterion added successfully.'
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
        ProcurementPrequalification $prequalification,
        ProcurementPrequalificationCriterion $criterion
    ): RedirectResponse {

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $prequalification
        );

        $this->ensureCriterionBelongsToProject(
            $project,
            $prequalification,
            $criterion
        );

        $validated = $request->validate([

            'criterion_name' => [
                'required',
                'string',
                'max:255',
            ],

            'criterion_description' => [
                'nullable',
                'string',
            ],

            'criterion_type' => [
                'required',
                'in:Technical,Financial,Legal,Experience,Manpower,Equipment,Other',
            ],

            'requirement' => [
                'nullable',
                'string',
                'max:500',
            ],

            'bidder_response' => [
                'nullable',
                'string',
            ],

            'evaluation_result' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Partially Compliant,Not Applicable',
            ],

            'evaluator_remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $criterion->update([

            'criterion_name' =>
                $validated['criterion_name'],

            'criterion_description' =>
                $validated['criterion_description']
                ?? null,

            'criterion_type' =>
                $validated['criterion_type'],

            'requirement' =>
                $validated['requirement']
                ?? null,

            'bidder_response' =>
                $validated['bidder_response']
                ?? null,

            'evaluation_result' =>
                $validated['evaluation_result'],

            'evaluator_remarks' =>
                $validated['evaluator_remarks']
                ?? null,

            'evaluated_by' =>
                auth()->id(),

            'evaluated_at' =>
                $validated['evaluation_result'] !== 'Pending'
                    ? now()->toDateString()
                    : null,

            'updated_by' =>
                auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recalculate Overall Result
        |--------------------------------------------------------------------------
        */

        $prequalification->update([
            'status' =>
                $prequalification->calculateResult(),
        ]);

        return back()->with(
            'success',
            'Prequalification criterion updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProcurementPrequalification $prequalification,
        ProcurementPrequalificationCriterion $criterion
    ): RedirectResponse {

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $prequalification
        );

        $this->ensureCriterionBelongsToProject(
            $project,
            $prequalification,
            $criterion
        );

        $criterion->delete();

        /*
        |--------------------------------------------------------------------------
        | Recalculate Overall Result
        |--------------------------------------------------------------------------
        */

        $prequalification->update([
            'status' =>
                $prequalification->calculateResult(),
        ]);

        return back()->with(
            'success',
            'Prequalification criterion deleted successfully.'
        );
    }
}