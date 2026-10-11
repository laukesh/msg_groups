<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseSafetyMeeting;
use App\Models\ConstructionHseSafetyMeetingParticipant;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseSafetyMeetingParticipantController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        $participants = $meeting
            ->participants()
            ->with([
                'creator',
                'updater',
            ])
            ->orderBy('participant_name')
            ->get();

        return view(
            'construction.hse.safety-meeting-participants.index',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        return view(
            'construction.hse.safety-meeting-participants.create',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting
    ): RedirectResponse {

        $this->validateMeetingRelation(
            $project,
            $meeting
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

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $participant =
            ConstructionHseSafetyMeetingParticipant::create([

                'construction_hse_safety_meeting_id' =>
                    $meeting->id,

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
                    $request->boolean('attended', true),

                'attendance_time' =>
                    $validated['attendance_time'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.participants.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingParticipant $participant
    ): View {

        $this->validateParticipantRelation(
            $project,
            $meeting,
            $participant
        );

        $participant->load([
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.safety-meeting-participants.show',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingParticipant $participant
    ): View {

        $this->validateParticipantRelation(
            $project,
            $meeting,
            $participant
        );

        return view(
            'construction.hse.safety-meeting-participants.edit',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingParticipant $participant
    ): RedirectResponse {

        $this->validateParticipantRelation(
            $project,
            $meeting,
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

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.participants.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingParticipant $participant
    ): RedirectResponse {

        $this->validateParticipantRelation(
            $project,
            $meeting,
            $participant
        );


        $participant->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.participants.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            )
            ->with(
                'success',
                'Participant deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE MEETING
    |--------------------------------------------------------------------------
    */

    protected function validateMeetingRelation(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): void {

        abort_unless(
            (int) $meeting->project_id ===
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
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingParticipant $participant
    ): void {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );


        abort_unless(
            (int) $participant->construction_hse_safety_meeting_id ===
            (int) $meeting->id,
            404
        );
    }
}