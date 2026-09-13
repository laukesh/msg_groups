<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\FeasibilityAssessment;
use App\Models\LegalRegulatoryFeasibility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalRegulatoryFeasibilityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        $legalRegulatoryFeasibilities =
            $feasibilityAssessment
                ->legalRegulatoryFeasibilities()
                ->latest('id')
                ->paginate(15);

        return view(
            'feasibility.legal-regulatory-feasibilities.index',
            compact(
                'land',
                'feasibilityAssessment',
                'legalRegulatoryFeasibilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );

        return view(
            'feasibility.legal-regulatory-feasibilities.create',
            compact(
                'land',
                'feasibilityAssessment'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );


        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */

            'ownership_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ownership_details' => [
                'nullable',
                'string',
            ],


            'title_verification_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'title_verification_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Encumbrance
            |--------------------------------------------------------------------------
            */

            'encumbrance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'encumbrance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Zoning
            |--------------------------------------------------------------------------
            */

            'zoning_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'zoning_type' => [
                'nullable',
                'string',
                'max:150',
            ],

            'zoning_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Permission
            |--------------------------------------------------------------------------
            */

            'development_permission_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_permission_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Building Approval
            |--------------------------------------------------------------------------
            */

            'building_approval_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'building_approval_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Environmental
            |--------------------------------------------------------------------------
            */

            'environmental_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_clearance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Fire NOC
            |--------------------------------------------------------------------------
            */

            'fire_noc_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fire_noc_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pollution
            |--------------------------------------------------------------------------
            */

            'pollution_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pollution_clearance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | RERA
            |--------------------------------------------------------------------------
            */

            'rera_applicability' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rera_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rera_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Other Approvals
            |--------------------------------------------------------------------------
            */

            'other_approval_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'other_approval_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Legal / Compliance
            |--------------------------------------------------------------------------
            */

            'legal_risks' => [
                'nullable',
                'string',
            ],

            'compliance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'compliance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_legal_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Score
            |--------------------------------------------------------------------------
            */

            'overall_legal_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Assessment
        |--------------------------------------------------------------------------
        */

        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        /*
        |--------------------------------------------------------------------------
        | Analysis Number
        |--------------------------------------------------------------------------
        */

        $validated['analysis_number'] =
            'LR-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            'Draft';


        /*
        |--------------------------------------------------------------------------
        | Created By
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $legalRegulatoryFeasibility =
            LegalRegulatoryFeasibility::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'legalRegulatoryFeasibility' =>
                    $legalRegulatoryFeasibility->id,
            ]
        )->with(
            'success',
            'Legal & regulatory feasibility created successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LegalRegulatoryFeasibility $legalRegulatoryFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );


        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $legalRegulatoryFeasibility
        );


        return view(
            'feasibility.legal-regulatory-feasibilities.show',
            compact(
                'land',
                'feasibilityAssessment',
                'legalRegulatoryFeasibility'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LegalRegulatoryFeasibility $legalRegulatoryFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );


        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $legalRegulatoryFeasibility
        );


        return view(
            'feasibility.legal-regulatory-feasibilities.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'legalRegulatoryFeasibility'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LegalRegulatoryFeasibility $legalRegulatoryFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );


        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $legalRegulatoryFeasibility
        );


        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */

            'ownership_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ownership_details' => [
                'nullable',
                'string',
            ],

            'title_verification_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'title_verification_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Encumbrance
            |--------------------------------------------------------------------------
            */

            'encumbrance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'encumbrance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Zoning
            |--------------------------------------------------------------------------
            */

            'zoning_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'zoning_type' => [
                'nullable',
                'string',
                'max:150',
            ],

            'zoning_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Permission
            |--------------------------------------------------------------------------
            */

            'development_permission_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_permission_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Building Approval
            |--------------------------------------------------------------------------
            */

            'building_approval_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'building_approval_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Environmental
            |--------------------------------------------------------------------------
            */

            'environmental_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'environmental_clearance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Fire NOC
            |--------------------------------------------------------------------------
            */

            'fire_noc_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fire_noc_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pollution
            |--------------------------------------------------------------------------
            */

            'pollution_clearance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pollution_clearance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | RERA
            |--------------------------------------------------------------------------
            */

            'rera_applicability' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rera_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rera_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Other Approvals
            |--------------------------------------------------------------------------
            */

            'other_approval_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'other_approval_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Legal / Compliance
            |--------------------------------------------------------------------------
            */

            'legal_risks' => [
                'nullable',
                'string',
            ],

            'compliance_status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'compliance_details' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Findings
            |--------------------------------------------------------------------------
            */

            'key_legal_findings' => [
                'nullable',
                'string',
            ],

            'recommendation' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Score
            |--------------------------------------------------------------------------
            */

            'overall_legal_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'string',
                'max:50',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $legalRegulatoryFeasibility->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'legalRegulatoryFeasibility' =>
                    $legalRegulatoryFeasibility->id,
            ]
        )->with(
            'success',
            'Legal & regulatory feasibility updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        LegalRegulatoryFeasibility $legalRegulatoryFeasibility
    ) {
        $this->validateLandAssessment(
            $land,
            $feasibilityAssessment
        );


        $this->validateBelongsToAssessment(
            $feasibilityAssessment,
            $legalRegulatoryFeasibility
        );


        $legalRegulatoryFeasibility->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.index',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Legal & regulatory feasibility deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Land -> Assessment
    |--------------------------------------------------------------------------
    */

    private function validateLandAssessment(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ): void {
        abort_unless(
            (int) $feasibilityAssessment->land_id ===
            (int) $land->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Assessment -> Legal Feasibility
    |--------------------------------------------------------------------------
    */

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        LegalRegulatoryFeasibility $legalRegulatoryFeasibility
    ): void {
        abort_unless(
            (int) $legalRegulatoryFeasibility
                ->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}