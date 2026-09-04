<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingMinutes;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProjectGovernanceMeetingMinutesController extends Controller
{
    /**
     * Display the meeting minutes.
     */
    public function index(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        $minutes = $meeting->officialMinutes()
            ->with([
                'preparer',
                'approver',
            ])
            ->first();

        return view(
            'projects.governance-meetings.minutes.index',
            compact(
                'project',
                'meeting',
                'minutes'
            )
        );
    }


    /**
     * Show form to create meeting minutes.
     */
    public function create(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        /*
         * Do not allow more than one structured
         * minutes record for a meeting.
         */
        $existingMinutes = $meeting->officialMinutes()->first();

        if ($existingMinutes) {

            return redirect()->route(
                'admin.projects.governance-meetings.minutes.edit',
                [
                    'project' => $project->id,
                    'meeting' => $meeting->id,
                    'minutes' => $existingMinutes->id,
                ]
            );
        }


        /*
         * Generate next minutes number.
         */
        $nextMinutesNumber =
            $this->generateMinutesNumber($project, $meeting);


        /*
         * Users available for preparation / approval.
         */
        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.governance-meetings.minutes.create',
            compact(
                'project',
                'meeting',
                'users',
                'nextMinutesNumber'
            )
        );
    }


    /**
     * Store meeting minutes.
     */
    public function store(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );


        /*
         * Prevent duplicate minutes.
         */
        if ($meeting->officialMinutes()->exists()) {

            return redirect()
                ->route(
                    'admin.projects.governance-meetings.minutes.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                )
                ->with(
                    'error',
                    'Meeting minutes already exist for this meeting.'
                );
        }


        $validated = $request->validate([

            'minutes_number' => [
                'required',
                'string',
                'max:100',
            ],

            'prepared_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'prepared_date' => [
                'nullable',
                'date',
            ],

            'minutes_status' => [
                'required',
                'in:Draft,Submitted,Approved,Rejected',
            ],

            'approved_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],

            'opening_summary' => [
                'nullable',
                'string',
            ],

            'attendance_summary' => [
                'nullable',
                'string',
            ],

            'discussion_summary' => [
                'nullable',
                'string',
            ],

            'key_matters_discussed' => [
                'nullable',
                'string',
            ],

            'decisions_summary' => [
                'nullable',
                'string',
            ],

            'action_summary' => [
                'nullable',
                'string',
            ],

            'closing_summary' => [
                'nullable',
                'string',
            ],

            'general_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
         * Approval validation.
         */
        if (
            $validated['minutes_status'] === 'Approved'
            && empty($validated['approved_by'])
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'approved_by' =>
                        'Approved By is required when minutes are approved.',
                ]);
        }


        /*
         * If approved and no approval date supplied,
         * use today's date.
         */
        if (
            $validated['minutes_status'] === 'Approved'
            && empty($validated['approval_date'])
        ) {

            $validated['approval_date'] =
                now()->format('Y-m-d');
        }


        /*
         * Create structured minutes record.
         */
        $minutes = DB::transaction(function () use (
            $validated,
            $project,
            $meeting
        ) {

            return ProjectGovernanceMeetingMinutes::create([

                'project_id' =>
                    $project->id,

                'project_governance_meeting_id' =>
                    $meeting->id,

                'minutes_number' =>
                    $validated['minutes_number'],

                'prepared_by' =>
                    $validated['prepared_by'] ?? null,

                'prepared_date' =>
                    $validated['prepared_date'] ?? null,

                'minutes_status' =>
                    $validated['minutes_status'],

                'approved_by' =>
                    $validated['approved_by'] ?? null,

                'approval_date' =>
                    $validated['approval_date'] ?? null,

                'opening_summary' =>
                    $validated['opening_summary'] ?? null,

                'attendance_summary' =>
                    $validated['attendance_summary'] ?? null,

                'discussion_summary' =>
                    $validated['discussion_summary'] ?? null,

                'key_matters_discussed' =>
                    $validated['key_matters_discussed'] ?? null,

                'decisions_summary' =>
                    $validated['decisions_summary'] ?? null,

                'action_summary' =>
                    $validated['action_summary'] ?? null,

                'closing_summary' =>
                    $validated['closing_summary'] ?? null,

                'general_remarks' =>
                    $validated['general_remarks'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

            ]);
        });


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes created successfully.'
        );
    }


    /**
     * Show meeting minutes.
     */
    public function show(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        $minutes->load([
            'preparer',
            'approver',
            'meeting',
        ]);


        return view(
            'projects.governance-meetings.minutes.show',
            compact(
                'project',
                'meeting',
                'minutes'
            )
        );
    }


    /**
     * Show form to edit meeting minutes.
     */
    public function edit(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Approved minutes are locked.
         */
        if ($minutes->minutes_status === 'Approved') {

            return redirect()
                ->route(
                    'admin.projects.governance-meetings.minutes.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                )
                ->with(
                    'error',
                    'Approved meeting minutes cannot be edited.'
                );
        }


        $minutes->load([
            'preparer',
            'approver',
        ]);


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.governance-meetings.minutes.edit',
            compact(
                'project',
                'meeting',
                'minutes',
                'users'
            )
        );
    }


    /**
     * Update meeting minutes.
     */
    public function update(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Approved minutes cannot be changed.
         */
        if ($minutes->minutes_status === 'Approved') {

            return back()->with(
                'error',
                'Approved meeting minutes cannot be modified.'
            );
        }


        $validated = $request->validate([

            'minutes_number' => [
                'required',
                'string',
                'max:100',
            ],

            'prepared_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'prepared_date' => [
                'nullable',
                'date',
            ],

            'minutes_status' => [
                'required',
                'in:Draft,Submitted,Approved,Rejected',
            ],

            'approved_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],

            'opening_summary' => [
                'nullable',
                'string',
            ],

            'attendance_summary' => [
                'nullable',
                'string',
            ],

            'discussion_summary' => [
                'nullable',
                'string',
            ],

            'key_matters_discussed' => [
                'nullable',
                'string',
            ],

            'decisions_summary' => [
                'nullable',
                'string',
            ],

            'action_summary' => [
                'nullable',
                'string',
            ],

            'closing_summary' => [
                'nullable',
                'string',
            ],

            'general_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
         * Approval validation.
         */
        if (
            $validated['minutes_status'] === 'Approved'
            && empty($validated['approved_by'])
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'approved_by' =>
                        'Approved By is required when minutes are approved.',
                ]);
        }


        if (
            $validated['minutes_status'] === 'Approved'
            && empty($validated['approval_date'])
        ) {

            $validated['approval_date'] =
                now()->format('Y-m-d');
        }


        /*
         * Update record.
         */
        $minutes->update([

            'minutes_number' =>
                $validated['minutes_number'],

            'prepared_by' =>
                $validated['prepared_by'] ?? null,

            'prepared_date' =>
                $validated['prepared_date'] ?? null,

            'minutes_status' =>
                $validated['minutes_status'],

            'approved_by' =>
                $validated['approved_by'] ?? null,

            'approval_date' =>
                $validated['approval_date'] ?? null,

            'opening_summary' =>
                $validated['opening_summary'] ?? null,

            'attendance_summary' =>
                $validated['attendance_summary'] ?? null,

            'discussion_summary' =>
                $validated['discussion_summary'] ?? null,

            'key_matters_discussed' =>
                $validated['key_matters_discussed'] ?? null,

            'decisions_summary' =>
                $validated['decisions_summary'] ?? null,

            'action_summary' =>
                $validated['action_summary'] ?? null,

            'closing_summary' =>
                $validated['closing_summary'] ?? null,

            'general_remarks' =>
                $validated['general_remarks'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

        ]);


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes updated successfully.'
        );
    }


    /**
     * Delete meeting minutes.
     */
    public function destroy(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Do not delete approved minutes.
         */
        if ($minutes->minutes_status === 'Approved') {

            return back()->with(
                'error',
                'Approved meeting minutes cannot be deleted.'
            );
        }


        $minutes->delete();


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes deleted successfully.'
        );
    }


    /**
     * Generate next minutes number.
     */
    private function generateMinutesNumber(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): string {

        $year = now()->format('Y');

        $count = ProjectGovernanceMeetingMinutes::query()
            ->whereHas('meeting', function ($query) use ($project) {

                $query->where(
                    'project_id',
                    $project->id
                );

            })
            ->whereYear('created_at', $year)
            ->count();

        $sequence = $count + 1;

        return sprintf(
            'MIN-%s-%04d',
            $year,
            $sequence
        );
    }

    /**
     * Submit meeting minutes for approval.
     */
    public function submit(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Approved minutes cannot be submitted again.
         */
        if ($minutes->minutes_status === 'Approved') {

            return back()->with(
                'error',
                'Approved meeting minutes cannot be submitted again.'
            );
        }


        /*
         * Only Draft or Rejected minutes can be submitted.
         */
        if (
            !in_array(
                $minutes->minutes_status,
                ['Draft', 'Rejected'],
                true
            )
        ) {

            return back()->with(
                'error',
                'Only Draft or Rejected minutes can be submitted for approval.'
            );
        }


        /*
         * Basic content check.
         *
         * Prevent completely empty minutes from being submitted.
         */
        $hasContent =
            $minutes->opening_summary ||
            $minutes->attendance_summary ||
            $minutes->discussion_summary ||
            $minutes->key_matters_discussed ||
            $minutes->decisions_summary ||
            $minutes->action_summary ||
            $minutes->closing_summary ||
            $minutes->general_remarks;


        if (!$hasContent) {

            return back()->with(
                'error',
                'Please enter meeting minutes before submitting them for approval.'
            );
        }


        /*
         * Submit.
         */
        $minutes->update([
            'minutes_status' => 'Submitted',
        ]);


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes submitted for approval successfully.'
        );
    }

    /**
     * Approve meeting minutes.
     */
    public function approve(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Only Submitted minutes can be approved.
         */
        if ($minutes->minutes_status !== 'Submitted') {

            return back()->with(
                'error',
                'Only submitted meeting minutes can be approved.'
            );
        }


        $validated = $request->validate([
            'approved_by' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],
        ]);


        $minutes->update([

            'minutes_status' => 'Approved',

            'approved_by' =>
                $validated['approved_by'],

            'approval_date' =>
                $validated['approval_date']
                    ?? now()->format('Y-m-d'),

        ]);


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes approved successfully.'
        );
    }

    /**
     * Reject / return meeting minutes for revision.
     */
    public function reject(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingMinutes $minutes
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $minutes->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        if ($minutes->minutes_status !== 'Submitted') {

            return back()->with(
                'error',
                'Only submitted meeting minutes can be rejected.'
            );
        }


        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);


        $minutes->update([

            'minutes_status' => 'Rejected',

            'remarks' =>
                $validated['remarks'],

        ]);


        return redirect()->route(
            'admin.projects.governance-meetings.minutes.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting minutes returned for revision.'
        );
    }
}