<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseEnvironmentalAction;
use App\Models\ConstructionHseEnvironmentalCompliance;
use App\Models\ConstructionHseEnvironmentalRecord;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseEnvironmentalActionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $actions = ConstructionHseEnvironmentalAction::query()
            ->where('project_id', $project->id)
            ->with([
                'environmentalRecord',
                'environmentalCompliance',
                'assignee',
                'verifier',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.environmental-actions.index',
            [
                'project' => $project,
                'actions' => $actions,
            ]
        );
    }


    public function create(
        Request $request,
        Project $project
    ): View {
        $actionNumber = $this->generateActionNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        $records = ConstructionHseEnvironmentalRecord::query()
            ->where('project_id', $project->id)
            ->latest('monitoring_date')
            ->get();

        $compliances = ConstructionHseEnvironmentalCompliance::query()
            ->where('project_id', $project->id)
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Pre-selected Source
        |--------------------------------------------------------------------------
        */

        $sourceType = $request->get('source');

        $sourceId = $request->get('source_id');

        $selectedRecordId = null;

        $selectedComplianceId = null;


        /*
        |--------------------------------------------------------------------------
        | Environmental Record Source
        |--------------------------------------------------------------------------
        */

        if (
            $sourceType === 'record' &&
            !empty($sourceId)
        ) {

            $record = ConstructionHseEnvironmentalRecord::query()
                ->where('id', $sourceId)
                ->where('project_id', $project->id)
                ->firstOrFail();

            $selectedRecordId = $record->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Environmental Compliance Source
        |--------------------------------------------------------------------------
        */

        if (
            $sourceType === 'compliance' &&
            !empty($sourceId)
        ) {

            $compliance =
                ConstructionHseEnvironmentalCompliance::query()
                    ->where('id', $sourceId)
                    ->where('project_id', $project->id)
                    ->firstOrFail();

            $selectedComplianceId = $compliance->id;
        }


        return view(
            'construction.hse.environmental-actions.create',
            [
                'project' => $project,

                'actionNumber' => $actionNumber,

                'users' => $users,

                'records' => $records,

                'compliances' => $compliances,

                'selectedRecordId' =>
                    $selectedRecordId,

                'selectedComplianceId' =>
                    $selectedComplianceId,
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

            'action_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_actions,action_number',
            ],

            'environmental_record_id' => [
                'nullable',
                'exists:construction_hse_environmental_records,id',
            ],

            'environmental_compliance_id' => [
                'nullable',
                'exists:construction_hse_environmental_compliances,id',
            ],

            'action_title' => [
                'required',
                'string',
                'max:255',
            ],

            'action_type' => [
                'required',
                'in:Corrective Action,Preventive Action,Improvement Action,Legal / Compliance Action',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'action_description' => [
                'nullable',
                'string',
            ],

            'root_cause' => [
                'nullable',
                'string',
            ],

            'preventive_action' => [
                'nullable',
                'string',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'assigned_to_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'assigned_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'completion_date' => [
                'nullable',
                'date',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'verification_required' => [
                'nullable',
                'boolean',
            ],

            'verification_status' => [
                'required',
                'in:Pending,Verified,Rejected,Not Required',
            ],

            'verified_by' => [
                'nullable',
                'exists:users,id',
            ],

            'verified_at' => [
                'nullable',
                'date',
            ],

            'verification_remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Source Record Belongs To Project
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['environmental_record_id'])) {

            $exists = ConstructionHseEnvironmentalRecord::query()
                ->where('id', $validated['environmental_record_id'])
                ->where('project_id', $project->id)
                ->exists();

            abort_unless($exists, 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Source Compliance Belongs To Project
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['environmental_compliance_id'])) {

            $exists = ConstructionHseEnvironmentalCompliance::query()
                ->where('id', $validated['environmental_compliance_id'])
                ->where('project_id', $project->id)
                ->exists();

            abort_unless($exists, 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Assigned User
        |--------------------------------------------------------------------------
        */

        $assignedToName = null;

        if (!empty($validated['assigned_to'])) {

            $assignedToName = User::find(
                $validated['assigned_to']
            )?->name;
        }


        /*
        |--------------------------------------------------------------------------
        | Verified By
        |--------------------------------------------------------------------------
        */

        $verifiedBy = null;
        $verifiedAt = null;

        if (!empty($validated['verified_by'])) {

            $verifiedBy =
                $validated['verified_by'];

            $verifiedAt =
                $validated['verified_at']
                ?? now();
        }


        $action =
            ConstructionHseEnvironmentalAction::create([

                'project_id' =>
                    $project->id,

                'environmental_record_id' =>
                    $validated['environmental_record_id']
                    ?? null,

                'environmental_compliance_id' =>
                    $validated['environmental_compliance_id']
                    ?? null,

                'action_number' =>
                    $validated['action_number'],

                'action_title' =>
                    $validated['action_title'],

                'action_type' =>
                    $validated['action_type'],

                'priority' =>
                    $validated['priority'],

                'action_description' =>
                    $validated['action_description']
                    ?? null,

                'root_cause' =>
                    $validated['root_cause']
                    ?? null,

                'preventive_action' =>
                    $validated['preventive_action']
                    ?? null,

                'assigned_to' =>
                    $validated['assigned_to']
                    ?? null,

                'assigned_to_name' =>
                    $assignedToName
                    ?? ($validated['assigned_to_name'] ?? null),

                'assigned_date' =>
                    $validated['assigned_date']
                    ?? null,

                'due_date' =>
                    $validated['due_date']
                    ?? null,

                'completion_date' =>
                    $validated['completion_date']
                    ?? null,

                'completion_remarks' =>
                    $validated['completion_remarks']
                    ?? null,

                'verification_required' =>
                    $request->boolean(
                        'verification_required'
                    ),

                'verification_status' =>
                    $validated['verification_status'],

                'verified_by' =>
                    $verifiedBy,

                'verified_at' =>
                    $verifiedAt,

                'verification_remarks' =>
                    $validated['verification_remarks']
                    ?? null,

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.actions.show',
                [
                    'project' => $project,
                    'action' => $action,
                ]
            )
            ->with(
                'success',
                'Environmental action created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseEnvironmentalAction $action
    ): View {

        $this->validateActionRelation(
            $project,
            $action
        );

        $action->load([
            'environmentalRecord',
            'environmentalCompliance',
            'assignee',
            'verifier',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.environmental-actions.show',
            [
                'project' => $project,
                'action' => $action,
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
        ConstructionHseEnvironmentalAction $action
    ): View {

        $this->validateActionRelation(
            $project,
            $action
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        $records = ConstructionHseEnvironmentalRecord::query()
            ->where('project_id', $project->id)
            ->latest('monitoring_date')
            ->get();

        $compliances = ConstructionHseEnvironmentalCompliance::query()
            ->where('project_id', $project->id)
            ->latest('id')
            ->get();

        return view(
            'construction.hse.environmental-actions.edit',
            [
                'project' => $project,
                'action' => $action,
                'actionNumber' => $action->action_number,
                'users' => $users,
                'records' => $records,
                'compliances' => $compliances,
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
        ConstructionHseEnvironmentalAction $action
    ): RedirectResponse {

        $this->validateActionRelation(
            $project,
            $action
        );


        $validated = $request->validate([

            'action_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_actions,action_number,' .
                    $action->id,
            ],

            'environmental_record_id' => [
                'nullable',
                'exists:construction_hse_environmental_records,id',
            ],

            'environmental_compliance_id' => [
                'nullable',
                'exists:construction_hse_environmental_compliances,id',
            ],

            'action_title' => [
                'required',
                'string',
                'max:255',
            ],

            'action_type' => [
                'required',
                'in:Corrective Action,Preventive Action,Improvement Action,Legal / Compliance Action',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'action_description' => [
                'nullable',
                'string',
            ],

            'root_cause' => [
                'nullable',
                'string',
            ],

            'preventive_action' => [
                'nullable',
                'string',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'assigned_to_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'assigned_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'completion_date' => [
                'nullable',
                'date',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'verification_required' => [
                'nullable',
                'boolean',
            ],

            'verification_status' => [
                'required',
                'in:Pending,Verified,Rejected,Not Required',
            ],

            'verified_by' => [
                'nullable',
                'exists:users,id',
            ],

            'verified_at' => [
                'nullable',
                'date',
            ],

            'verification_remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Source Record
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['environmental_record_id'])) {

            $exists = ConstructionHseEnvironmentalRecord::query()
                ->where('id', $validated['environmental_record_id'])
                ->where('project_id', $project->id)
                ->exists();

            abort_unless($exists, 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Source Compliance
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['environmental_compliance_id'])) {

            $exists = ConstructionHseEnvironmentalCompliance::query()
                ->where('id', $validated['environmental_compliance_id'])
                ->where('project_id', $project->id)
                ->exists();

            abort_unless($exists, 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Assigned User
        |--------------------------------------------------------------------------
        */

        $assignedToName = null;

        if (!empty($validated['assigned_to'])) {

            $assignedToName = User::find(
                $validated['assigned_to']
            )?->name;
        }


        /*
        |--------------------------------------------------------------------------
        | Verification
        |--------------------------------------------------------------------------
        */

        $verifiedBy =
            $validated['verified_by']
            ?? null;

        $verifiedAt = null;

        if ($verifiedBy) {

            $verifiedAt =
                $validated['verified_at']
                ?? now();
        }


        $action->update([

            'action_number' =>
                $validated['action_number'],

            'environmental_record_id' =>
                $validated['environmental_record_id']
                ?? null,

            'environmental_compliance_id' =>
                $validated['environmental_compliance_id']
                ?? null,

            'action_title' =>
                $validated['action_title'],

            'action_type' =>
                $validated['action_type'],

            'priority' =>
                $validated['priority'],

            'action_description' =>
                $validated['action_description']
                ?? null,

            'root_cause' =>
                $validated['root_cause']
                ?? null,

            'preventive_action' =>
                $validated['preventive_action']
                ?? null,

            'assigned_to' =>
                $validated['assigned_to']
                ?? null,

            'assigned_to_name' =>
                $assignedToName
                ?? ($validated['assigned_to_name'] ?? null),

            'assigned_date' =>
                $validated['assigned_date']
                ?? null,

            'due_date' =>
                $validated['due_date']
                ?? null,

            'completion_date' =>
                $validated['completion_date']
                ?? null,

            'completion_remarks' =>
                $validated['completion_remarks']
                ?? null,

            'verification_required' =>
                $request->boolean(
                    'verification_required'
                ),

            'verification_status' =>
                $validated['verification_status'],

            'verified_by' =>
                $verifiedBy,

            'verified_at' =>
                $verifiedAt,

            'verification_remarks' =>
                $validated['verification_remarks']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.actions.show',
                [
                    'project' => $project,
                    'action' => $action,
                ]
            )
            ->with(
                'success',
                'Environmental action updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseEnvironmentalAction $action
    ): RedirectResponse {

        $this->validateActionRelation(
            $project,
            $action
        );

        $action->delete();

        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.actions.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'Environmental action deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateActionRelation(
        Project $project,
        ConstructionHseEnvironmentalAction $action
    ): void {

        abort_unless(
            (int) $action->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATION
    |--------------------------------------------------------------------------
    */

    protected function generateActionNumber(): string
    {
        $lastId =
            ConstructionHseEnvironmentalAction::max('id')
            ?? 0;

        return 'HSE-ENV-ACT-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}