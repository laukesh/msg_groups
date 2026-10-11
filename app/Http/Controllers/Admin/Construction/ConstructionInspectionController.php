<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionInspection;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use App\Models\ProjectConsultant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionInspectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $inspections = ConstructionInspection::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
                'workOrder',
                'consultant',
                'scheduleActivity',
                'inspector',
                'witness',
            ])
            ->orderByDesc('id')
            ->get();


        $summary = [

            'total' =>
                $inspections->count(),

            'planned' =>
                $inspections
                    ->where(
                        'status',
                        'Planned'
                    )
                    ->count(),

            'scheduled' =>
                $inspections
                    ->where(
                        'status',
                        'Scheduled'
                    )
                    ->count(),

            'under_review' =>
                $inspections
                    ->where(
                        'status',
                        'Conducted'
                    )
                    ->count(),

            'passed' =>
                $inspections
                    ->where(
                        'result',
                        'Passed'
                    )
                    ->count(),

            'failed' =>
                $inspections
                    ->where(
                        'result',
                        'Failed'
                    )
                    ->count(),

            'closed' =>
                $inspections
                    ->where(
                        'status',
                        'Closed'
                    )
                    ->count(),

        ];


        return view(
            'construction.inspections.index',
            compact(
                'project',
                'inspections',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Project Contracts
        |--------------------------------------------------------------------------
        */

        $contracts =
            ProcurementContract::query()
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
                    'tender.package',
                ])
                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Active',
                        'In Progress',
                    ]
                )
                ->orderBy(
                    'contract_number'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Work Orders
        |--------------------------------------------------------------------------
        */

        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                ])
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Consultants
        |--------------------------------------------------------------------------
        */

        $consultants =
            ProjectConsultant::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'status',
                    'Active'
                )
                ->orderBy(
                    'company_name'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Schedule Activities
        |--------------------------------------------------------------------------
        */

        $scheduleActivities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'id'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users =
            User::query()
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'construction.inspections.create',
            compact(
                'project',
                'contracts',
                'workOrders',
                'consultants',
                'scheduleActivities',
                'users'
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
            $this->validateRequest(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated[
                        'procurement_contract_id'
                    ]
                );


            if (!$contract) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Automatically use Work Order contract
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    $validated[
                        'procurement_contract_id'
                    ]
                )
                &&
                $workOrder->procurement_contract_id
            ) {

                $validated[
                    'procurement_contract_id'
                ] =
                    $workOrder->procurement_contract_id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Consultant
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'consultant_id'
                ]
            )
        ) {

            $consultant =
                ProjectConsultant::query()
                    ->where(
                        'id',
                        $validated[
                            'consultant_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$consultant) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'consultant_id' =>
                            'The selected consultant does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Schedule Activity
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'schedule_activity_id'
                ]
            )
        ) {

            $activity =
                ConstructionScheduleActivity::query()
                    ->where(
                        'id',
                        $validated[
                            'schedule_activity_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$activity) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedule_activity_id' =>
                            'The selected schedule activity does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated[
            'project_id'
        ] =
            $project->id;

        $validated[
            'priority'
        ] =
            $validated[
                'priority'
            ]
            ??
            'Normal';

        $validated[
            'status'
        ] =
            'Planned';

        $validated[
            'reinspection_required'
        ] =
            false;

        $validated[
            'created_by'
        ] =
            auth()->id();

        $validated[
            'updated_by'
        ] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Generate Inspection Number
        |--------------------------------------------------------------------------
        */

        $inspection =
            DB::transaction(
                function () use (
                    $validated,
                    $project
                ) {

                    $inspectionNumber =
                        $this->generateInspectionNumber(
                            $project
                        );


                    $validated[
                        'inspection_number'
                    ] =
                        $inspectionNumber;


                    return ConstructionInspection::create(
                        $validated
                    );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.inspections.show',
                [
                    'project' =>
                        $project,

                    'inspection' =>
                        $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection '
                . $inspection->inspection_number
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionInspection $inspection
    ): View {

        $this->ensureProjectInspection(
            $project,
            $inspection
        );


        $inspection->load([
            'contract.bidder',
            'workOrder.contract.bidder',
            'consultant',
            'scheduleActivity',
            'inspector',
            'witness',
            'creator',
            'updater',
        ]);

         $users = User::query()
        ->orderBy('name')
        ->get();


        return view(
            'construction.inspections.show',
            compact(
                'project',
                'inspection',
                'users'
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
        ConstructionInspection $inspection
    ): View {

        $this->ensureProjectInspection(
            $project,
            $inspection
        );


        $contracts =
            ProcurementContract::query()
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
                    'tender.package',
                ])
                ->orderBy(
                    'contract_number'
                )
                ->get();


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                ])
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        $consultants =
            ProjectConsultant::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'company_name'
                )
                ->get();


        $scheduleActivities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'id'
                )
                ->get();


        $users =
            User::query()
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'construction.inspections.edit',
            compact(
                'project',
                'inspection',
                'contracts',
                'workOrders',
                'consultants',
                'scheduleActivities',
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
        ConstructionInspection $inspection
    ): RedirectResponse {

        $this->ensureProjectInspection(
            $project,
            $inspection
        );


        $validated =
            $this->validateRequest(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated[
                        'procurement_contract_id'
                    ]
                );


            if (!$contract) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Consultant
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'consultant_id'
                ]
            )
        ) {

            $consultant =
                ProjectConsultant::query()
                    ->where(
                        'id',
                        $validated[
                            'consultant_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$consultant) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'consultant_id' =>
                            'The selected consultant does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Schedule Activity
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'schedule_activity_id'
                ]
            )
        ) {

            $activity =
                ConstructionScheduleActivity::query()
                    ->where(
                        'id',
                        $validated[
                            'schedule_activity_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$activity) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedule_activity_id' =>
                            'The selected schedule activity does not belong to this project.',
                    ]);
            }
        }


        $validated[
            'updated_by'
        ] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Don't allow normal edit to override workflow status/result
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['status'],
            $validated['result'],
            $validated['approval_date'],
            $validated['approved_by']
        );


        $inspection->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.inspections.show',
                [
                    'project' =>
                        $project,

                    'inspection' =>
                        $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionInspection $inspection
    ): RedirectResponse {

        $this->ensureProjectInspection(
            $project,
            $inspection
        );


        if (
            !in_array(
                $inspection->status,
                [
                    'Planned',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only planned inspections can be deleted.',
                ]);
        }


        $inspection->delete();


        return redirect()
            ->route(
                'admin.projects.construction.inspections.index',
                $project
            )
            ->with(
                'success',
                'Inspection deleted successfully.'
            );
    }

    /*
	|--------------------------------------------------------------------------
	| Schedule Inspection
	|--------------------------------------------------------------------------
	*/

	public function schedule(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if ($inspection->status !== 'Planned') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only planned inspections can be scheduled.',
	            ]);
	    }

	    $validated = $request->validate([

	        'scheduled_date' =>
	            'required|date',

	    ]);

	    $inspection->update([

	        'scheduled_date' =>
	            $validated['scheduled_date'],

	        'status' =>
	            'Scheduled',

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Inspection scheduled successfully.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Conduct Inspection
	|--------------------------------------------------------------------------
	*/

	public function conduct(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if ($inspection->status !== 'Scheduled') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only scheduled inspections can be conducted.',
	            ]);
	    }

	    $validated = $request->validate([

	        'conducted_date' =>
	            'required|date',

	        'inspected_by' =>
	            'required|integer|exists:users,id',

	        'witnessed_by' =>
	            'nullable|integer|exists:users,id',

	        'observations' =>
	            'nullable|string',

	        'non_conformance' =>
	            'nullable|string',

	        'remarks' =>
	            'nullable|string',

	    ]);

	    $inspection->update([

	        'conducted_date' =>
	            $validated['conducted_date'],

	        'inspected_by' =>
	            $validated['inspected_by'],

	        'witnessed_by' =>
	            $validated['witnessed_by'] ?? null,

	        'observations' =>
	            $validated['observations'] ?? null,

	        'non_conformance' =>
	            $validated['non_conformance'] ?? null,

	        'remarks' =>
	            $validated['remarks'] ?? null,

	        'status' =>
	            'Conducted',

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Inspection marked as conducted.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Pass Inspection
	|--------------------------------------------------------------------------
	*/

	public function pass(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if ($inspection->status !== 'Conducted') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only conducted inspections can be passed.',
	            ]);
	    }

	    $validated = $request->validate([

	        'remarks' =>
	            'nullable|string',

	    ]);

	    $inspection->update([

	        'result' =>
	            'Passed',

	        'status' =>
	            'Conducted',

	        'remarks' =>
	            $validated['remarks']
	                ?? $inspection->remarks,

	        'reinspection_required' =>
	            false,

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Inspection marked as Passed.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Fail Inspection
	|--------------------------------------------------------------------------
	*/

	public function fail(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if ($inspection->status !== 'Conducted') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only conducted inspections can be failed.',
	            ]);
	    }

	    $validated = $request->validate([

	        'non_conformance' =>
	            'required|string',

	        'corrective_action' =>
	            'required|string',

	        'corrective_action_due_date' =>
	            'nullable|date',

	        'remarks' =>
	            'nullable|string',

	    ]);

	    $inspection->update([

	        'result' =>
	            'Failed',

	        'non_conformance' =>
	            $validated['non_conformance'],

	        'corrective_action' =>
	            $validated['corrective_action'],

	        'corrective_action_due_date' =>
	            $validated['corrective_action_due_date']
	                ?? null,

	        'reinspection_required' =>
	            true,

	        'remarks' =>
	            $validated['remarks']
	                ?? $inspection->remarks,

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Inspection marked as Failed and corrective action recorded.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Record Corrective Action
	|--------------------------------------------------------------------------
	*/

	public function correctiveAction(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if (
	        $inspection->result !== 'Failed'
	        ||
	        !$inspection->reinspection_required
	    ) {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Corrective action can only be recorded for failed inspections requiring re-inspection.',
	            ]);
	    }

	    $validated = $request->validate([

	        'corrective_action_date' =>
	            'required|date',

	        'reinspection_date' =>
	            'required|date|after_or_equal:corrective_action_date',

	        'remarks' =>
	            'nullable|string',

	    ]);

	    $inspection->update([

	        'corrective_action_date' =>
	            $validated['corrective_action_date'],

	        'reinspection_date' =>
	            $validated['reinspection_date'],

	        'remarks' =>
	            $validated['remarks']
	                ?? $inspection->remarks,

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Corrective action recorded and re-inspection scheduled.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Re-inspection
	|--------------------------------------------------------------------------
	*/

	public function reinspection(
	    Request $request,
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if (
	        $inspection->result !== 'Failed'
	        ||
	        !$inspection->reinspection_required
	        ||
	        !$inspection->corrective_action_date
	    ) {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Corrective action must be completed before re-inspection.',
	            ]);
	    }

	    $validated = $request->validate([

	        'reinspection_date' =>
	            'required|date',

	        'reinspection_result' =>
	            'required|in:Passed,Failed',

	        'observations' =>
	            'nullable|string',

	        'non_conformance' =>
	            'nullable|string',

	        'corrective_action' =>
	            'nullable|string',

	        'remarks' =>
	            'nullable|string',

	    ]);

	    if (
	        $validated['reinspection_result'] ===
	        'Passed'
	    ) {

	        $inspection->update([

	            'reinspection_date' =>
	                $validated['reinspection_date'],

	            'result' =>
	                'Passed',

	            'reinspection_required' =>
	                false,

	            'observations' =>
	                $validated['observations']
	                    ?? $inspection->observations,

	            'remarks' =>
	                $validated['remarks']
	                    ?? $inspection->remarks,

	            'status' =>
	                'Conducted',

	            'updated_by' =>
	                auth()->id(),

	        ]);

	    } else {

	        $inspection->update([

	            'reinspection_date' =>
	                $validated['reinspection_date'],

	            'result' =>
	                'Failed',

	            'reinspection_required' =>
	                true,

	            'observations' =>
	                $validated['observations']
	                    ?? $inspection->observations,

	            'non_conformance' =>
	                $validated['non_conformance']
	                    ?? $inspection->non_conformance,

	            'corrective_action' =>
	                $validated['corrective_action']
	                    ?? $inspection->corrective_action,

	            'remarks' =>
	                $validated['remarks']
	                    ?? $inspection->remarks,

	            'status' =>
	                'Conducted',

	            'updated_by' =>
	                auth()->id(),

	        ]);

	    }

	    return back()
	        ->with(
	            'success',
	            'Re-inspection recorded successfully.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Close Inspection
	|--------------------------------------------------------------------------
	*/

	public function close(
	    Project $project,
	    ConstructionInspection $inspection
	): RedirectResponse {

	    $this->ensureProjectInspection(
	        $project,
	        $inspection
	    );

	    if (
	        $inspection->status !== 'Conducted'
	        ||
	        $inspection->result !== 'Passed'
	        ||
	        $inspection->reinspection_required
	    ) {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only passed inspections with no pending re-inspection can be closed.',
	            ]);
	    }

	    $inspection->update([

	        'status' =>
	            'Closed',

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Inspection closed successfully.'
	        );
	}


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateRequest(
        Request $request
    ): array {

        return $request->validate([

            'inspection_date' =>
                'required|date',

            'inspection_type' =>
                'nullable|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'location' =>
                'nullable|string|max:255',

            'procurement_contract_id' =>
                'nullable|integer',

            'work_order_id' =>
                'nullable|integer',

            'consultant_id' =>
                'nullable|integer',

            'schedule_activity_id' =>
                'nullable|integer',

            'planned_date' =>
                'nullable|date',

            'scheduled_date' =>
                'nullable|date',

            'conducted_date' =>
                'nullable|date',

            'inspected_by' =>
                'nullable|integer|exists:users,id',

            'witnessed_by' =>
                'nullable|integer|exists:users,id',

            'priority' =>
                'required|string|max:50',

            'observations' =>
                'nullable|string',

            'non_conformance' =>
                'nullable|string',

            'corrective_action' =>
                'nullable|string',

            'corrective_action_due_date' =>
                'nullable|date',

            'corrective_action_date' =>
                'nullable|date',

            'reinspection_required' =>
                'nullable|boolean',

            'reinspection_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Find Project Contract
    |--------------------------------------------------------------------------
    */

    private function findProjectContract(
        Project $project,
        int $contractId
    ): ?ProcurementContract {

        return ProcurementContract::query()
            ->whereKey(
                $contractId
            )
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
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Inspection Number
    |--------------------------------------------------------------------------
    */

    private function generateInspectionNumber(
        Project $project
    ): string {

        $prefix =
            'INS-'
            . str_pad(
                $project->id,
                4,
                '0',
                STR_PAD_LEFT
            )
            . '-';


        $lastNumber =
            ConstructionInspection::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->lockForUpdate()
                ->count();


        do {

            $lastNumber++;

            $number =
                $prefix
                . str_pad(
                    $lastNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

        } while (
            ConstructionInspection::query()
                ->where(
                    'inspection_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }


    /*
    |--------------------------------------------------------------------------
    | Ensure Inspection Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function ensureProjectInspection(
        Project $project,
        ConstructionInspection $inspection
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );
    }
}