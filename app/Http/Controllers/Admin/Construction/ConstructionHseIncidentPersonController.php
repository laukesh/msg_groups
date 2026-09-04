<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ConstructionHseIncidentPerson;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionHseIncidentPersonController extends Controller
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

        $persons = $incident
            ->persons()
            ->with([
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.incident-persons.index',
            [
                'project' => $project,
                'incident' => $incident,
                'persons' => $persons,
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

        return view(
            'construction.hse.incident-persons.create',
            [
                'project' => $project,
                'incident' => $incident,
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

        $validated = $this->validatePerson(
            $request
        );


        DB::transaction(
            function () use (
                $validated,
                $incident
            ) {

                /*
                |--------------------------------------------------------------------------
                | Normalize conditional values
                |--------------------------------------------------------------------------
                */

                if (
                    empty(
                        $validated['injury_occurred']
                    )
                ) {

                    $validated[
                        'injury_type'
                    ] = null;

                    $validated[
                        'body_part_affected'
                    ] = null;

                    $validated[
                        'injury_severity'
                    ] = null;

                    $validated[
                        'treatment_type'
                    ] = null;

                    $validated[
                        'medical_facility'
                    ] = null;

                    $validated[
                        'hospitalized'
                    ] = false;

                    $validated[
                        'hospitalization_date'
                    ] = null;

                    $validated[
                        'lost_work_days'
                    ] = 0;

                    $validated[
                        'returned_to_work'
                    ] = false;

                    $validated[
                        'return_to_work_date'
                    ] = null;
                }


                if (
                    empty(
                        $validated['hospitalized']
                    )
                ) {

                    $validated[
                        'hospitalization_date'
                    ] = null;
                }


                if (
                    empty(
                        $validated['returned_to_work']
                    )
                ) {

                    $validated[
                        'return_to_work_date'
                    ] = null;
                }


                $validated[
                    'created_by'
                ] = Auth::id();

                $validated[
                    'updated_by'
                ] = Auth::id();


                $incident->persons()->create(
                    $validated
                );
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.persons.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident person added successfully.'
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
        ConstructionHseIncidentPerson $person
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validatePersonRelation(
            $incident,
            $person
        );

        $person->load([
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.incident-persons.show',
            [
                'project' => $project,
                'incident' => $incident,
                'person' => $person,
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
        ConstructionHseIncidentPerson $person
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validatePersonRelation(
            $incident,
            $person
        );

        return view(
            'construction.hse.incident-persons.edit',
            [
                'project' => $project,
                'incident' => $incident,
                'person' => $person,
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
        ConstructionHseIncidentPerson $person
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validatePersonRelation(
            $incident,
            $person
        );

        $validated = $this->validatePerson(
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Normalize conditional values
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['injury_occurred']
            )
        ) {

            $validated[
                'injury_type'
            ] = null;

            $validated[
                'body_part_affected'
            ] = null;

            $validated[
                'injury_severity'
            ] = null;

            $validated[
                'treatment_type'
            ] = null;

            $validated[
                'medical_facility'
            ] = null;

            $validated[
                'hospitalized'
            ] = false;

            $validated[
                'hospitalization_date'
            ] = null;

            $validated[
                'lost_work_days'
            ] = 0;

            $validated[
                'returned_to_work'
            ] = false;

            $validated[
                'return_to_work_date'
            ] = null;
        }


        if (
            empty(
                $validated['hospitalized']
            )
        ) {

            $validated[
                'hospitalization_date'
            ] = null;
        }


        if (
            empty(
                $validated['returned_to_work']
            )
        ) {

            $validated[
                'return_to_work_date'
            ] = null;
        }


        $validated[
            'updated_by'
        ] = Auth::id();


        $person->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.persons.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'person' => $person,
                ]
            )
            ->with(
                'success',
                'Incident person updated successfully.'
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
        ConstructionHseIncidentPerson $person
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validatePersonRelation(
            $incident,
            $person
        );


        $person->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.persons.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident person deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validatePerson(
        Request $request
    ): array {

        return $request->validate([

            'person_name' => [
                'required',
                'string',
                'max:255',
            ],

            'person_type' => [
                'required',
                'string',
                'max:100',
            ],

            'employee_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'injury_occurred' => [
                'nullable',
                'boolean',
            ],

            'injury_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'body_part_affected' => [
                'nullable',
                'string',
                'max:255',
            ],

            'injury_severity' => [
                'nullable',
                'string',
                'max:100',
            ],

            'treatment_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'medical_facility' => [
                'nullable',
                'string',
                'max:255',
            ],

            'hospitalized' => [
                'nullable',
                'boolean',
            ],

            'hospitalization_date' => [
                'nullable',
                'date',
            ],

            'lost_work_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'returned_to_work' => [
                'nullable',
                'boolean',
            ],

            'return_to_work_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | INCIDENT VALIDATION
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
    | PERSON RELATION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validatePersonRelation(
        ConstructionHseIncident $incident,
        ConstructionHseIncidentPerson $person
    ): void {

        abort_unless(
            (int) $person->construction_hse_incident_id ===
            (int) $incident->id,
            404
        );
    }
}