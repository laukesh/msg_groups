<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ProcurementContract;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConstructionHseIncidentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Incident Register
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Project $project
    ): View {

        $query = ConstructionHseIncident::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contractor',
                'reporter',
                'investigator',
                'actions',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'incident_number',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'location',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'incident_type',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Severity Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('severity')) {

            $query->where(
                'severity',
                $request->severity
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Incident Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('incident_type')) {

            $query->where(
                'incident_type',
                $request->incident_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'incident_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'incident_date',
                '<=',
                $request->date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $incidents = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        //echo "<pre>";print_r($incidents);die();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $baseQuery =
            ConstructionHseIncident::query()
                ->where(
                    'project_id',
                    $project->id
                );


        $summary = [

            'total' =>
                (clone $baseQuery)->count(),

            'reported' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Reported'
                    )
                    ->count(),

            'under_investigation' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Under Investigation'
                    )
                    ->count(),

            'investigation_completed' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Investigation Completed'
                    )
                    ->count(),

            'actions_assigned' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Actions Assigned'
                    )
                    ->count(),

            'actions_completed' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Actions Completed'
                    )
                    ->count(),

            'verified' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Verified'
                    )
                    ->count(),

            'closed' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Closed'
                    )
                    ->count(),

            'critical' =>
                (clone $baseQuery)
                    ->where(
                        'severity',
                        'Critical'
                    )
                    ->count(),

            'high' =>
                (clone $baseQuery)
                    ->where(
                        'severity',
                        'High'
                    )
                    ->count(),

            'injuries' =>
                (clone $baseQuery)
                    ->where(
                        'injury_occurred',
                        true
                    )
                    ->count(),

            'work_stopped' =>
                (clone $baseQuery)
                    ->where(
                        'work_stopped',
                        true
                    )
                    ->count(),
        ];


        return view(
            'construction.hse.incidents.index',
            compact(
                'project',
                'incidents',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
	    Project $project
	): View {

	    /*
	    |--------------------------------------------------------------------------
	    | Get contracts belonging to this project
	    |--------------------------------------------------------------------------
	    */

	    $contracts = ProcurementContract::query()
	        ->whereHas(
	            'tender',
	            function ($query) use ($project) {

	                $query->whereHas(
	                    'package',
	                    function ($query) use ($project) {

	                        $query->whereHas(
	                            'procurementPlan',
	                            function ($query) use ($project) {

	                                $query->where(
	                                    'project_id',
	                                    $project->id
	                                );
	                            }
	                        );
	                    }
	                );
	            }
	        )
	        ->with([
	            'bidder',
	            'tender.package.procurementPlan',
	        ])
	        ->orderBy(
	            'contract_number'
	        )
	        ->get();

	    //echo "<pre>";print_r($contracts);die();


	    /*
	    |--------------------------------------------------------------------------
	    | Users
	    |--------------------------------------------------------------------------
	    */

	    $users = User::query()
	        ->orderBy('name')
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Incident Number
	    |--------------------------------------------------------------------------
	    */

	    $incidentNumber =
	        $this->generateIncidentNumber();


	    return view(
	        'construction.hse.incidents.create',
	        compact(
	            'project',
	            'contracts',
	            'users',
	            'incidentNumber'
	        )
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated =
            $this->validateIncident(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Always generate incident number on server
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;

        $validated['incident_number'] =
            $this->generateIncidentNumber();

        $validated['created_by'] =
            auth()->id();

        $validated['status'] =
            'Reported';


        /*
        |--------------------------------------------------------------------------
        | Reporter
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['reported_by']
            )
        ) {

            $validated['reported_by'] =
                auth()->id();
        }


        if (
            empty(
                $validated['reported_by_name']
            )
            &&
            auth()->check()
        ) {

            $validated['reported_by_name'] =
                auth()->user()->name;
        }


        /*
        |--------------------------------------------------------------------------
        | Store
        |--------------------------------------------------------------------------
        */

        $incident =
            DB::transaction(
                function () use (
                    $validated
                ) {

                    return ConstructionHseIncident::create(
                        $validated
                    );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' =>
                        $project,
                    'incident' =>
                        $incident,
                ]
            )
            ->with(
                'success',
                'HSE incident reported successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseIncident $incident
    ): View {

        $this->validateProjectIncident(
            $project,
            $incident
        );


        $incident->load([
            'contractor',
            'reporter',
            'investigator',
            'closedBy',
            'creator',
            'updater',
            'persons',
            'witnesses',
            'investigations',
            'actions.responsibleUser',
            'actions.completedBy',
            'actions.verifiedBy',
            'documents.uploader',
        ]);

        //echo "<pre>";print_r($incident);die();


        return view(
            'construction.hse.incidents.show',
            compact(
                'project',
                'incident'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionHseIncident $incident
    ): View {

        $this->validateProjectIncident(
            $project,
            $incident
        );


        /*
        |--------------------------------------------------------------------------
        | Do not allow editing a closed incident
        |--------------------------------------------------------------------------
        */

        abort_if(
            $incident->status === 'Closed',
            422,
            'A closed incident cannot be edited.'
        );


        /*
        |--------------------------------------------------------------------------
        | Contractors
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::query()
            ->whereHas(
                'tender',
                function ($query) use ($project) {

                    $query->whereHas(
                        'package',
                        function ($query) use ($project) {

                            $query->whereHas(
                                'procurementPlan',
                                function ($query) use ($project) {

                                    $query->where(
                                        'project_id',
                                        $project->id
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->with('bidder')
            ->orderBy(
                'contract_number'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.hse.incidents.edit',
            compact(
                'project',
                'incident',
                'contracts',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateProjectIncident(
            $project,
            $incident
        );


        abort_if(
            $incident->status === 'Closed',
            422,
            'A closed incident cannot be edited.'
        );


        $validated =
            $this->validateIncident(
                $request
            );


        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Do not allow changing system fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['project_id'],
            $validated['incident_number'],
            $validated['status']
        );


        $incident->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' =>
                        $project,
                    'incident' =>
                        $incident,
                ]
            )
            ->with(
                'success',
                'HSE incident updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateProjectIncident(
            $project,
            $incident
        );


        abort_if(
            $incident->status !== 'Reported',
            422,
            'Only reported incidents can be deleted.'
        );


        $incident->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.index',
                $project
            )
            ->with(
                'success',
                'HSE incident deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateIncident(
        Request $request
    ): array {

        return $request->validate([

            'incident_date' => [
                'required',
                'date',
            ],

            'incident_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'incident_type' => [
                'required',
                'string',
                'max:100',
            ],

            'severity' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'required',
                'string',
            ],

            'contractor_id' => [
			    'nullable',
			    'exists:procurement_contracts,id',
			],

            'reported_by' => [
                'nullable',
                'exists:users,id',
            ],

            'reported_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'immediate_action' => [
                'nullable',
                'string',
            ],

            'injury_occurred' => [
                'nullable',
                'boolean',
            ],

            'injury_details' => [
                'nullable',
                'string',
            ],

            'property_damage' => [
                'nullable',
                'boolean',
            ],

            'property_damage_details' => [
                'nullable',
                'string',
            ],

            'work_stopped' => [
                'nullable',
                'boolean',
            ],

            'work_stoppage_details' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Incident Number
    |--------------------------------------------------------------------------
    */

    private function generateIncidentNumber(): string
	{
	    do {

	        $number =
	            'HSE-INC-' .
	            str_pad(
	                (string) (
	                    ConstructionHseIncident::max('id')
	                    + 1
	                ),
	                6,
	                '0',
	                STR_PAD_LEFT
	            );

	    } while (
	        ConstructionHseIncident::where(
	            'incident_number',
	            $number
	        )->exists()
	    );


	    return $number;
	}


        /*
    |--------------------------------------------------------------------------
    | Start Investigation
    |--------------------------------------------------------------------------
    */

    public function startInvestigation(
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateProjectIncident(
            $project,
            $incident
        );

        abort_if(
            $incident->status !== 'Reported',
            422,
            'Only reported incidents can be moved to investigation.'
        );

        $incident->update([
            'status' => 'Under Investigation',
            'updated_by' => auth()->id(),
        ]);

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
                'Incident moved to Under Investigation.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Incident
    |--------------------------------------------------------------------------
    */

    public function verify(
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateProjectIncident(
            $project,
            $incident
        );

        abort_if(
            $incident->status !== 'Actions Completed',
            422,
            'Only incidents with completed actions can be verified.'
        );

        $incident->update([
            'status' => 'Verified',
            'updated_by' => auth()->id(),
        ]);

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
                'Incident verified successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Close Incident
    |--------------------------------------------------------------------------
    */

    public function close(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {

        $this->validateProjectIncident(
            $project,
            $incident
        );

        abort_if(
            $incident->status !== 'Verified',
            422,
            'Only verified incidents can be closed.'
        );

        $validated = $request->validate([
            'closure_remarks' => [
                'required',
                'string',
            ],
        ]);

        $incident->update([
            'status' => 'Closed',

            'closed_date' => now()->toDateString(),

            'closed_by' => auth()->id(),

            'closure_remarks' =>
                $validated['closure_remarks'],

            'updated_by' => auth()->id(),
        ]);

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
                'Incident closed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Incident Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function validateProjectIncident(
        Project $project,
        ConstructionHseIncident $incident
    ): void {

        abort_unless(
            $incident->project_id === $project->id,
            404
        );
    }
}