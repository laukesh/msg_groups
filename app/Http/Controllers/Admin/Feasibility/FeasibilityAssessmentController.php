<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FeasibilityAssessment;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeasibilityAssessmentController extends Controller
{
    /**
     * Display feasibility assessments.
     */
    public function index(Land $land)
    {
        $feasibilities = $land
            ->feasibilityAssessments()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.assessments.index',
            compact(
                'land',
                'feasibilities'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'feasibility.assessments.create',
            compact('land')
        );
    }


    /**
     * Store feasibility assessment.
     */
    public function store(
        Request $request,
        Land $land
    ) {

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'project_concept' => [
                'nullable',
                'string'
            ],

            'development_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'target_completion_date' => [
                'nullable',
                'date'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'key_assumptions' => [
                'nullable',
                'string'
            ],

            'key_risks' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['land_id'] = $land->id;

        $validated['assessment_number'] =
            'FEAS-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );

        $validated['status'] = 'Draft';

        $validated['created_by'] = auth()->id();


        FeasibilityAssessment::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.feasibility-assessments.index',
                $land
            )
            ->with(
                'success',
                'Feasibility assessment created successfully.'
            );
    }


    /**
     * Display feasibility assessment.
     */
    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {

        $this->validateBelongsToLand(
            $land,
            $feasibilityAssessment
        );


        return view(
            'feasibility.assessments.show',
            compact(
                'land',
                'feasibilityAssessment'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {

        $this->validateBelongsToLand(
            $land,
            $feasibilityAssessment
        );


        return view(
            'feasibility.assessments.edit',
            compact(
                'land',
                'feasibilityAssessment'
            )
        );
    }


    /**
     * Update feasibility assessment.
     */
    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {

        $this->validateBelongsToLand(
            $land,
            $feasibilityAssessment
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'project_concept' => [
                'nullable',
                'string'
            ],

            'development_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'required',
                'string',
                'max:50'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'target_completion_date' => [
                'nullable',
                'date'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'key_assumptions' => [
                'nullable',
                'string'
            ],

            'key_risks' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

        ]);


        $validated['updated_by'] =
            auth()->id();


        $feasibilityAssessment->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.feasibility-assessments.show',
                [
                    $land,
                    $feasibilityAssessment
                ]
            )
            ->with(
                'success',
                'Feasibility assessment updated successfully.'
            );
    }


    /**
     * Delete feasibility assessment.
     */
    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {

        $this->validateBelongsToLand(
            $land,
            $feasibilityAssessment
        );


        $feasibilityAssessment->delete();


        return redirect()
            ->route(
                'admin.land.lands.feasibility-assessments.index',
                $land
            )
            ->with(
                'success',
                'Feasibility assessment deleted successfully.'
            );
    }


    private function validateBelongsToLand(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ): void {

        abort_unless(
            (int) $feasibilityAssessment->land_id ===
            (int) $land->id,
            404
        );
    }
}