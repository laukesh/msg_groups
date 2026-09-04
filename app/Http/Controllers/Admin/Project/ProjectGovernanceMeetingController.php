<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernance;
use App\Models\ProjectGovernanceMeeting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProjectGovernanceMeetingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $meetings = ProjectGovernanceMeeting::where(
            'project_id',
            $project->id
        )
            ->with([
                'governance',
                'chairperson',
                'secretary',
            ])
            ->orderByDesc('meeting_date')
            ->orderByDesc('id')
            ->get();

        return view(
            'projects.governance-meetings.index',
            compact(
                'project',
                'meetings'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $governances = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Generate Meeting Number
        |--------------------------------------------------------------------------
        */

        $lastNumber = ProjectGovernanceMeeting::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('meeting_number');

        if ($lastNumber) {

            preg_match(
                '/(\d+)$/',
                $lastNumber,
                $matches
            );

            $nextNumber =
                isset($matches[1])
                    ? ((int) $matches[1]) + 1
                    : 1;

        } else {

            $nextNumber = 1;
        }

        $meetingNumber =
            'GOV-MTG-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

        return view(
            'projects.governance-meetings.create',
            compact(
                'project',
                'governances',
                'users',
                'meetingNumber'
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
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'meeting_number' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'project_governance_meetings',
                    'meeting_number'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'project_id',
                            $project->id
                        )
                ),
            ],

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'meeting_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:start_time',
            ],

            'meeting_type' => [
                'required',
                'string',
                'max:150',
            ],

            'committee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meeting_mode' => [
                'required',
                'in:Physical,Virtual,Hybrid',
            ],

            'chairperson_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'secretary_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'agenda' => [
                'nullable',
                'string',
            ],

            'minutes' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Scheduled,Held,Cancelled,Postponed',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Governance Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['project_governance_id']
            )
        ) {

            $governanceExists =
                ProjectGovernance::where(
                    'id',
                    $validated['project_governance_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();

            if (!$governanceExists) {

                return back()
                    ->withErrors([
                        'project_governance_id' =>
                            'The selected governance framework does not belong to this project.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Held Meeting
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Held' &&
            empty($validated['minutes'])
        ) {

            return back()
                ->withErrors([
                    'minutes' =>
                        'Minutes are required when the meeting status is Held.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Meeting
        |--------------------------------------------------------------------------
        */

        $meeting =
            ProjectGovernanceMeeting::create([

                'project_id' =>
                    $project->id,

                'project_governance_id' =>
                    $validated['project_governance_id']
                    ?? null,

                'meeting_number' =>
                    $validated['meeting_number'],

                'meeting_date' =>
                    $validated['meeting_date'],

                'start_time' =>
                    $validated['start_time']
                    ?? null,

                'end_time' =>
                    $validated['end_time']
                    ?? null,

                'meeting_type' =>
                    $validated['meeting_type'],

                'committee_name' =>
                    $validated['committee_name'],

                'location' =>
                    $validated['location']
                    ?? null,

                'meeting_mode' =>
                    $validated['meeting_mode'],

                'chairperson_id' =>
                    $validated['chairperson_id']
                    ?? null,

                'secretary_id' =>
                    $validated['secretary_id']
                    ?? null,

                'agenda' =>
                    $validated['agenda']
                    ?? null,

                'minutes' =>
                    $validated['minutes']
                    ?? null,

                'status' =>
                    $validated['status'],

                'reference_number' =>
                    $validated['reference_number']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.show',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Governance meeting created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $meeting->load([
            'governance',
            'chairperson',
            'secretary',
            'attendees',
            'agendaItems',
            'decisions',
            'actionItems',

            'officialMinutes.preparer',
            'officialMinutes.approver',
            'documents.uploader',
        ]);

        $meeting->loadCount([
            'attendees',
            'agendaItems',
            'decisions',
            'actionItems',
        ]);

        return view(
            'projects.governance-meetings.show',
            compact(
                'project',
                'meeting'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $governances = ProjectGovernance::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'projects.governance-meetings.edit',
            compact(
                'project',
                'meeting',
                'governances',
                'users'
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
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $validated = $request->validate([

            'project_governance_id' => [
                'nullable',
                'integer',
                'exists:project_governance,id',
            ],

            'meeting_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:start_time',
            ],

            'meeting_type' => [
                'required',
                'string',
                'max:150',
            ],

            'committee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meeting_mode' => [
                'required',
                'in:Physical,Virtual,Hybrid',
            ],

            'chairperson_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'secretary_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'agenda' => [
                'nullable',
                'string',
            ],

            'minutes' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Scheduled,Held,Cancelled,Postponed',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Governance Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['project_governance_id']
            )
        ) {

            $governanceExists =
                ProjectGovernance::where(
                    'id',
                    $validated['project_governance_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();

            if (!$governanceExists) {

                return back()
                    ->withErrors([
                        'project_governance_id' =>
                            'The selected governance framework does not belong to this project.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Held Meeting
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Held' &&
            empty($validated['minutes'])
        ) {

            return back()
                ->withErrors([
                    'minutes' =>
                        'Minutes are required when the meeting status is Held.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update Meeting
        |--------------------------------------------------------------------------
        */

        $meeting->update([

            'project_governance_id' =>
                $validated['project_governance_id']
                ?? null,

            'meeting_date' =>
                $validated['meeting_date'],

            'start_time' =>
                $validated['start_time']
                ?? null,

            'end_time' =>
                $validated['end_time']
                ?? null,

            'meeting_type' =>
                $validated['meeting_type'],

            'committee_name' =>
                $validated['committee_name'],

            'location' =>
                $validated['location']
                ?? null,

            'meeting_mode' =>
                $validated['meeting_mode'],

            'chairperson_id' =>
                $validated['chairperson_id']
                ?? null,

            'secretary_id' =>
                $validated['secretary_id']
                ?? null,

            'agenda' =>
                $validated['agenda']
                ?? null,

            'minutes' =>
                $validated['minutes']
                ?? null,

            'status' =>
                $validated['status'],

            'reference_number' =>
                $validated['reference_number']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.show',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Governance meeting updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $meeting->delete();

        return redirect()
            ->route(
                'admin.projects.governance-meetings.index',
                [
                    'project' =>
                        $project->id,
                ]
            )
            ->with(
                'success',
                'Governance meeting deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $validated = $request->validate([

            'status' => [
                'required',
                'in:Scheduled,Held,Cancelled,Postponed',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Held requires minutes
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Held' &&
            empty($meeting->minutes)
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Minutes must be entered before marking the meeting as Held.',
                ]);
        }


        $meeting->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Meeting status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): void {

        abort_unless(
            (int) $meeting->project_id ===
            (int) $project->id,
            404
        );
    }
}