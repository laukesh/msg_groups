<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ConstructionHseObservation;
use App\Models\ProcurementBidder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ProcurementContract;

class ConstructionHseObservationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
    Request $request,
    Project $project
): View {

    /*
    |--------------------------------------------------------------------------
    | Base Project Query
    |--------------------------------------------------------------------------
    */

    $baseQuery = ConstructionHseObservation::query()
        ->where(
            'project_id',
            $project->id
        );


    /*
    |--------------------------------------------------------------------------
    | Dashboard Counts
    |--------------------------------------------------------------------------
    */

    $summary = [

        'total' =>
            (clone $baseQuery)->count(),

        'open' =>
            (clone $baseQuery)
                ->where('status', 'Open')
                ->count(),

        'in_progress' =>
            (clone $baseQuery)
                ->where('status', 'In Progress')
                ->count(),

        'resolved' =>
            (clone $baseQuery)
                ->where('status', 'Resolved')
                ->count(),

        'verified' =>
            (clone $baseQuery)
                ->where('status', 'Verified')
                ->count(),

        'closed' =>
            (clone $baseQuery)
                ->where('status', 'Closed')
                ->count(),

        'critical' =>
            (clone $baseQuery)
                ->where('severity', 'Critical')
                ->count(),

        'high' =>
            (clone $baseQuery)
                ->where('severity', 'High')
                ->count(),

        'overdue' =>
            (clone $baseQuery)
                ->whereNotIn(
                    'status',
                    [
                        'Closed',
                    ]
                )
                ->whereNotNull('due_date')
                ->whereDate(
                    'due_date',
                    '<',
                    now()->toDateString()
                )
                ->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | Register Query
    |--------------------------------------------------------------------------
    */

    $query = ConstructionHseObservation::query()
        ->where(
            'project_id',
            $project->id
        )
        ->with([
            'reporter',
            'responsibleUser',
            'contractor',
            'correctiveActions',
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

        $query->where(function ($q) use ($search) {

            $q->where(
                'observation_number',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'location',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'category',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'description',
                'like',
                "%{$search}%"
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Status
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
    | Severity
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
    | Pagination
    |--------------------------------------------------------------------------
    */

    $observations = $query
        ->latest('id')
        ->paginate(15)
        ->withQueryString();


    return view(
        'construction.hse.observations.index',
        compact(
            'project',
            'observations',
            'summary'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
    Project $project
): View {

    $users = User::query()
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Contractors / Bidders
    |--------------------------------------------------------------------------
    */

    $contractors = ProcurementBidder::query()
        ->orderBy('company_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Contracts belonging to this project
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
        ->orderBy('contract_number')
        ->get();


    return view(
        'construction.hse.observations.create',
        compact(
            'project',
            'users',
            'contractors',
            'contracts'
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
        Project $project
    ) {

        $validated = $request->validate([

            'observation_date' => [
                'required',
                'date',
            ],

            'observation_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
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
                'exists:procurement_bidders,id',
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

            'corrective_action' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'responsible_user_id' => [
                'nullable',
                'exists:users,id',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        $validated['project_id'] = $project->id;

        /*
        |--------------------------------------------------------------------------
        | Auto Generate Observation Number
        |--------------------------------------------------------------------------
        */

        $validated['observation_number'] =
            $this->generateObservationNumber($project);

        /*
        |--------------------------------------------------------------------------
        | Initial Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'Open';

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] = auth()->id();

        $observation =
            ConstructionHseObservation::create(
                $validated
            );

        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.show',
                [
                    'project' => $project,
                    'observation' => $observation,
                ]
            )
            ->with(
                'success',
                'HSE observation created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseObservation $observation
    ): View {

        $this->validateObservation(
            $project,
            $observation
        );

        $observation->load([
            'project',
            'reporter',
            'responsibleUser',
            'closedBy',
            'contractor',
            'correctiveActions.responsibleUser',
            'correctiveActions.completedBy',
            'correctiveActions.verifiedBy',
        ]);

        return view(
            'construction.hse.observations.show',
            compact(
                'project',
                'observation'
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
    ConstructionHseObservation $observation
): View {

    $this->validateObservation(
        $project,
        $observation
    );


    /*
    |--------------------------------------------------------------------------
    | Closed observations cannot be edited
    |--------------------------------------------------------------------------
    */

    abort_if(
        $observation->status === 'Closed',
        422,
        'Closed observations cannot be edited. Reopen the observation first.'
    );


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
    | Contractors / Bidders
    |--------------------------------------------------------------------------
    */

    $contractors = ProcurementBidder::query()
        ->orderBy('company_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Project Contracts
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
        ->orderBy('contract_number')
        ->get();


    return view(
        'construction.hse.observations.edit',
        compact(
            'project',
            'observation',
            'users',
            'contractors',
            'contracts'
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
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );

        /*
        |--------------------------------------------------------------------------
        | Closed observations cannot be edited
        |--------------------------------------------------------------------------
        */

        abort_if(
            $observation->status === 'Closed',
            422,
            'Closed observations cannot be edited. Reopen the observation first.'
        );

        $validated = $request->validate([

            'observation_date' => [
                'required',
                'date',
            ],

            'observation_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
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
                'exists:procurement_bidders,id',
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

            'corrective_action' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'responsible_user_id' => [
                'nullable',
                'exists:users,id',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Do NOT allow observation number/status to be changed
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();

        $observation->update(
            $validated
        );

        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.show',
                [
                    'project' => $project,
                    'observation' => $observation,
                ]
            )
            ->with(
                'success',
                'HSE observation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | START OBSERVATION
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );

        abort_unless(
            $observation->status === 'Open',
            422,
            'Only open observations can be started.'
        );

        $observation->update([

            'status' => 'In Progress',

            'updated_by' => auth()->id(),

        ]);

        return back()
            ->with(
                'success',
                'Observation started successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY OBSERVATION
    |--------------------------------------------------------------------------
    */

    public function verify(
        Project $project,
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );

        abort_unless(
            in_array(
                $observation->status,
                [
                    'In Progress',
                    'Resolved',
                ],
                true
            ),
            422,
            'This observation is not ready for verification.'
        );

        /*
        |--------------------------------------------------------------------------
        | At least one corrective action required
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $observation
                ->correctiveActions()
                ->exists(),
            422,
            'Observation cannot be verified without corrective actions.'
        );

        /*
        |--------------------------------------------------------------------------
        | All corrective actions must be verified/closed
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

        abort_unless(
            !$pendingActions,
            422,
            'Observation cannot be verified because some corrective actions are still pending.'
        );

        $observation->update([

            'status' => 'Verified',

            'updated_by' => auth()->id(),

        ]);

        return back()
            ->with(
                'success',
                'Observation verified successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE OBSERVATION
    |--------------------------------------------------------------------------
    */

    public function close(
        Project $project,
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );

        abort_unless(
            $observation->status === 'Verified',
            422,
            'Only verified observations can be closed.'
        );

        /*
        |--------------------------------------------------------------------------
        | All corrective actions must be closed
        |--------------------------------------------------------------------------
        */

        $pendingActions =
            $observation
                ->correctiveActions()
                ->where(
                    'status',
                    '!=',
                    'Closed'
                )
                ->exists();

        abort_unless(
            !$pendingActions,
            422,
            'Observation cannot be closed because some corrective actions are still open.'
        );

        $observation->update([

            'status' => 'Closed',

            'closed_date' =>
                now()->toDateString(),

            'closed_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);

        return back()
            ->with(
                'success',
                'Observation closed successfully.'
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

            'status' => 'In Progress',

            'closed_date' => null,

            'closed_by' => null,

            'closure_remarks' => null,

            'updated_by' => auth()->id(),

        ]);

        return back()
            ->with(
                'success',
                'Observation reopened successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseObservation $observation
    ) {

        $this->validateObservation(
            $project,
            $observation
        );

        $observation->delete();

        return redirect()
            ->route(
                'admin.projects.construction.hse.observations.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'HSE observation deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTO GENERATE OBSERVATION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateObservationNumber(
        Project $project
    ): string {

        $year = now()->format('Y');

        $prefix =
            'HSE-OBS-' .
            $year .
            '-';

        $lastObservation =
            ConstructionHseObservation::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'observation_number',
                    'like',
                    $prefix . '%'
                )
                ->orderByDesc('id')
                ->first();

        if (!$lastObservation) {

            return $prefix . '001';
        }

        $lastNumber =
            (int) str_replace(
                $prefix,
                '',
                $lastObservation->observation_number
            );

        return $prefix .
            str_pad(
                $lastNumber + 1,
                3,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PROJECT OWNERSHIP
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
}