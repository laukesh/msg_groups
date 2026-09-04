<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingAgendaItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectGovernanceMeetingAgendaItemController extends Controller
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

        $agendaItems = $meeting
            ->agendaItems()
            ->with('presenter')
            ->orderBy('item_no')
            ->get();

        return view(
            'projects.governance-meetings.agenda-items.index',
            compact(
                'project',
                'meeting',
                'agendaItems'
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

        /*
        |--------------------------------------------------------------------------
        | Generate next item number
        |--------------------------------------------------------------------------
        */

        $nextItemNo =
            ((int) $meeting
                ->agendaItems()
                ->max('item_no')) + 1;

        return view(
            'projects.governance-meetings.agenda-items.create',
            compact(
                'project',
                'meeting',
                'users',
                'nextItemNo'
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

            'item_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'presenter_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'presenter_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'discussion' => [
                'nullable',
                'string',
            ],

            'outcome' => [
                'nullable',
                'string',
            ],

            'decision_required' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Open,Discussed,Deferred,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Check duplicate item number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->agendaItems()
            ->where(
                'item_no',
                $validated['item_no']
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'item_no' =>
                        'This agenda item number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create agenda item
        |--------------------------------------------------------------------------
        */

        $meeting->agendaItems()->create([

            'item_no' =>
                $validated['item_no'],

            'subject' =>
                $validated['subject'],

            'description' =>
                $validated['description']
                ?? null,

            'presenter_id' =>
                $validated['presenter_id']
                ?? null,

            'presenter_name' =>
                $validated['presenter_name']
                ?? null,

            'priority' =>
                $validated['priority'],

            'discussion' =>
                $validated['discussion']
                ?? null,

            'outcome' =>
                $validated['outcome']
                ?? null,

            'decision_required' =>
                $request->boolean(
                    'decision_required'
                ),

            'status' =>
                $validated['status'],

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
                'admin.projects.governance-meetings.agenda-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Agenda item added successfully.'
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
        ProjectGovernanceMeetingAgendaItem $agendaItem
    ): View {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAgendaItemOwnership(
            $meeting,
            $agendaItem
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'projects.governance-meetings.agenda-items.edit',
            compact(
                'project',
                'meeting',
                'agendaItem',
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
        ProjectGovernanceMeetingAgendaItem $agendaItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAgendaItemOwnership(
            $meeting,
            $agendaItem
        );

        $validated = $request->validate([

            'item_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'presenter_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'presenter_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'discussion' => [
                'nullable',
                'string',
            ],

            'outcome' => [
                'nullable',
                'string',
            ],

            'decision_required' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Open,Discussed,Deferred,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Check duplicate item number
        |--------------------------------------------------------------------------
        */

        $exists = $meeting
            ->agendaItems()
            ->where(
                'item_no',
                $validated['item_no']
            )
            ->where(
                'id',
                '!=',
                $agendaItem->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'item_no' =>
                        'This agenda item number already exists for this meeting.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update agenda item
        |--------------------------------------------------------------------------
        */

        $agendaItem->update([

            'item_no' =>
                $validated['item_no'],

            'subject' =>
                $validated['subject'],

            'description' =>
                $validated['description']
                ?? null,

            'presenter_id' =>
                $validated['presenter_id']
                ?? null,

            'presenter_name' =>
                $validated['presenter_name']
                ?? null,

            'priority' =>
                $validated['priority'],

            'discussion' =>
                $validated['discussion']
                ?? null,

            'outcome' =>
                $validated['outcome']
                ?? null,

            'decision_required' =>
                $request->boolean(
                    'decision_required'
                ),

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.governance-meetings.agenda-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Agenda item updated successfully.'
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
        ProjectGovernanceMeetingAgendaItem $agendaItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAgendaItemOwnership(
            $meeting,
            $agendaItem
        );

        $agendaItem->delete();

        return redirect()
            ->route(
                'admin.projects.governance-meetings.agenda-items.index',
                [
                    'project' =>
                        $project->id,

                    'meeting' =>
                        $meeting->id,
                ]
            )
            ->with(
                'success',
                'Agenda item deleted successfully.'
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
        ProjectGovernanceMeetingAgendaItem $agendaItem
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $meeting
        );

        $this->validateAgendaItemOwnership(
            $meeting,
            $agendaItem
        );

        $validated = $request->validate([

            'status' => [
                'required',
                'in:Open,Discussed,Deferred,Closed',
            ],

        ]);


        $agendaItem->update([

            'status' =>
                $validated['status'],

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'Agenda item status updated successfully.'
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
    | AGENDA ITEM OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateAgendaItemOwnership(
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingAgendaItem $agendaItem
    ): void {

        abort_unless(
            (int) $agendaItem->project_governance_meeting_id ===
            (int) $meeting->id,
            404
        );
    }
}