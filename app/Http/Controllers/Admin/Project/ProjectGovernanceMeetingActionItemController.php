<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingActionItem;
use App\Models\ProjectGovernanceMeetingAgendaItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectGovernanceMeetingActionItemController extends Controller
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

        $actionItems = $meeting
            ->actionItems()
            ->with([
                'agendaItem',
                'responsibleUser',
            ])
            ->orderBy('action_no')
            ->get();

        return view(
            'projects.governance-meetings.action-items.index',
            compact(
                'project',
                'meeting',
                'actionItems'
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

        /*
        |--------------------------------------------------------------------------
        | Generate next action number
        |--------------------------------------------------------------------------
        */

        $nextActionNo =
            ((int) $meeting
                ->actionItems()
                ->max('action_no')) + 1;

        return view(
            'projects.governance-meetings.action-items.create',
            compact(
                'project',
                'meeting',
                'users',
                'agendaItems',
                'nextActionNo'
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

            'action_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'project_governance_meeting_agenda_item_id' => [
                'nullable',
                'integer',
                'exists:project_governance_meeting_agenda_items,id',
            ],

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsible_organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Overdue,Cancelled',
            ],

            'completion_date' => [
                'nullable',
                'date',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate agenda item belongs to this meeting
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
        | Check duplicate action number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->actionItems()
            ->where(
                'action_no',
                $validated['action_no']
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'action_no' =>
                        'This action number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Action Item
        |--------------------------------------------------------------------------
        */

        $meeting->actionItems()->create([

            'project_governance_meeting_agenda_item_id' =>
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ] ?? null,

            'action_no' =>
                $validated['action_no'],

            'action_description' =>
                $validated['action_description'],

            'responsible_user_id' =>
                $validated['responsible_user_id']
                ?? null,

            'responsible_name' =>
                $validated['responsible_name']
                ?? null,

            'responsible_organization' =>
                $validated['responsible_organization']
                ?? null,

            'priority' =>
                $validated['priority'],

            'due_date' =>
                $validated['due_date']
                ?? null,

            'status' =>
                 $this->normalizeStatus(
                    $validated['status'],
                    $validated['due_date'] ?? null
                ),

            'completion_date' =>
                $validated['completion_date']
                ?? null,

            'completion_remarks' =>
                $validated['completion_remarks']
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
                'admin.projects.governance-meetings.action-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Action item added successfully.'
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
        ProjectGovernanceMeetingActionItem $actionItem
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateActionItemOwnership(
            $meeting,
            $actionItem
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        $agendaItems = $meeting
            ->agendaItems()
            ->orderBy('item_no')
            ->get();

        return view(
            'projects.governance-meetings.action-items.edit',
            compact(
                'project',
                'meeting',
                'actionItem',
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
        ProjectGovernanceMeetingActionItem $actionItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateActionItemOwnership(
            $meeting,
            $actionItem
        );

        $validated = $request->validate([

            'action_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'project_governance_meeting_agenda_item_id' => [
                'nullable',
                'integer',
                'exists:project_governance_meeting_agenda_items,id',
            ],

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'responsible_organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Overdue,Cancelled',
            ],

            'completion_date' => [
                'nullable',
                'date',
            ],

            'completion_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate agenda item belongs to this meeting
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
        | Check duplicate action number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->actionItems()
            ->where(
                'action_no',
                $validated['action_no']
            )
            ->where(
                'id',
                '!=',
                $actionItem->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'action_no' =>
                        'This action number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Automatically manage completion date
        |--------------------------------------------------------------------------
        */

        $completionDate =
            $validated['completion_date']
            ?? null;


        if (
            $validated['status'] === 'Completed' &&
            empty($completionDate)
        ) {

            $completionDate =
                now()->toDateString();
        }


        if (
            $validated['status'] !== 'Completed'
        ) {

            $completionDate = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $actionItem->update([

            'project_governance_meeting_agenda_item_id' =>
                $validated[
                    'project_governance_meeting_agenda_item_id'
                ] ?? null,

            'action_no' =>
                $validated['action_no'],

            'action_description' =>
                $validated['action_description'],

            'responsible_user_id' =>
                $validated['responsible_user_id']
                ?? null,

            'responsible_name' =>
                $validated['responsible_name']
                ?? null,

            'responsible_organization' =>
                $validated['responsible_organization']
                ?? null,

            'priority' =>
                $validated['priority'],

            'due_date' =>
                $validated['due_date']
                ?? null,

            'status' =>
                $this->normalizeStatus(
                    $validated['status'],
                    $validated['due_date'] ?? null
                ),

            'completion_date' =>
                $completionDate,

            'completion_remarks' =>
                $validated['completion_remarks']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.action-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Action item updated successfully.'
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
        ProjectGovernanceMeetingActionItem $actionItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateActionItemOwnership(
            $meeting,
            $actionItem
        );

        $actionItem->delete();

        return redirect()
            ->route(
                'admin.projects.governance-meetings.action-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Action item deleted successfully.'
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
        ProjectGovernanceMeetingActionItem $actionItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateActionItemOwnership(
            $meeting,
            $actionItem
        );

        $validated = $request->validate([

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Overdue,Cancelled',
            ],

        ]);


        $updateData = [

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Completion Date
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Completed'
        ) {

            $updateData['completion_date'] =
                $actionItem->completion_date
                ?? now()->toDateString();

        } else {

            $updateData['completion_date'] =
                null;
        }


        $actionItem->update(
            $updateData
        );


        return back()
            ->with(
                'success',
                'Action item status updated successfully.'
            );
    }


    /**
     * Automatically determine whether an action is overdue.
     */
    protected function normalizeStatus(
        ?string $status,
        $dueDate
    ): ?string {

        if (
            in_array(
                $status,
                ['Completed', 'Cancelled'],
                true
            )
        ) {
            return $status;
        }

        if (
            !empty($dueDate) &&
            now()->startOfDay()->gt(
                \Carbon\Carbon::parse($dueDate)
            )
        ) {
            return 'Overdue';
        }

        return $status;
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
    | ACTION ITEM OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateActionItemOwnership(
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingActionItem $actionItem
    ): void {

        abort_unless(
            (int) $actionItem->project_governance_meeting_id ===
            (int) $meeting->id,
            404
        );
    }
}