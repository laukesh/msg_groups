<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingAttendee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProjectGovernanceMeetingAttendeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
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
        ]);

        $attendees = $meeting
            ->attendees()
            ->with('user')
            ->orderBy('attendee_name')
            ->get();

        return view(
            'projects.governance-meetings.attendees.index',
            compact(
                'project',
                'meeting',
                'attendees'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'projects.governance-meetings.attendees.create',
            compact(
                'project',
                'meeting',
                'users'
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
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $validated = $request->validate([

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'attendee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'attendee_role' => [
                'nullable',
                'string',
                'max:150',
            ],

            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'attendance_status' => [
                'required',
                'in:Invited,Present,Absent,Apologies',
            ],

            'joined_at' => [
                'nullable',
                'date_format:H:i',
            ],

            'left_at' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:joined_at',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Attendee
        |--------------------------------------------------------------------------
        */

        $duplicateQuery = $meeting
            ->attendees()
            ->where(
                'attendee_name',
                $validated['attendee_name']
            );


        if (!empty($validated['user_id'])) {

            $duplicateQuery->where(
                'user_id',
                $validated['user_id']
            );

        } else {

            $duplicateQuery->whereNull(
                'user_id'
            );
        }


        if ($duplicateQuery->exists()) {

            return back()
                ->withErrors([
                    'attendee_name' =>
                        'This attendee has already been added to this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Attendee
        |--------------------------------------------------------------------------
        */

        $attendee =
            $meeting->attendees()->create([

                'user_id' =>
                    $validated['user_id']
                    ?? null,

                'attendee_name' =>
                    $validated['attendee_name'],

                'attendee_role' =>
                    $validated['attendee_role']
                    ?? null,

                'organization' =>
                    $validated['organization']
                    ?? null,

                'attendance_status' =>
                    $validated['attendance_status'],

                'joined_at' =>
                    $validated['joined_at']
                    ?? null,

                'left_at' =>
                    $validated['left_at']
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
                'admin.projects.governance-meetings.attendees.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Meeting attendee added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAttendee $attendee
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAttendeeOwnership(
            $meeting,
            $attendee
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'projects.governance-meetings.attendees.edit',
            compact(
                'project',
                'meeting',
                'attendee',
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
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAttendee $attendee
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAttendeeOwnership(
            $meeting,
            $attendee
        );

        $validated = $request->validate([

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'attendee_name' => [
                'required',
                'string',
                'max:255',
            ],

            'attendee_role' => [
                'nullable',
                'string',
                'max:150',
            ],

            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'attendance_status' => [
                'required',
                'in:Invited,Present,Absent,Apologies',
            ],

            'joined_at' => [
                'nullable',
                'date_format:H:i',
            ],

            'left_at' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:joined_at',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Attendee
        |--------------------------------------------------------------------------
        */

        $duplicateQuery = $meeting
            ->attendees()
            ->where(
                'attendee_name',
                $validated['attendee_name']
            )
            ->where(
                'id',
                '!=',
                $attendee->id
            );


        if (!empty($validated['user_id'])) {

            $duplicateQuery->where(
                'user_id',
                $validated['user_id']
            );

        } else {

            $duplicateQuery->whereNull(
                'user_id'
            );
        }


        if ($duplicateQuery->exists()) {

            return back()
                ->withErrors([
                    'attendee_name' =>
                        'This attendee has already been added to this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $attendee->update([

            'user_id' =>
                $validated['user_id']
                ?? null,

            'attendee_name' =>
                $validated['attendee_name'],

            'attendee_role' =>
                $validated['attendee_role']
                ?? null,

            'organization' =>
                $validated['organization']
                ?? null,

            'attendance_status' =>
                $validated['attendance_status'],

            'joined_at' =>
                $validated['joined_at']
                ?? null,

            'left_at' =>
                $validated['left_at']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.attendees.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Meeting attendee updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAttendee $attendee
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAttendeeOwnership(
            $meeting,
            $attendee
        );

        $attendee->delete();

        return redirect()
            ->route(
                'admin.projects.governance-meetings.attendees.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Meeting attendee removed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE ATTENDANCE STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAttendee $attendee
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAttendeeOwnership(
            $meeting,
            $attendee
        );

        $validated = $request->validate([

            'attendance_status' => [
                'required',
                'in:Invited,Present,Absent,Apologies',
            ],

        ]);


        $attendee->update([

            'attendance_status' =>
                $validated['attendance_status'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Attendance status updated successfully.'
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


    /*
    |--------------------------------------------------------------------------
    | ATTENDEE OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateAttendeeOwnership(
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAttendee $attendee
    ): void {

        abort_unless(
            (int) $attendee->project_governance_meeting_id ===
            (int) $meeting->id,
            404
        );
    }
}