<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ConstructionHseIncidentInvestigation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionHseIncidentInvestigationController extends Controller
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

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $investigations = $incident
            ->investigations()
            ->with([
                'leadInvestigator',
                'reviewer',
            ])
            ->latest()
            ->get();

        return view(
            'construction.hse.incident-investigations.index',
            [
                'project' => $project,
                'incident' => $incident,
                'investigations' => $investigations,
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

        $this->validateIncidentProject(
            $project,
            $incident
        );

        /*
        |--------------------------------------------------------------------------
        | Investigation can only be created during investigation phase
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $incident->status,
                [
                    'Reported',
                    'Under Investigation',
                ],
                true
            ),
            404
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        $investigationNumber =
            $this->generateInvestigationNumber();

        return view(
            'construction.hse.incident-investigations.create',
            [
                'project' => $project,
                'incident' => $incident,
                'users' => $users,
                'investigationNumber' =>
                    $investigationNumber,
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

        $this->validateIncidentProject(
            $project,
            $incident
        );


        /*
        |--------------------------------------------------------------------------
        | Validate incident status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $incident->status,
                [
                    'Reported',
                    'Under Investigation',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'A new investigation cannot be created for an incident in its current status.'
                );
        }


        $validated = $request->validate([

            'investigation_date' => [
                'required',
                'date',
            ],

            'lead_investigator_id' => [
                'nullable',
                'exists:users,id',
            ],

            'lead_investigator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'investigation_team' => [
                'nullable',
                'string',
            ],

            'immediate_cause' => [
                'nullable',
                'string',
            ],

            'root_cause' => [
                'nullable',
                'string',
            ],

            'contributing_factors' => [
                'nullable',
                'string',
            ],

            'unsafe_act' => [
                'nullable',
                'string',
            ],

            'unsafe_condition' => [
                'nullable',
                'string',
            ],

            'findings' => [
                'nullable',
                'string',
            ],

            'conclusion' => [
                'nullable',
                'string',
            ],

            'recommendations' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(
            function () use (
                $validated,
                $incident
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lead investigator name
                |--------------------------------------------------------------------------
                */

                $leadInvestigatorName =
                    $validated['lead_investigator_name']
                    ?? null;


                if (
                    !$leadInvestigatorName &&
                    !empty(
                        $validated['lead_investigator_id']
                    )
                ) {

                    $leadInvestigatorName =
                        User::find(
                            $validated['lead_investigator_id']
                        )?->name;
                }


                /*
                |--------------------------------------------------------------------------
                | Create investigation
                |--------------------------------------------------------------------------
                */

                ConstructionHseIncidentInvestigation::create([

                    'construction_hse_incident_id' =>
                        $incident->id,

                    'investigation_number' =>
                        $this->generateInvestigationNumber(),

                    'investigation_date' =>
                        $validated['investigation_date'],

                    'lead_investigator_id' =>
                        $validated['lead_investigator_id']
                        ?? null,

                    'lead_investigator_name' =>
                        $leadInvestigatorName,

                    'investigation_team' =>
                        $validated['investigation_team']
                        ?? null,

                    'immediate_cause' =>
                        $validated['immediate_cause']
                        ?? null,

                    'root_cause' =>
                        $validated['root_cause']
                        ?? null,

                    'contributing_factors' =>
                        $validated['contributing_factors']
                        ?? null,

                    'unsafe_act' =>
                        $validated['unsafe_act']
                        ?? null,

                    'unsafe_condition' =>
                        $validated['unsafe_condition']
                        ?? null,

                    'findings' =>
                        $validated['findings']
                        ?? null,

                    'conclusion' =>
                        $validated['conclusion']
                        ?? null,

                    'recommendations' =>
                        $validated['recommendations']
                        ?? null,

                    'status' =>
                        'Draft',

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
                | Reported → Under Investigation
                |--------------------------------------------------------------------------
                */

                if (
                    $incident->status === 'Reported'
                ) {

                    $incident->update([

                        'status' =>
                            'Under Investigation',

                        'updated_by' =>
                            Auth::id(),

                    ]);

                }

            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Investigation created successfully.'
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
        ConstructionHseIncidentInvestigation $investigation
    ): View {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        $investigation->load([
            'leadInvestigator',
            'reviewer',
            'creator',
            'updater',
        ]);


        return view(
            'construction.hse.incident-investigations.show',
            [
                'project' => $project,
                'incident' => $incident,
                'investigation' => $investigation,
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
        ConstructionHseIncidentInvestigation $investigation
    ): View {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft / Rejected can be edited
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $investigation->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            404
        );


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.incident-investigations.edit',
            [
                'project' => $project,
                'incident' => $incident,
                'investigation' => $investigation,
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
        ConstructionHseIncidentInvestigation $investigation
    ): RedirectResponse {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft / Rejected can be updated
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $investigation->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft or rejected investigations can be edited.'
                );
        }


        $validated = $request->validate([

            'investigation_date' => [
                'required',
                'date',
            ],

            'lead_investigator_id' => [
                'nullable',
                'exists:users,id',
            ],

            'lead_investigator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'investigation_team' => [
                'nullable',
                'string',
            ],

            'immediate_cause' => [
                'nullable',
                'string',
            ],

            'root_cause' => [
                'nullable',
                'string',
            ],

            'contributing_factors' => [
                'nullable',
                'string',
            ],

            'unsafe_act' => [
                'nullable',
                'string',
            ],

            'unsafe_condition' => [
                'nullable',
                'string',
            ],

            'findings' => [
                'nullable',
                'string',
            ],

            'conclusion' => [
                'nullable',
                'string',
            ],

            'recommendations' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $leadInvestigatorName =
            $validated['lead_investigator_name']
            ?? null;


        if (
            !$leadInvestigatorName &&
            !empty(
                $validated['lead_investigator_id']
            )
        ) {

            $leadInvestigatorName =
                User::find(
                    $validated['lead_investigator_id']
                )?->name;
        }


        $investigation->update([

            'investigation_date' =>
                $validated['investigation_date'],

            'lead_investigator_id' =>
                $validated['lead_investigator_id']
                ?? null,

            'lead_investigator_name' =>
                $leadInvestigatorName,

            'investigation_team' =>
                $validated['investigation_team']
                ?? null,

            'immediate_cause' =>
                $validated['immediate_cause']
                ?? null,

            'root_cause' =>
                $validated['root_cause']
                ?? null,

            'contributing_factors' =>
                $validated['contributing_factors']
                ?? null,

            'unsafe_act' =>
                $validated['unsafe_act']
                ?? null,

            'unsafe_condition' =>
                $validated['unsafe_condition']
                ?? null,

            'findings' =>
                $validated['findings']
                ?? null,

            'conclusion' =>
                $validated['conclusion']
                ?? null,

            'recommendations' =>
                $validated['recommendations']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.investigations.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'investigation' => $investigation,
                ]
            )
            ->with(
                'success',
                'Investigation updated successfully.'
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
        ConstructionHseIncidentInvestigation $investigation
    ): RedirectResponse {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        if (
            $investigation->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft investigations can be deleted.'
                );
        }


        $investigation->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Investigation deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentInvestigation $investigation
    ): RedirectResponse {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        if (
            $investigation->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft investigations can be submitted.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Incident must still be under investigation
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $incident->status,
                [
                    'Under Investigation',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This incident is not currently in the investigation stage.'
                );
        }


        $investigation->update([

            'status' =>
                'Submitted',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Investigation submitted for review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentInvestigation $investigation
    ): RedirectResponse {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        if (
            $investigation->status !== 'Submitted'
        ) {

            return back()
                ->with(
                    'error',
                    'Only submitted investigations can be approved.'
                );
        }


        if (
            $incident->status !== 'Under Investigation'
        ) {

            return back()
                ->with(
                    'error',
                    'This incident is not currently under investigation.'
                );
        }


        $validated = $request->validate([

            'review_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        DB::transaction(
            function () use (
                $investigation,
                $incident,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Investigation approved
                |--------------------------------------------------------------------------
                */

                $investigation->update([

                    'status' =>
                        'Approved',

                    'reviewed_by' =>
                        Auth::id(),

                    'reviewed_date' =>
                        now()->toDateString(),

                    'review_remarks' =>
                        $validated['review_remarks']
                        ?? null,

                    'updated_by' =>
                        Auth::id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Investigation Completed
                |--------------------------------------------------------------------------
                */

                $incident->update([

                    'status' =>
                        'Investigation Completed',

                    'updated_by' =>
                        Auth::id(),

                ]);

            }
        );


        return back()
            ->with(
                'success',
                'Investigation approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentInvestigation $investigation
    ): RedirectResponse {

        $this->validateIncidentProject(
            $project,
            $incident
        );

        $this->validateInvestigationIncident(
            $incident,
            $investigation
        );


        if (
            $investigation->status !== 'Submitted'
        ) {

            return back()
                ->with(
                    'error',
                    'Only submitted investigations can be rejected.'
                );
        }


        $validated = $request->validate([

            'review_remarks' => [
                'required',
                'string',
            ],

        ]);


        $investigation->update([

            'status' =>
                'Rejected',

            'reviewed_by' =>
                Auth::id(),

            'reviewed_date' =>
                now()->toDateString(),

            'review_remarks' =>
                $validated['review_remarks'],

            'updated_by' =>
                Auth::id(),

        ]);


        return back()
            ->with(
                'success',
                'Investigation rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Investigation Number
    |--------------------------------------------------------------------------
    */

    protected function generateInvestigationNumber(): string
    {
        $last =
            ConstructionHseIncidentInvestigation::query()
                ->orderByDesc('id')
                ->first();


        $nextNumber = $last
            ? (
                (int) substr(
                    $last->investigation_number,
                    strrpos(
                        $last->investigation_number,
                        '-'
                    ) + 1
                ) + 1
            )
            : 1;


        return 'HSE-INV-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Incident / Project
    |--------------------------------------------------------------------------
    */

    protected function validateIncidentProject(
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
    | Validate Investigation / Incident
    |--------------------------------------------------------------------------
    */

    protected function validateInvestigationIncident(
        ConstructionHseIncident $incident,
        ConstructionHseIncidentInvestigation $investigation
    ): void {

        abort_unless(
            (int) $investigation->construction_hse_incident_id ===
            (int) $incident->id,
            404
        );
    }
}