<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseEnvironmentalRecord;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseEnvironmentalRecordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $records = ConstructionHseEnvironmentalRecord::query()
            ->where('project_id', $project->id)
            ->with([
                'responsiblePerson',
                'creator',
                'updater',
            ])
            ->latest('monitoring_date')
            ->latest('id')
            ->get();

        return view(
            'construction.hse.environmental-records.index',
            [
                'project' => $project,
                'records' => $records,
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
        $recordNumber = $this->generateRecordNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.environmental-records.create',
            [
                'project' => $project,
                'recordNumber' => $recordNumber,
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

            'record_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_records,record_number',
            ],

            'record_title' => [
                'required',
                'string',
                'max:255',
            ],

            'record_type' => [
                'required',
                'string',
                'max:100',
            ],

            'monitoring_date' => [
                'required',
                'date',
            ],

            'monitoring_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'monitoring_area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'environmental_parameter' => [
                'nullable',
                'string',
                'max:255',
            ],

            'parameter_value' => [
                'nullable',
                'numeric',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'limit_value' => [
                'nullable',
                'numeric',
            ],

            'compliance_status' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Not Applicable',
            ],

            'weather_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'observation' => [
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

            'responsible_person_id' => [
                'nullable',
                'exists:users,id',
            ],

            'responsible_person_name' => [
                'nullable',
                'string',
                'max:255',
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


        /*
        |--------------------------------------------------------------------------
        | Responsible Person
        |--------------------------------------------------------------------------
        */

        $responsiblePersonName = null;

        if (!empty($validated['responsible_person_id'])) {

            $responsiblePersonName = User::find(
                $validated['responsible_person_id']
            )?->name;
        }


        $record = ConstructionHseEnvironmentalRecord::create([

            'project_id' =>
                $project->id,

            'record_number' =>
                $validated['record_number'],

            'record_title' =>
                $validated['record_title'],

            'record_type' =>
                $validated['record_type'],

            'monitoring_date' =>
                $validated['monitoring_date'],

            'monitoring_time' =>
                $validated['monitoring_time'] ?? null,

            'location' =>
                $validated['location'] ?? null,

            'monitoring_area' =>
                $validated['monitoring_area'] ?? null,

            'environmental_parameter' =>
                $validated['environmental_parameter'] ?? null,

            'parameter_value' =>
                $validated['parameter_value'] ?? null,

            'unit' =>
                $validated['unit'] ?? null,

            'limit_value' =>
                $validated['limit_value'] ?? null,

            'compliance_status' =>
                $validated['compliance_status'],

            'weather_condition' =>
                $validated['weather_condition'] ?? null,

            'observation' =>
                $validated['observation'] ?? null,

            'corrective_action_required' =>
                $request->boolean(
                    'corrective_action_required'
                ),

            'corrective_action' =>
                $validated['corrective_action'] ?? null,

            'responsible_person_id' =>
                $validated['responsible_person_id'] ?? null,

            'responsible_person_name' =>
                $responsiblePersonName
                ?? ($validated['responsible_person_name'] ?? null),

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
                'admin.projects.construction.hse.environmental.records.show',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            )
            ->with(
                'success',
                'Environmental record created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseEnvironmentalRecord $record
    ): View {

        $this->validateRecordRelation(
            $project,
            $record
        );

        $record->load([
            'responsiblePerson',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.environmental-records.show',
            [
                'project' => $project,
                'record' => $record,
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
        ConstructionHseEnvironmentalRecord $record
    ): View {

        $this->validateRecordRelation(
            $project,
            $record
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.environmental-records.edit',
            [
                'project' => $project,
                'record' => $record,
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
        ConstructionHseEnvironmentalRecord $record
    ): RedirectResponse {

        $this->validateRecordRelation(
            $project,
            $record
        );


        $validated = $request->validate([

            'record_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_environmental_records,record_number,' .
                    $record->id,
            ],

            'record_title' => [
                'required',
                'string',
                'max:255',
            ],

            'record_type' => [
                'required',
                'string',
                'max:100',
            ],

            'monitoring_date' => [
                'required',
                'date',
            ],

            'monitoring_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'monitoring_area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'environmental_parameter' => [
                'nullable',
                'string',
                'max:255',
            ],

            'parameter_value' => [
                'nullable',
                'numeric',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'limit_value' => [
                'nullable',
                'numeric',
            ],

            'compliance_status' => [
                'required',
                'in:Pending,Compliant,Non-Compliant,Not Applicable',
            ],

            'weather_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'observation' => [
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

            'responsible_person_id' => [
                'nullable',
                'exists:users,id',
            ],

            'responsible_person_name' => [
                'nullable',
                'string',
                'max:255',
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


        $record->update([

            'record_number' =>
                $validated['record_number'],

            'record_title' =>
                $validated['record_title'],

            'record_type' =>
                $validated['record_type'],

            'monitoring_date' =>
                $validated['monitoring_date'],

            'monitoring_time' =>
                $validated['monitoring_time'] ?? null,

            'location' =>
                $validated['location'] ?? null,

            'monitoring_area' =>
                $validated['monitoring_area'] ?? null,

            'environmental_parameter' =>
                $validated['environmental_parameter'] ?? null,

            'parameter_value' =>
                $validated['parameter_value'] ?? null,

            'unit' =>
                $validated['unit'] ?? null,

            'limit_value' =>
                $validated['limit_value'] ?? null,

            'compliance_status' =>
                $validated['compliance_status'],

            'weather_condition' =>
                $validated['weather_condition'] ?? null,

            'observation' =>
                $validated['observation'] ?? null,

            'corrective_action_required' =>
                $request->boolean(
                    'corrective_action_required'
                ),

            'corrective_action' =>
                $validated['corrective_action'] ?? null,

            'responsible_person_id' =>
                $validated['responsible_person_id'] ?? null,

            'responsible_person_name' =>
                $responsiblePersonName
                ?? ($validated['responsible_person_name'] ?? null),

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.records.show',
                [
                    'project' => $project,
                    'record' => $record,
                ]
            )
            ->with(
                'success',
                'Environmental record updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseEnvironmentalRecord $record
    ): RedirectResponse {

        $this->validateRecordRelation(
            $project,
            $record
        );

        $record->delete();

        return redirect()
            ->route(
                'admin.projects.construction.hse.environmental.records.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'Environmental record deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PROJECT RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateRecordRelation(
        Project $project,
        ConstructionHseEnvironmentalRecord $record
    ): void {

        abort_unless(
            (int) $record->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE RECORD NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateRecordNumber(): string
    {
        $lastId =
            ConstructionHseEnvironmentalRecord::max('id')
            ?? 0;

        return 'HSE-ENV-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}