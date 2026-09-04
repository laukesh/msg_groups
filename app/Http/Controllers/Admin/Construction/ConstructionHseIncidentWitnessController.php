<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ConstructionHseIncidentWitness;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseIncidentWitnessController extends Controller
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

        $witnesses = $incident
            ->witnesses()
            ->with([
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.incident-witnesses.index',
            [
                'project' => $project,
                'incident' => $incident,
                'witnesses' => $witnesses,
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
            'construction.hse.incident-witnesses.create',
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

        $validated = $request->validate([

            'witness_name' => [
                'required',
                'string',
                'max:255',
            ],

            'witness_type' => [
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

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'statement' => [
                'nullable',
                'string',
            ],

            'statement_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();


        $incident->witnesses()->create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.witnesses.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident witness added successfully.'
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
        ConstructionHseIncidentWitness $witness
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateWitnessRelation(
            $incident,
            $witness
        );

        $witness->load([
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.incident-witnesses.show',
            [
                'project' => $project,
                'incident' => $incident,
                'witness' => $witness,
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
        ConstructionHseIncidentWitness $witness
    ): View {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateWitnessRelation(
            $incident,
            $witness
        );

        return view(
            'construction.hse.incident-witnesses.edit',
            [
                'project' => $project,
                'incident' => $incident,
                'witness' => $witness,
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
        ConstructionHseIncidentWitness $witness
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateWitnessRelation(
            $incident,
            $witness
        );


        $validated = $request->validate([

            'witness_name' => [
                'required',
                'string',
                'max:255',
            ],

            'witness_type' => [
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

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'statement' => [
                'nullable',
                'string',
            ],

            'statement_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['updated_by'] = Auth::id();


        $witness->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.witnesses.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'witness' => $witness,
                ]
            )
            ->with(
                'success',
                'Incident witness updated successfully.'
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
        ConstructionHseIncidentWitness $witness
    ): RedirectResponse {

        $this->validateIncident(
            $project,
            $incident
        );

        $this->validateWitnessRelation(
            $incident,
            $witness
        );


        $witness->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.witnesses.index',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident witness deleted successfully.'
            );
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
    | WITNESS RELATION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateWitnessRelation(
        ConstructionHseIncident $incident,
        ConstructionHseIncidentWitness $witness
    ): void {

        abort_unless(
            (int) $witness->construction_hse_incident_id ===
            (int) $incident->id,
            404
        );
    }
}