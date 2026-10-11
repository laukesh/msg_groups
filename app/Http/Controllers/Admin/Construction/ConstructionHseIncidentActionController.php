<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ConstructionHseIncidentAction;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionHseIncidentActionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseIncident $incident
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $incident->load([
            'actions.responsibleUser',
            'actions.completedBy',
            'actions.verifiedBy',
        ]);

        $actions = $incident
            ->actions()
            ->latest('id')
            ->get();

        return view(
            'construction.hse.incident-actions.index',
            [
                'project' => $project,
                'incident' => $incident,
                'actions' => $actions,
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
        ConstructionHseIncident $incident
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        /*
        |--------------------------------------------------------------------------
        | Actions can only be created after investigation is completed
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $incident->status,
                [
                    'Investigation Completed',
                    'Actions Assigned',
                    'Actions Completed',
                    'Verified',
                ],
                true
            ),
            404
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        $actionNumber =
            $this->generateActionNumber();

        return view(
            'construction.hse.incident-actions.create',
            [
                'project' => $project,
                'incident' => $incident,
                'users' => $users,
                'actionNumber' => $actionNumber,
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
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        /*
        |--------------------------------------------------------------------------
        | Incident must be ready for actions
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $incident->status,
                [
                    'Investigation Completed',
                    'Actions Assigned',
                    'Actions Completed',
                    'Verified',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Actions cannot be created at the current incident stage.'
                );
        }


        $validated = $request->validate([

            'action_type' => [
                'required',
                'string',
                'max:100',
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

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $actionNumber =
            $this->generateActionNumber();


        /*
        |--------------------------------------------------------------------------
        | Responsible User Name
        |--------------------------------------------------------------------------
        */

        $responsibleName =
            $validated['responsible_name']
            ?? null;


        if (
            !empty(
                $validated['responsible_user_id']
            )
        ) {

            $responsibleUser =
                User::find(
                    $validated['responsible_user_id']
                );

            if ($responsibleUser) {

                $responsibleName =
                    $responsibleUser->name;
            }
        }


        DB::transaction(
            function () use (
                $validated,
                $incident,
                $actionNumber,
                $responsibleName
            ) {

                ConstructionHseIncidentAction::create([

                    'construction_hse_incident_id' =>
                        $incident->id,

                    'action_number' =>
                        $actionNumber,

                    'action_type' =>
                        $validated['action_type'],

                    'action_description' =>
                        $validated['action_description'],

                    'responsible_user_id' =>
                        $validated['responsible_user_id']
                        ?? null,

                    'responsible_name' =>
                        $responsibleName,

                    'due_date' =>
                        $validated['due_date']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Workflow controlled by server
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        'Open',

                    'verification_status' =>
                        'Pending',

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Investigation Completed → Actions Assigned
                |--------------------------------------------------------------------------
                */

                if (
                    $incident->status ===
                    'Investigation Completed'
                ) {

                    $incident->update([

                        'status' =>
                            'Actions Assigned',

                        'updated_by' =>
                            Auth::id(),

                    ]);

                }

            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.actions.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident action created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );

        $action->load([
            'responsibleUser',
            'completedBy',
            'verifiedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.incident-actions.show',
            [
                'project' => $project,
                'incident' => $incident,
                'action' => $action,
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
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );

        /*
        |--------------------------------------------------------------------------
        | Closed / Verified actions cannot be edited
        |--------------------------------------------------------------------------
        */

        abort_unless(
            !in_array(
                $action->status,
                [
                    'Closed',
                ],
                true
            ),
            404
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.hse.incident-actions.edit',
            [
                'project' => $project,
                'incident' => $incident,
                'action' => $action,
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
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if ($action->status === 'Closed') {

            return back()
                ->with(
                    'error',
                    'A closed action cannot be edited.'
                );
        }


        $validated = $request->validate([

            'action_type' => [
                'required',
                'string',
                'max:100',
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

            'due_date' => [
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
        | Responsible User Name
        |--------------------------------------------------------------------------
        */

        $responsibleName =
            $validated['responsible_name']
            ?? null;


        if (
            !empty(
                $validated['responsible_user_id']
            )
        ) {

            $responsibleUser =
                User::find(
                    $validated['responsible_user_id']
                );

            if ($responsibleUser) {

                $responsibleName =
                    $responsibleUser->name;
            }
        }


        $action->update([

            'action_type' =>
                $validated['action_type'],

            'action_description' =>
                $validated['action_description'],

            'responsible_user_id' =>
                $validated['responsible_user_id']
                ?? null,

            'responsible_name' =>
                $responsibleName,

            'due_date' =>
                $validated['due_date']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.actions.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'action' => $action,
                ]
            )
            ->with(
                'success',
                'Incident action updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if ($action->status !== 'Open') {

            return back()
                ->with(
                    'error',
                    'Only open actions can be deleted.'
                );
        }


        DB::transaction(
            function () use (
                $action,
                $incident
            ) {

                $action->delete();

                $this->refreshIncidentActionStatus(
                    $incident
                );
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.actions.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident action deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if ($action->status !== 'Open') {

            return back()
                ->with(
                    'error',
                    'Only open actions can be started.'
                );
        }


        $action->update([

            'status' =>
                'In Progress',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Incident action started successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE
    |--------------------------------------------------------------------------
    */

    public function complete(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if (
            !in_array(
                $action->status,
                [
                    'Open',
                    'In Progress',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This action cannot be completed in its current status.'
                );
        }


        $validated = $request->validate([

            'completion_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(
            function () use (
                $action,
                $incident,
                $validated
            ) {

                $action->update([

                    'status' =>
                        'Completed',

                    'completed_date' =>
                        now()->toDateString(),

                    'completed_by' =>
                        Auth::id(),

                    'completion_remarks' =>
                        $validated['completion_remarks']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Completion requires verification
                    |--------------------------------------------------------------------------
                    */

                    'verification_status' =>
                        'Pending',

                    'updated_by' =>
                        Auth::id(),

                ]);


                $this->refreshIncidentActionStatus(
                    $incident
                );

            }
        );


        return back()
            ->with(
                'success',
                'Incident action completed successfully and is pending verification.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY
    |--------------------------------------------------------------------------
    */

    public function verify(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if (
            $action->status !== 'Completed'
        ) {

            return back()
                ->with(
                    'error',
                    'Only completed actions can be verified.'
                );
        }


        if (
            $action->verification_status !==
            'Pending'
        ) {

            return back()
                ->with(
                    'error',
                    'This action is not pending verification.'
                );
        }


        $validated = $request->validate([

            'verification_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(
            function () use (
                $action,
                $incident,
                $validated
            ) {

                $action->update([

                    'verification_status' =>
                        'Verified',

                    'verified_date' =>
                        now()->toDateString(),

                    'verified_by' =>
                        Auth::id(),

                    'verification_remarks' =>
                        $validated['verification_remarks']
                        ?? null,

                    'status' =>
                        'Closed',

                    'updated_by' =>
                        Auth::id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Check whether ALL actions are verified
                |--------------------------------------------------------------------------
                */

                $this->refreshIncidentActionStatus(
                    $incident
                );

            }
        );


        return back()
            ->with(
                'success',
                'Incident action verified and closed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function rejectVerification(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateAction(
            $incident,
            $action
        );


        if (
            $action->status !== 'Completed'
        ) {

            return back()
                ->with(
                    'error',
                    'Only completed actions can be sent back for correction.'
                );
        }


        if (
            $action->verification_status !==
            'Pending'
        ) {

            return back()
                ->with(
                    'error',
                    'This action is not pending verification.'
                );
        }


        $validated = $request->validate([

            'verification_remarks' => [
                'required',
                'string',
            ],

        ]);


        $action->update([

            'verification_status' =>
                'Rejected',

            'verification_remarks' =>
                $validated['verification_remarks'],

            /*
            |--------------------------------------------------------------------------
            | Send action back for correction
            |--------------------------------------------------------------------------
            */

            'status' =>
                'In Progress',

            'updated_by' =>
                Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Incident cannot remain Actions Completed
        |--------------------------------------------------------------------------
        */

        if (
            $incident->status ===
            'Actions Completed'
        ) {

            $incident->update([

                'status' =>
                    'Actions Assigned',

                'updated_by' =>
                    Auth::id(),

            ]);

        }


        return back()
            ->with(
                'success',
                'Incident action verification rejected and returned for correction.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE ACTION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateActionNumber(): string
    {
        $lastAction =
            ConstructionHseIncidentAction::query()
                ->orderByDesc('id')
                ->first();

        $nextNumber = $lastAction
            ? $lastAction->id + 1
            : 1;

        return 'HSE-INC-ACT-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REFRESH INCIDENT ACTION STATUS
    |--------------------------------------------------------------------------
    */

    private function refreshIncidentActionStatus(
        ConstructionHseIncident $incident
    ): void {

        $actions = $incident
            ->actions()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | No actions
        |--------------------------------------------------------------------------
        */

        if ($actions->isEmpty()) {

            if (
                $incident->status !==
                'Investigation Completed'
            ) {

                $incident->update([

                    'status' =>
                        'Investigation Completed',

                    'updated_by' =>
                        Auth::id(),

                ]);

            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Any action still open / in progress
        |--------------------------------------------------------------------------
        */

        $hasPendingActions =
            $actions->contains(
                fn ($action) =>
                    in_array(
                        $action->status,
                        [
                            'Open',
                            'In Progress',
                        ],
                        true
                    )
            );


        if ($hasPendingActions) {

            $incident->update([

                'status' =>
                    'Actions Assigned',

                'updated_by' =>
                    Auth::id(),

            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | All actions completed / closed
        |--------------------------------------------------------------------------
        */

        $allCompleted =
            $actions->every(
                fn ($action) =>
                    in_array(
                        $action->status,
                        [
                            'Completed',
                            'Closed',
                        ],
                        true
                    )
            );


        if (!$allCompleted) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | All actions verified
        |--------------------------------------------------------------------------
        */

        $allVerified =
            $actions->every(
                fn ($action) =>
                    $action->verification_status ===
                    'Verified'
            );


        if ($allVerified) {

            /*
            |--------------------------------------------------------------------------
            | All actions verified → Incident Verified
            |--------------------------------------------------------------------------
            */

            $incident->update([

                'status' =>
                    'Verified',

                'updated_by' =>
                    Auth::id(),

            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Actions completed but verification pending
        |--------------------------------------------------------------------------
        */

        $incident->update([

            'status' =>
                'Actions Completed',

            'updated_by' =>
                Auth::id(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INCIDENT
    |--------------------------------------------------------------------------
    */

    private function validateIncident(
        Project $project,
        ConstructionHseIncident $incident
    ): void {

        abort_unless(
            (int) $incident->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE ACTION
    |--------------------------------------------------------------------------
    */

    private function validateAction(
        ConstructionHseIncident $incident,
        ConstructionHseIncidentAction $action
    ): void {

        abort_unless(
            (int) $action->construction_hse_incident_id ===
            (int) $incident->id,
            404
        );
    }
}