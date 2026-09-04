<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPrequalification;
use App\Models\ProcurementPrequalificationCriterion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProcurementPrequalificationCriterionController extends Controller
{
    public function store(
        Request $request,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

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


        $nextNo = (
            $prequalification
                ->criteria()
                ->max('criterion_no') ?? 0
        ) + 1;


        /*$prequalification->criteria()->create([
            'criterion_no' => $nextNo,

            'criterion_name' =>
                $validated['criterion_name'],

            'criterion_description' =>
                $validated['criterion_description'] ?? null,

            'criterion_type' =>
                $validated['criterion_type'],

            'requirement' =>
                $validated['requirement'] ?? null,

            'bidder_response' =>
                $validated['bidder_response'] ?? null,

            'evaluation_result' =>
                $validated['evaluation_result'],

            'evaluator_remarks' =>
                $validated['evaluator_remarks'] ?? null,

            'evaluated_by' =>
                auth()->id(),

            'evaluated_at' =>
                $validated['evaluation_result'] !== 'Pending'
                    ? now()->toDateString()
                    : null,

            'created_by' =>
                auth()->id(),
        ]);*/

        $criterion = $prequalification->criteria()->create([
            'criterion_no' => $nextNo,

            'criterion_name' =>
                $validated['criterion_name'],

            'criterion_description' =>
                $validated['criterion_description'] ?? null,

            'criterion_type' =>
                $validated['criterion_type'],

            'requirement' =>
                $validated['requirement'] ?? null,

            'bidder_response' =>
                $validated['bidder_response'] ?? null,

            'evaluation_result' =>
                $validated['evaluation_result'],

            'evaluator_remarks' =>
                $validated['evaluator_remarks'] ?? null,

            'evaluated_by' =>
                auth()->id(),

            'evaluated_at' =>
                $validated['evaluation_result'] !== 'Pending'
                    ? now()->toDateString()
                    : null,

            'created_by' =>
                auth()->id(),
        ]);


        // Automatically calculate overall result
        $prequalification->update([
            'status' => $prequalification->calculateResult(),
        ]);


        return back()->with(
            'success',
            'Prequalification criterion added successfully.'
        );
    }


    public function update(
        Request $request,
        ProcurementPrequalification $prequalification,
        ProcurementPrequalificationCriterion $criterion
    ): RedirectResponse {

        abort_unless(
            $criterion->procurement_prequalification_id
                === $prequalification->id,
            404
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
                $validated['criterion_description'] ?? null,

            'criterion_type' =>
                $validated['criterion_type'],

            'requirement' =>
                $validated['requirement'] ?? null,

            'bidder_response' =>
                $validated['bidder_response'] ?? null,

            'evaluation_result' =>
                $validated['evaluation_result'],

            'evaluator_remarks' =>
                $validated['evaluator_remarks'] ?? null,

            'evaluated_by' =>
                auth()->id(),

            'evaluated_at' =>
                $validated['evaluation_result'] !== 'Pending'
                    ? now()->toDateString()
                    : null,

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Prequalification criterion updated successfully.'
        );
    }


    public function destroy(
        ProcurementPrequalification $prequalification,
        ProcurementPrequalificationCriterion $criterion
    ): RedirectResponse {

        abort_unless(
            $criterion->procurement_prequalification_id
                === $prequalification->id,
            404
        );


        $criterion->delete();

        $prequalification->update([
            'status' => $prequalification->calculateResult(),
        ]);


        return back()->with(
            'success',
            'Prequalification criterion deleted successfully.'
        );
    }
}