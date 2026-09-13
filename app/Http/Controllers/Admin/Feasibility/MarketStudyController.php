<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FeasibilityAssessment;
use App\Models\Land;
use App\Models\MarketStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketStudyController extends Controller
{
    /**
     * Display market studies.
     */
    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        return view(
            'feasibility.market-studies.index',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'marketStudies' => $feasibilityAssessment
                    ->marketStudies()
                    ->latest('id')
                    ->paginate(15),
            ]
        );
    }


    /**
     * Show create form.
     */
    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        return view(
            'feasibility.market-studies.create',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
            ]
        );
    }


    /**
     * Store market study.
     */
    public function store(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'study_date' => [
                'nullable',
                'date',
            ],

            'study_period' => [
                'nullable',
                'string',
                'max:100',
            ],

            'market_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'market_segment' => [
                'nullable',
                'string',
                'max:150',
            ],

            'market_overview' => [
                'nullable',
                'string',
            ],

            'market_trends' => [
                'nullable',
                'string',
            ],

            'target_market' => [
                'nullable',
                'string',
            ],

            'market_size' => [
                'nullable',
                'numeric',
            ],

            'growth_rate' => [
                'nullable',
                'numeric',
            ],

            'growth_outlook' => [
                'nullable',
                'string',
            ],

            'key_drivers' => [
                'nullable',
                'string',
            ],

            'key_constraints' => [
                'nullable',
                'string',
            ],

            'key_assumptions' => [
                'nullable',
                'string',
            ],

            'key_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;

        $validated['study_number'] =
            'MS-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(Str::random(4));

        $validated['status'] = 'Draft';

        $validated['created_by'] = auth()->id();


        $marketStudy = MarketStudy::create($validated);


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.market-studies.show',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'marketStudy' => $marketStudy,
            ]
        )->with(
            'success',
            'Market study created successfully.'
        );
    }


    /**
     * Display market study.
     */
    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        MarketStudy $marketStudy
    ) {
        return view(
            'feasibility.market-studies.show',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'marketStudy' => $marketStudy,
            ]
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        MarketStudy $marketStudy
    ) {
        return view(
            'feasibility.market-studies.edit',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'marketStudy' => $marketStudy,
            ]
        );
    }


    /**
     * Update market study.
     */
    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        MarketStudy $marketStudy
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'study_date' => [
                'nullable',
                'date',
            ],

            'study_period' => [
                'nullable',
                'string',
                'max:100',
            ],

            'market_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'market_segment' => [
                'nullable',
                'string',
                'max:150',
            ],

            'market_overview' => [
                'nullable',
                'string',
            ],

            'market_trends' => [
                'nullable',
                'string',
            ],

            'target_market' => [
                'nullable',
                'string',
            ],

            'market_size' => [
                'nullable',
                'numeric',
            ],

            'growth_rate' => [
                'nullable',
                'numeric',
            ],

            'growth_outlook' => [
                'nullable',
                'string',
            ],

            'key_drivers' => [
                'nullable',
                'string',
            ],

            'key_constraints' => [
                'nullable',
                'string',
            ],

            'key_assumptions' => [
                'nullable',
                'string',
            ],

            'key_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

        ]);


        $validated['updated_by'] = auth()->id();


        $marketStudy->update($validated);


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.market-studies.show',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
                'marketStudy' => $marketStudy,
            ]
        )->with(
            'success',
            'Market study updated successfully.'
        );
    }


    /**
     * Delete market study.
     */
    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        MarketStudy $marketStudy
    ) {
        $marketStudy->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.market-studies.index',
            [
                'land' => $land,
                'feasibilityAssessment' => $feasibilityAssessment,
            ]
        )->with(
            'success',
            'Market study deleted successfully.'
        );
    }
}