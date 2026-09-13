<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ConstructionHseObservation;
use App\Models\ConstructionHseCorrectiveAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstructionHseCorrectiveActionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseObservation $observation
    ) {
        $this->validateObservation(
            $project,
            $observation
        );

        $correctiveActions = $observation
            ->correctiveActions()
            ->with([
                'responsibleUser',
                'completedBy',
                'verifiedBy',
                'creator',
            ])
            ->latest('id')
            ->paginate(15);

        return view(
            'construction.hse.corrective-actions.index',
            compact(
                'project',
                'observation',
                'correctiveActions'
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
        ConstructionHseObservation $observation
    ) {
        $this->validateObservation(
            $project,
            $observation
        );


        /*
        |--------------------------------------------------------------------------
        | Only Open / In Progress observations can receive
        | new corrective actions.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $observation->status,
                [
                    'Open',
                    'In Progress',
                ],
                true
            ),
            422,
            'Corrective actions can only be added to an open or in-progress observation.'
        );


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.corrective-actions.create',
            compact(
                'project',
                'observation',
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
        ConstructionHseObservation $observation
    ) {
        $this->validateObservation(
            $project,
            $observation
        );


        /*
        |--------------------------------------------------------------------------
        | Only Open / In Progress observations can receive
        | new corrective actions.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $observation->status,
                [
                    'Open',
                    'In Progress',
                ],
                true
            ),
            422,
            'Corrective actions can only be added to an open or in-progress observation.'
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
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
        | Create Corrective Action
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $observation
        ) {

            /*
            | Generate action number
            */

            $validated['action_number'] =
                $this->generateActionNumber(
                    $observation
                );


            /*
            | Link action to observation
            */

            $validated[
                'construction_hse_observation_id'
            ] = $observation->id;


            /*
            | Initial status
            */

            $validated['status'] = 'Open';

            $validated['verification_status'] = 'Pending';


            /*
            | Audit
            */

            $validated['created_by'] =
                auth()->id();


            /*
            | Create
            */

            ConstructionHseCorrectiveAction::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Move Observation to In Progress
            |--------------------------------------------------------------------------
            */

            if (
                $observation->status === 'Open'
            ) {

                $observation->update([

                    'status' =>
                        'In Progress',

                    'updated_by' =>
                        auth()->id(),

                ]);

            }

        });


        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.corrective-actions.index',
                [
                    'project' =>
                        $project,

                    'observation' =>
                        $observation,
                ]
            )
            ->with(
                'success',
                'Corrective action created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        $correctiveAction->load([
            'responsibleUser',
            'completedBy',
            'verifiedBy',
            'creator',
        ]);


        return view(
            'construction.hse.corrective-actions.show',
            compact(
                'project',
                'observation',
                'correctiveAction'
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
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_if(
            $correctiveAction->status === 'Closed',
            422,
            'Closed corrective actions cannot be edited.'
        );


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.corrective-actions.edit',
            compact(
                'project',
                'observation',
                'correctiveAction',
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
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_if(
            $correctiveAction->status === 'Closed',
            422,
            'Closed corrective actions cannot be edited.'
        );


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'action_description' => [
                'required',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
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
        | If a resolved / verified action is changed,
        | previous verification is no longer valid.
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $correctiveAction->status,
                [
                    'Resolved',
                    'Verified',
                ],
                true
            )
        ) {

            $validated['status'] =
                'In Progress';


            $validated[
                'verification_status'
            ] = 'Pending';


            $validated[
                'verified_date'
            ] = null;


            $validated[
                'verified_by'
            ] = null;


            $validated[
                'verification_remarks'
            ] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated[
            'updated_by'
        ] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $correctiveAction->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Observation must remain In Progress
        |--------------------------------------------------------------------------
        */

        if (
            $observation->status !== 'Closed'
            &&
            $observation->status !== 'In Progress'
        ) {

            $observation->update([

                'status' =>
                    'In Progress',

                'updated_by' =>
                    auth()->id(),

            ]);

        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.corrective-actions.show',
                [
                    'project' =>
                        $project,

                    'observation' =>
                        $observation,

                    'correctiveAction' =>
                        $correctiveAction,
                ]
            )
            ->with(
                'success',
                'Corrective action updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_unless(
            $correctiveAction->status === 'Open',
            422,
            'Only open corrective actions can be started.'
        );


        $correctiveAction->update([

            'status' =>
                'In Progress',

            'updated_by' =>
                auth()->id(),

        ]);


        if (
            $observation->status !== 'Closed'
        ) {

            $observation->update([

                'status' =>
                    'In Progress',

                'updated_by' =>
                    auth()->id(),

            ]);

        }


        return back()
            ->with(
                'success',
                'Corrective action started successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE
    |--------------------------------------------------------------------------
    */

    public function resolve(
        Project $project,
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_unless(
            in_array(
                $correctiveAction->status,
                [
                    'Open',
                    'In Progress',
                ],
                true
            ),
            422,
            'Only open or in-progress corrective actions can be resolved.'
        );


        $correctiveAction->update([

            'status' =>
                'Resolved',

            'verification_status' =>
                'Pending',

            'completed_date' =>
                now()->toDateString(),

            'completed_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Corrective action marked as resolved.'
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
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_unless(
            $correctiveAction->status === 'Resolved',
            422,
            'Only resolved corrective actions can be verified.'
        );


        abort_unless(
            $correctiveAction->verification_status === 'Pending',
            422,
            'This corrective action has already been verified.'
        );


        $validated = $request->validate([

            'verification_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(function () use (
            $correctiveAction,
            $observation,
            $validated
        ) {

            $correctiveAction->update([

                'status' =>
                    'Verified',

                'verification_status' =>
                    'Verified',

                'verified_date' =>
                    now()->toDateString(),

                'verified_by' =>
                    auth()->id(),

                'verification_remarks' =>
                    $validated[
                        'verification_remarks'
                    ] ?? null,

                'updated_by' =>
                    auth()->id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Move Observation to Verified only when
            | ALL corrective actions are verified / closed.
            |--------------------------------------------------------------------------
            */

            $pendingActions =
                $observation
                    ->correctiveActions()
                    ->whereNotIn(
                        'status',
                        [
                            'Verified',
                            'Closed',
                        ]
                    )
                    ->exists();


            if (!$pendingActions) {

                $observation->update([

                    'status' =>
                        'Verified',

                    'updated_by' =>
                        auth()->id(),

                ]);

            }

        });


        return back()
            ->with(
                'success',
                'Corrective action verified successfully.'
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
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_unless(
            $correctiveAction->status === 'Resolved',
            422,
            'Only resolved corrective actions can have verification rejected.'
        );


        $validated = $request->validate([

            'verification_remarks' => [
                'required',
                'string',
            ],

        ]);


        $correctiveAction->update([

            'status' =>
                'In Progress',

            'verification_status' =>
                'Rejected',

            'verification_remarks' =>
                $validated[
                    'verification_remarks'
                ],

            'updated_by' =>
                auth()->id(),

        ]);


        if (
            $observation->status !== 'Closed'
        ) {

            $observation->update([

                'status' =>
                    'In Progress',

                'updated_by' =>
                    auth()->id(),

            ]);

        }


        return back()
            ->with(
                'success',
                'Verification rejected. Corrective action returned to In Progress.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    public function close(
        Project $project,
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_unless(
            $correctiveAction->status === 'Verified',
            422,
            'Only verified corrective actions can be closed.'
        );


        abort_unless(
            $correctiveAction->verification_status === 'Verified',
            422,
            'Corrective action must be verified before closing.'
        );


        DB::transaction(function () use (
            $correctiveAction,
            $observation
        ) {

            $correctiveAction->update([

                'status' =>
                    'Closed',

                'updated_by' =>
                    auth()->id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Close observation only when every action is closed
            |--------------------------------------------------------------------------
            */

            $remainingActions =
                $observation
                    ->correctiveActions()
                    ->where(
                        'status',
                        '!=',
                        'Closed'
                    )
                    ->exists();


            if (!$remainingActions) {

                $observation->update([

                    'status' =>
                        'Closed',

                    'closed_date' =>
                        now()->toDateString(),

                    'closed_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);

            }

        });


        return back()
            ->with(
                'success',
                'Corrective action closed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        $this->validateCorrectiveAction(
            $observation,
            $correctiveAction
        );


        abort_if(
            $correctiveAction->status === 'Closed',
            422,
            'Closed corrective actions cannot be deleted.'
        );


        $correctiveAction->delete();


        /*
        |--------------------------------------------------------------------------
        | If no actions remain, reopen observation as Open
        |--------------------------------------------------------------------------
        */

        if (
            !$observation
                ->correctiveActions()
                ->exists()
        ) {

            $observation->update([

                'status' =>
                    'Open',

                'closed_date' =>
                    null,

                'closed_by' =>
                    null,

                'updated_by' =>
                    auth()->id(),

            ]);

        }


        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.corrective-actions.index',
                [
                    'project' =>
                        $project,

                    'observation' =>
                        $observation,
                ]
            )
            ->with(
                'success',
                'Corrective action deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE ACTION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateActionNumber(
        ConstructionHseObservation $observation
    ): string {

        $prefix =
            $observation->observation_number
            . '-CA-';


        $lastAction =
            ConstructionHseCorrectiveAction::query()
                ->where(
                    'construction_hse_observation_id',
                    $observation->id
                )
                ->orderByDesc('id')
                ->first();


        if (!$lastAction) {

            return $prefix . '001';
        }


        $lastNumber = (int) str_replace(
            $prefix,
            '',
            $lastAction->action_number
        );


        return $prefix . str_pad(
            $lastNumber + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE OBSERVATION
    |--------------------------------------------------------------------------
    */

    private function validateObservation(
        Project $project,
        ConstructionHseObservation $observation
    ): void {

        abort_unless(
            $observation->project_id === $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE CORRECTIVE ACTION
    |--------------------------------------------------------------------------
    */

    private function validateCorrectiveAction(
        ConstructionHseObservation $observation,
        ConstructionHseCorrectiveAction $correctiveAction
    ): void {

        abort_unless(
            $correctiveAction
                ->construction_hse_observation_id
                === $observation->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REOPEN OBSERVATION
    |--------------------------------------------------------------------------
    */

    public function reopen(
        Project $project,
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );


        abort_unless(
            $observation->status === 'Closed',
            422,
            'Only closed observations can be reopened.'
        );


        $observation->update([

            'status' =>
                'In Progress',

            'closed_date' =>
                null,

            'closed_by' =>
                null,

            'closure_remarks' =>
                null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Observation reopened successfully.'
        );
    }
}