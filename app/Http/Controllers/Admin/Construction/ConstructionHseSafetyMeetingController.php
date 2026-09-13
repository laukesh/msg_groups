<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseSafetyMeeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseSafetyMeetingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $meetings = ConstructionHseSafetyMeeting::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'conductedBy',
                'creator',
                'updater',
            ])
            ->latest('meeting_date')
            ->latest('id')
            ->get();

        return view(
            'construction.hse.safety-meetings.index',
            [
                'project' => $project,
                'meetings' => $meetings,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        $meetingNumber =
            $this->generateMeetingNumber();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.safety-meetings.create',
            [
                'project' => $project,
                'meetingNumber' => $meetingNumber,
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

            'meeting_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_safety_meetings,meeting_number',
            ],

            'meeting_date' => [
                'required',
                'date',
            ],

            'meeting_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'meeting_type' => [
                'required',
                'string',
                'max:100',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conducted_by' => [
                'nullable',
                'exists:users,id',
            ],

            'conducted_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meeting_objective' => [
                'nullable',
                'string',
            ],

            'agenda' => [
                'nullable',
                'string',
            ],

            'discussion_points' => [
                'nullable',
                'string',
            ],

            'safety_instructions' => [
                'nullable',
                'string',
            ],

            'actions_commitments' => [
                'nullable',
                'string',
            ],

            'next_meeting_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Draft,Scheduled,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $meeting =
            ConstructionHseSafetyMeeting::create([

                'project_id' =>
                    $project->id,

                'meeting_number' =>
                    $validated['meeting_number'],

                'meeting_date' =>
                    $validated['meeting_date'],

                'meeting_time' =>
                    $validated['meeting_time'] ?? null,

                'meeting_type' =>
                    $validated['meeting_type'],

                'title' =>
                    $validated['title'],

                'location' =>
                    $validated['location'] ?? null,

                'conducted_by' =>
                    $validated['conducted_by'] ?? null,

                'conducted_by_name' =>
                    $validated['conducted_by_name'] ?? null,

                'meeting_objective' =>
                    $validated['meeting_objective'] ?? null,

                'agenda' =>
                    $validated['agenda'] ?? null,

                'discussion_points' =>
                    $validated['discussion_points'] ?? null,

                'safety_instructions' =>
                    $validated['safety_instructions'] ?? null,

                'actions_commitments' =>
                    $validated['actions_commitments'] ?? null,

                'next_meeting_date' =>
                    $validated['next_meeting_date'] ?? null,

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
                'admin.projects.construction.hse.safety-meetings.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            )
            ->with(
                'success',
                'Safety meeting created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        $meeting->load([
            'conductedBy',
            'creator',
            'updater',
            'participants',
            'documents',
        ]);

        return view(
            'construction.hse.safety-meetings.show',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.safety-meetings.edit',
            [
                'project' => $project,
                'meeting' => $meeting,
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
        ConstructionHseSafetyMeeting $meeting
    ): RedirectResponse {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );


        $validated = $request->validate([

            'meeting_date' => [
                'required',
                'date',
            ],

            'meeting_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'meeting_type' => [
                'required',
                'string',
                'max:100',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conducted_by' => [
                'nullable',
                'exists:users,id',
            ],

            'conducted_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meeting_objective' => [
                'nullable',
                'string',
            ],

            'agenda' => [
                'nullable',
                'string',
            ],

            'discussion_points' => [
                'nullable',
                'string',
            ],

            'safety_instructions' => [
                'nullable',
                'string',
            ],

            'actions_commitments' => [
                'nullable',
                'string',
            ],

            'next_meeting_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Draft,Scheduled,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $meeting->update([

            'meeting_date' =>
                $validated['meeting_date'],

            'meeting_time' =>
                $validated['meeting_time'] ?? null,

            'meeting_type' =>
                $validated['meeting_type'],

            'title' =>
                $validated['title'],

            'location' =>
                $validated['location'] ?? null,

            'conducted_by' =>
                $validated['conducted_by'] ?? null,

            'conducted_by_name' =>
                $validated['conducted_by_name'] ?? null,

            'meeting_objective' =>
                $validated['meeting_objective'] ?? null,

            'agenda' =>
                $validated['agenda'] ?? null,

            'discussion_points' =>
                $validated['discussion_points'] ?? null,

            'safety_instructions' =>
                $validated['safety_instructions'] ?? null,

            'actions_commitments' =>
                $validated['actions_commitments'] ?? null,

            'next_meeting_date' =>
                $validated['next_meeting_date'] ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.show',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            )
            ->with(
                'success',
                'Safety meeting updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): RedirectResponse {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );


        $meeting->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'Safety meeting deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE RELATION
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
    | GENERATE MEETING NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateMeetingNumber(): string
    {
        $lastId =
            ConstructionHseSafetyMeeting::max('id') ?? 0;

        return 'HSE-SM-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}