<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingAgendaItem;
use App\Models\ProjectGovernanceMeetingDecision;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectGovernanceMeetingDecisionController extends Controller
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

        $decisions = $meeting
            ->decisions()
            ->with([
                'agendaItem',
                'approver',
            ])
            ->orderBy('decision_no')
            ->get();

        return view(
            'projects.governance-meetings.decisions.index',
            compact(
                'project',
                'meeting',
                'decisions'
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

        $agendaItems = $meeting
            ->agendaItems()
            ->orderBy('item_no')
            ->get();

        $nextDecisionNo =
            ((int) $meeting
                ->decisions()
                ->max('decision_no')) + 1;

        return view(
            'projects.governance-meetings.decisions.create',
            compact(
                'project',
                'meeting',
                'users',
                'agendaItems',
                'nextDecisionNo'
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

            'decision_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'project_governance_meeting_agenda_item_id' => [
                'nullable',
                'integer',
                'exists:project_governance_meeting_agenda_items,id',
            ],

            'decision_title' => [
                'required',
                'string',
                'max:255',
            ],

            'decision_text' => [
                'required',
                'string',
            ],

            'decision_type' => [
                'required',
                'in:Approval,Direction,Resolution,Recommendation,Information',
            ],

            'decision_status' => [
                'required',
                'in:Draft,Approved,Rejected,Deferred,Superseded',
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

            'effective_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Agenda Item Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ]
            )
        ) {

            $agendaItemExists = $meeting
                ->agendaItems()
                ->where(
                    'id',
                    $validated[
                        'project_governance_meeting_agenda_item_id'
                    ]
                )
                ->exists();


            if (!$agendaItemExists) {

                return back()
                    ->withErrors([
                        'project_governance_meeting_agenda_item_id' =>
                            'The selected agenda item does not belong to this meeting.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Decision Number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->decisions()
            ->where(
                'decision_no',
                $validated['decision_no']
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'decision_no' =>
                        'This decision number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Approval Information
        |--------------------------------------------------------------------------
        */

        $approvedBy =
            $validated['approved_by']
            ?? null;

        $approvalDate =
            $validated['approval_date']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | Approved Decision
        |--------------------------------------------------------------------------
        */

        if (
            $validated['decision_status'] === 'Approved'
        ) {

            if (!$approvedBy) {

                return back()
                    ->withErrors([
                        'approved_by' =>
                            'Approved By is required when the decision status is Approved.',
                    ])
                    ->withInput();
            }


            if (!$approvalDate) {

                $approvalDate =
                    now()->toDateString();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Non-approved Decision
        |--------------------------------------------------------------------------
        */

        if (
            $validated['decision_status'] !== 'Approved'
        ) {

            $approvedBy = null;
            $approvalDate = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Decision
        |--------------------------------------------------------------------------
        */

        $meeting->decisions()->create([

            'project_governance_meeting_agenda_item_id' =>
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ] ?? null,

            'decision_no' =>
                $validated['decision_no'],

            'decision_title' =>
                $validated['decision_title'],

            'decision_text' =>
                $validated['decision_text'],

            'decision_type' =>
                $validated['decision_type'],

            'decision_status' =>
                $validated['decision_status'],

            'approved_by' =>
                $approvedBy,

            'approval_date' =>
                $approvalDate,

            'effective_date' =>
                $validated['effective_date']
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
                'admin.projects.governance-meetings.decisions.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Decision / resolution added successfully.'
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
        ProjectGovernanceMeetingDecision $decision
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateDecisionOwnership(
            $meeting,
            $decision
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        $agendaItems = $meeting
            ->agendaItems()
            ->orderBy('item_no')
            ->get();

        return view(
            'projects.governance-meetings.decisions.edit',
            compact(
                'project',
                'meeting',
                'decision',
                'users',
                'agendaItems'
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
        ProjectGovernanceMeetingDecision $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateDecisionOwnership(
            $meeting,
            $decision
        );

        $validated = $request->validate([

            'decision_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'project_governance_meeting_agenda_item_id' => [
                'nullable',
                'integer',
                'exists:project_governance_meeting_agenda_items,id',
            ],

            'decision_title' => [
                'required',
                'string',
                'max:255',
            ],

            'decision_text' => [
                'required',
                'string',
            ],

            'decision_type' => [
                'required',
                'in:Approval,Direction,Resolution,Recommendation,Information',
            ],

            'decision_status' => [
                'required',
                'in:Draft,Approved,Rejected,Deferred,Superseded',
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

            'effective_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Agenda Item Ownership
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ]
            )
        ) {

            $agendaItemExists = $meeting
                ->agendaItems()
                ->where(
                    'id',
                    $validated[
                        'project_governance_meeting_agenda_item_id'
                    ]
                )
                ->exists();


            if (!$agendaItemExists) {

                return back()
                    ->withErrors([
                        'project_governance_meeting_agenda_item_id' =>
                            'The selected agenda item does not belong to this meeting.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Decision Number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->decisions()
            ->where(
                'decision_no',
                $validated['decision_no']
            )
            ->where(
                'id',
                '!=',
                $decision->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'decision_no' =>
                        'This decision number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Approval Information
        |--------------------------------------------------------------------------
        */

        $approvedBy =
            $validated['approved_by']
            ?? null;

        $approvalDate =
            $validated['approval_date']
            ?? null;


        if (
            $validated['decision_status'] === 'Approved'
        ) {

            if (!$approvedBy) {

                return back()
                    ->withErrors([
                        'approved_by' =>
                            'Approved By is required when the decision status is Approved.',
                    ])
                    ->withInput();
            }


            if (!$approvalDate) {

                $approvalDate =
                    now()->toDateString();
            }
        }


        if (
            $validated['decision_status'] !== 'Approved'
        ) {

            $approvedBy = null;
            $approvalDate = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Decision
        |--------------------------------------------------------------------------
        */

        $decision->update([

            'project_governance_meeting_agenda_item_id' =>
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ] ?? null,

            'decision_no' =>
                $validated['decision_no'],

            'decision_title' =>
                $validated['decision_title'],

            'decision_text' =>
                $validated['decision_text'],

            'decision_type' =>
                $validated['decision_type'],

            'decision_status' =>
                $validated['decision_status'],

            'approved_by' =>
                $approvedBy,

            'approval_date' =>
                $approvalDate,

            'effective_date' =>
                $validated['effective_date']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.decisions.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Decision / resolution updated successfully.'
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
        ProjectGovernanceMeetingDecision $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateDecisionOwnership(
            $meeting,
            $decision
        );

        $decision->delete();

        return redirect()
            ->route(
                'admin.projects.governance-meetings.decisions.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Decision / resolution deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingDecision $decision
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateDecisionOwnership(
            $meeting,
            $decision
        );


        $validated = $request->validate([

            'decision_status' => [
                'required',
                'in:Draft,Approved,Rejected,Deferred,Superseded',
            ],

        ]);


        $status = $validated['decision_status'];


        /*
         * ---------------------------------------------------------
         * Prepare update data
         * ---------------------------------------------------------
         */

        $updateData = [

            'decision_status' =>
                $status,

            'updated_by' =>
                auth()->id(),

        ];


        /*
         * ---------------------------------------------------------
         * Approved
         * ---------------------------------------------------------
         *
         * When a decision is approved:
         *
         * approved_by    = current logged-in user
         * approval_date  = existing date OR today
         */

        if ($status === 'Approved') {

            $updateData['approved_by'] =
                auth()->id();

            $updateData['approval_date'] =
                $decision->approval_date
                ?? now()->toDateString();

        }


        /*
         * ---------------------------------------------------------
         * Any non-approved status
         * ---------------------------------------------------------
         */

        else {

            $updateData['approved_by'] = null;

            $updateData['approval_date'] = null;

        }


        /*
         * ---------------------------------------------------------
         * Update
         * ---------------------------------------------------------
         */

        $decision->update(
            $updateData
        );


        return back()
            ->with(
                'success',
                'Decision status updated successfully.'
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
    | DECISION OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateDecisionOwnership(
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingDecision $decision
    ): void {

        abort_unless(
            (int) $decision->project_governance_meeting_id ===
            (int) $meeting->id,
            404
        );
    }
}