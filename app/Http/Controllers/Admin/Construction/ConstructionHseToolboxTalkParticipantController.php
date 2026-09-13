<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseToolboxTalk;
use App\Models\ConstructionHseToolboxTalkParticipant;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseToolboxTalkParticipantController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );

        $participants = $toolboxTalk
            ->participants()
            ->with([
                'creator',
                'updater',
            ])
            ->orderBy('id')
            ->get();

        return view(
            'construction.hse.toolbox-talk-participants.index',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
                'participants' => $participants,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );

        return view(
            'construction.hse.toolbox-talk-participants.create',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
                'participant' => null,
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
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): RedirectResponse {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );


        $validated = $request->validate([

            'participant_name' => [
                'required',
                'string',
                'max:255',
            ],

            'participant_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'employee_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'attended' => [
                'nullable',
                'boolean',
            ],

            'attendance_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'signature_path' => [
                'nullable',
                'string',
                'max:500',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        ConstructionHseToolboxTalkParticipant::create([

            'construction_hse_toolbox_talk_id' =>
                $toolboxTalk->id,

            'participant_name' =>
                $validated['participant_name'],

            'participant_type' =>
                $validated['participant_type'] ?? null,

            'employee_code' =>
                $validated['employee_code'] ?? null,

            'company_name' =>
                $validated['company_name'] ?? null,

            'designation' =>
                $validated['designation'] ?? null,

            'phone' =>
                $validated['phone'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'attended' =>
                $request->boolean('attended'),

            'attendance_time' =>
                $validated['attendance_time'] ?? null,

            'signature_path' =>
                $validated['signature_path'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.participants.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            )
            ->with(
                'success',
                'Participant added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkParticipant $participant
    ): View {

        $this->validateParticipantRelation(
            $project,
            $toolboxTalk,
            $participant
        );

        $participant->load([
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.toolbox-talk-participants.show',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
                'participant' => $participant,
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkParticipant $participant
    ): View {

        $this->validateParticipantRelation(
            $project,
            $toolboxTalk,
            $participant
        );

        return view(
            'construction.hse.toolbox-talk-participants.edit',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
                'participant' => $participant,
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkParticipant $participant
    ): RedirectResponse {

        $this->validateParticipantRelation(
            $project,
            $toolboxTalk,
            $participant
        );


        $validated = $request->validate([

            'participant_name' => [
                'required',
                'string',
                'max:255',
            ],

            'participant_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'employee_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'attended' => [
                'nullable',
                'boolean',
            ],

            'attendance_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'signature_path' => [
                'nullable',
                'string',
                'max:500',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $participant->update([

            'participant_name' =>
                $validated['participant_name'],

            'participant_type' =>
                $validated['participant_type'] ?? null,

            'employee_code' =>
                $validated['employee_code'] ?? null,

            'company_name' =>
                $validated['company_name'] ?? null,

            'designation' =>
                $validated['designation'] ?? null,

            'phone' =>
                $validated['phone'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'attended' =>
                $request->boolean('attended'),

            'attendance_time' =>
                $validated['attendance_time'] ?? null,

            'signature_path' =>
                $validated['signature_path'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.participants.show',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                    'participant' => $participant,
                ]
            )
            ->with(
                'success',
                'Participant updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkParticipant $participant
    ): RedirectResponse {

        $this->validateParticipantRelation(
            $project,
            $toolboxTalk,
            $participant
        );

        $participant->delete();

        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.participants.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            )
            ->with(
                'success',
                'Participant deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE TOOLBOX TALK
    |--------------------------------------------------------------------------
    */

    protected function validateTalkRelation(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): void {

        abort_unless(
            (int) $toolboxTalk->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PARTICIPANT
    |--------------------------------------------------------------------------
    */

    protected function validateParticipantRelation(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkParticipant $participant
    ): void {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );

        abort_unless(
            (int) $participant
                ->construction_hse_toolbox_talk_id ===
            (int) $toolboxTalk->id,
            404
        );
    }
}