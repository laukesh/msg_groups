<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseEnvironmentalCompliance;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseEnvironmentalComplianceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $compliances = ConstructionHseEnvironmentalCompliance::query()
            ->where('project_id', $project->id)
            ->with([
                'responsiblePerson',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.environmental-compliances.index',
            [
                'project' => $project,
                'compliances' => $compliances,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $complianceNumber =
            $this->generateComplianceNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.environmental-compliances.create',
            [
                'project' => $project,
                'complianceNumber' => $complianceNumber,
                'users' => $users,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'compliance_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_compliances,compliance_number',
            ],

            'compliance_title' => [
                'required',
                'string',
                'max:255',
            ],

            'compliance_type' => [
                'required',
                'string',
                'max:100',
            ],

            'regulatory_authority' => [
                'nullable',
                'string',
                'max:255',
            ],

            'legislation_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'permit_license_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'requirement_description' => [
                'nullable',
                'string',
            ],

            'applicable_from' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'review_date' => [
                'nullable',
                'date',
            ],

            'compliance_status' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Not Applicable',
            ],

            'risk_level' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'responsible_person_id' => [
                'nullable',
                'exists:users,id',
            ],

            'responsible_person_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'evidence_available' => [
                'nullable',
                'boolean',
            ],

            'evidence_description' => [
                'nullable',
                'string',
            ],

            'non_compliance_details' => [
                'nullable',
                'string',
            ],

            'corrective_action_required' => [
                'nullable',
                'boolean',
            ],

            'corrective_action' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Approved,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $responsiblePersonName = null;

        if (!empty($validated['responsible_person_id'])) {

            $responsiblePersonName = User::find(
                $validated['responsible_person_id']
            )?->name;
        }


        $compliance =
            ConstructionHseEnvironmentalCompliance::create([

                'project_id' =>
                    $project->id,

                'compliance_number' =>
                    $validated['compliance_number'],

                'compliance_title' =>
                    $validated['compliance_title'],

                'compliance_type' =>
                    $validated['compliance_type'],

                'regulatory_authority' =>
                    $validated['regulatory_authority'] ?? null,

                'legislation_reference' =>
                    $validated['legislation_reference'] ?? null,

                'permit_license_number' =>
                    $validated['permit_license_number'] ?? null,

                'requirement_description' =>
                    $validated['requirement_description'] ?? null,

                'applicable_from' =>
                    $validated['applicable_from'] ?? null,

                'due_date' =>
                    $validated['due_date'] ?? null,

                'review_date' =>
                    $validated['review_date'] ?? null,

                'compliance_status' =>
                    $validated['compliance_status'],

                'risk_level' =>
                    $validated['risk_level'],

                'responsible_person_id' =>
                    $validated['responsible_person_id'] ?? null,

                'responsible_person_name' =>
                    $responsiblePersonName
                    ?? ($validated['responsible_person_name'] ?? null),

                'evidence_available' =>
                    $request->boolean('evidence_available'),

                'evidence_description' =>
                    $validated['evidence_description'] ?? null,

                'non_compliance_details' =>
                    $validated['non_compliance_details'] ?? null,

                'corrective_action_required' =>
                    $request->boolean(
                        'corrective_action_required'
                    ),

                'corrective_action' =>
                    $validated['corrective_action'] ?? null,

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.compliances.show',
                [
                    'project' => $project,
                    'compliance' => $compliance,
                ]
            )
            ->with(
                'success',
                'Environmental compliance record created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseEnvironmentalCompliance $compliance
    ): View {

        $this->validateComplianceRelation(
            $project,
            $compliance
        );

        $compliance->load([
            'responsiblePerson',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.environmental-compliances.show',
            [
                'project' => $project,
                'compliance' => $compliance,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionHseEnvironmentalCompliance $compliance
    ): View {

        $this->validateComplianceRelation(
            $project,
            $compliance
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.environmental-compliances.edit',
            [
                'project' => $project,
                'compliance' => $compliance,
                'users' => $users,
            ]
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
        ConstructionHseEnvironmentalCompliance $compliance
    ): RedirectResponse {

        $this->validateComplianceRelation(
            $project,
            $compliance
        );


        $validated = $request->validate([

            'compliance_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_compliances,compliance_number,' .
                    $compliance->id,
            ],

            'compliance_title' => [
                'required',
                'string',
                'max:255',
            ],

            'compliance_type' => [
                'required',
                'string',
                'max:100',
            ],

            'regulatory_authority' => [
                'nullable',
                'string',
                'max:255',
            ],

            'legislation_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'permit_license_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'requirement_description' => [
                'nullable',
                'string',
            ],

            'applicable_from' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'review_date' => [
                'nullable',
                'date',
            ],

            'compliance_status' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Not Applicable',
            ],

            'risk_level' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'responsible_person_id' => [
                'nullable',
                'exists:users,id',
            ],

            'responsible_person_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'evidence_available' => [
                'nullable',
                'boolean',
            ],

            'evidence_description' => [
                'nullable',
                'string',
            ],

            'non_compliance_details' => [
                'nullable',
                'string',
            ],

            'corrective_action_required' => [
                'nullable',
                'boolean',
            ],

            'corrective_action' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Approved,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $responsiblePersonName = null;

        if (!empty($validated['responsible_person_id'])) {

            $responsiblePersonName = User::find(
                $validated['responsible_person_id']
            )?->name;
        }


        $compliance->update([

            'compliance_number' =>
                $validated['compliance_number'],

            'compliance_title' =>
                $validated['compliance_title'],

            'compliance_type' =>
                $validated['compliance_type'],

            'regulatory_authority' =>
                $validated['regulatory_authority'] ?? null,

            'legislation_reference' =>
                $validated['legislation_reference'] ?? null,

            'permit_license_number' =>
                $validated['permit_license_number'] ?? null,

            'requirement_description' =>
                $validated['requirement_description'] ?? null,

            'applicable_from' =>
                $validated['applicable_from'] ?? null,

            'due_date' =>
                $validated['due_date'] ?? null,

            'review_date' =>
                $validated['review_date'] ?? null,

            'compliance_status' =>
                $validated['compliance_status'],

            'risk_level' =>
                $validated['risk_level'],

            'responsible_person_id' =>
                $validated['responsible_person_id'] ?? null,

            'responsible_person_name' =>
                $responsiblePersonName
                ?? ($validated['responsible_person_name'] ?? null),

            'evidence_available' =>
                $request->boolean('evidence_available'),

            'evidence_description' =>
                $validated['evidence_description'] ?? null,

            'non_compliance_details' =>
                $validated['non_compliance_details'] ?? null,

            'corrective_action_required' =>
                $request->boolean(
                    'corrective_action_required'
                ),

            'corrective_action' =>
                $validated['corrective_action'] ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.compliances.show',
                [
                    'project' => $project,
                    'compliance' => $compliance,
                ]
            )
            ->with(
                'success',
                'Environmental compliance record updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseEnvironmentalCompliance $compliance
    ): RedirectResponse {

        $this->validateComplianceRelation(
            $project,
            $compliance
        );

        $compliance->delete();

        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.compliances.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'Environmental compliance record deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateComplianceRelation(
        Project $project,
        ConstructionHseEnvironmentalCompliance $compliance
    ): void {

        abort_unless(
            (int) $compliance->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATION
    |--------------------------------------------------------------------------
    */

    protected function generateComplianceNumber(): string
    {
        $lastId =
            ConstructionHseEnvironmentalCompliance::max('id')
            ?? 0;

        return 'HSE-ENV-CMP-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}