<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionSubmittal;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use App\Models\ProjectConsultant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionSubmittalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $submittals = ConstructionSubmittal::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
                'workOrder',
                'consultant',
                'scheduleActivity',
                'submittedBy',
                'submittedTo',
                'approvedBy',
            ])
            ->orderByDesc('id')
            ->get();


        $summary = [

            'total' =>
                $submittals->count(),

            'draft' =>
                $submittals
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'submitted' =>
                $submittals
                    ->where(
                        'status',
                        'Submitted'
                    )
                    ->count(),

            'under_review' =>
                $submittals
                    ->where(
                        'status',
                        'Under Review'
                    )
                    ->count(),

            'approved' =>
                $submittals
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->count(),

            'revise' =>
                $submittals
                    ->where(
                        'status',
                        'Revise & Resubmit'
                    )
                    ->count(),

            'rejected' =>
                $submittals
                    ->where(
                        'status',
                        'Rejected'
                    )
                    ->count(),

            'overdue' =>
                $submittals
                    ->filter(function ($submittal) {

                        return $submittal->review_due_date
                            && $submittal->review_due_date->isPast()
                            && !in_array(
                                $submittal->status,
                                [
                                    'Approved',
                                    'Rejected',
                                    'Closed',
                                    'Cancelled',
                                ],
                                true
                            );
                    })
                    ->count(),
        ];


        return view(
            'construction.submittals.index',
            compact(
                'project',
                'submittals',
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
        $contracts =
            $this->projectContracts(
                $project
            );


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                ])
                ->orderByDesc('id')
                ->get();


        $consultants =
            ProjectConsultant::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy('company_name')
                ->orderBy('consultant_name')
                ->get();


        $scheduleActivities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy('id')
                ->get();


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.submittals.create',
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
        | Verify Consultant
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['consultant_id'])) {

            $consultant =
                ProjectConsultant::query()
                    ->where(
                        'id',
                        $validated['consultant_id']
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
        | Verify Contract
        |--------------------------------------------------------------------------
        */

        $contract = null;


        if (!empty($validated['procurement_contract_id'])) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated['procurement_contract_id']
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
        | Verify Work Order
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['work_order_id'])) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated['work_order_id']
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
        | Verify Schedule Activity
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['schedule_activity_id'])) {

            $activity =
                ConstructionScheduleActivity::query()
                    ->where(
                        'id',
                        $validated['schedule_activity_id']
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
        | Create
        |--------------------------------------------------------------------------
        */

        $submittal =
            DB::transaction(
                function () use (
                    $validated,
                    $project
                ) {

                    $submittalNumber =
                        $this->generateSubmittalNumber(
                            $project
                        );


                    return ConstructionSubmittal::create([

                        'project_id' =>
                            $project->id,

                        'procurement_contract_id' =>
                            $validated['procurement_contract_id'],

                        'work_order_id' =>
                            $validated['work_order_id'],

                        'consultant_id' =>
                            $validated['consultant_id'],

                        'schedule_activity_id' =>
                            $validated['schedule_activity_id'],

                        'submittal_number' =>
                            $submittalNumber,

                        'submittal_date' =>
                            $validated['submittal_date'],

                        'submittal_type' =>
                            $validated['submittal_type'],

                        'title' =>
                            $validated['title'],

                        'description' =>
                            $validated['description'],

                        'submitted_by' =>
                            $validated['submitted_by'],

                        'submitted_to' =>
                            $validated['submitted_to'],

                        'document_reference' =>
                            $validated['document_reference'],

                        'revision_number' =>
                            $validated['revision_number'],

                        'submission_date' =>
                            $validated['submission_date'],

                        'review_due_date' =>
                            $validated['review_due_date'],

                        'review_date' =>
                            $validated['review_date'],

                        'status' =>
                            'Draft',

                        'review_comments' =>
                            $validated['review_comments'],

                        'response' =>
                            $validated['response'],

                        'approval_date' =>
                            $validated['approval_date'],

                        'approved_by' =>
                            $validated['approved_by'],

                        'location' =>
                            $validated['location'],

                        'priority' =>
                            $validated['priority']
                            ?? 'Normal',

                        'remarks' =>
                            $validated['remarks'],

                        'created_by' =>
                            auth()->id(),

                        'updated_by' =>
                            auth()->id(),
                    ]);
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.submittals.show',
                [
                    'project' =>
                        $project,

                    'submittal' =>
                        $submittal,
                ]
            )
            ->with(
                'success',
                'Submittal '
                . $submittal->submittal_number
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
        ConstructionSubmittal $submittal
    ): View {

        abort_unless(
            $submittal->project_id === $project->id,
            404
        );


        $submittal->load([
            'contract.bidder',
            'workOrder.contract.bidder',
            'consultant',
            'scheduleActivity',
            'submittedBy',
            'submittedTo',
            'approvedBy',
            'creator',
            'updater',
        ]);


        return view(
            'construction.submittals.show',
            compact(
                'project',
                'submittal'
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
        ConstructionSubmittal $submittal
    ): View {

        abort_unless(
            $submittal->project_id === $project->id,
            404
        );


        $contracts =
            $this->projectContracts(
                $project
            );


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                ])
                ->orderByDesc('id')
                ->get();


        $consultants =
            ProjectConsultant::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy('company_name')
                ->orderBy('consultant_name')
                ->get();


        $scheduleActivities =
            ConstructionScheduleActivity::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy('id')
                ->get();


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.submittals.edit',
            compact(
                'project',
                'submittal',
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
        ConstructionSubmittal $submittal
    ): RedirectResponse {

        abort_unless(
            $submittal->project_id === $project->id,
            404
        );


        $validated =
            $this->validateRequest(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Only Draft / Revise & Resubmit Can Be Edited
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $submittal->status,
                [
                    'Draft',
                    'Revise & Resubmit',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only Draft or Revise & Resubmit submittals can be edited.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Consultant
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['consultant_id'])) {

            $consultant =
                ProjectConsultant::query()
                    ->where(
                        'id',
                        $validated['consultant_id']
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
        | Verify Contract
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['procurement_contract_id'])) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated['procurement_contract_id']
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
        | Verify Work Order
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['work_order_id'])) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated['work_order_id']
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
        | Update
        |--------------------------------------------------------------------------
        */

        $submittal->update([

            'procurement_contract_id' =>
                $validated['procurement_contract_id'],

            'work_order_id' =>
                $validated['work_order_id'],

            'consultant_id' =>
                $validated['consultant_id'],

            'schedule_activity_id' =>
                $validated['schedule_activity_id'],

            'submittal_date' =>
                $validated['submittal_date'],

            'submittal_type' =>
                $validated['submittal_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'],

            'submitted_by' =>
                $validated['submitted_by'],

            'submitted_to' =>
                $validated['submitted_to'],

            'document_reference' =>
                $validated['document_reference'],

            'revision_number' =>
                $validated['revision_number'],

            'submission_date' =>
                $validated['submission_date'],

            'review_due_date' =>
                $validated['review_due_date'],

            'review_date' =>
                $validated['review_date'],

            'review_comments' =>
                $validated['review_comments'],

            'response' =>
                $validated['response'],

            'approval_date' =>
                $validated['approval_date'],

            'approved_by' =>
                $validated['approved_by'],

            'location' =>
                $validated['location'],

            'priority' =>
                $validated['priority']
                ?? 'Normal',

            'remarks' =>
                $validated['remarks'],

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.construction.submittals.show',
                [
                    'project' =>
                        $project,

                    'submittal' =>
                        $submittal,
                ]
            )
            ->with(
                'success',
                'Submittal updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionSubmittal $submittal
    ): RedirectResponse {

        abort_unless(
            $submittal->project_id === $project->id,
            404
        );


        if (
            !in_array(
                $submittal->status,
                [
                    'Draft',
                    'Revise & Resubmit',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'delete' =>
                        'Only Draft or Revise & Resubmit submittals can be deleted.',
                ]);
        }


        $submittal->delete();


        return redirect()
            ->route(
                'admin.projects.construction.submittals.index',
                $project
            )
            ->with(
                'success',
                'Submittal deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function validateRequest(
        Request $request
    ): array {

        $validated =
            $request->validate([

                'procurement_contract_id' => [
                    'nullable',
                    'integer',
                ],

                'work_order_id' => [
                    'nullable',
                    'integer',
                ],

                'consultant_id' => [
                    'nullable',
                    'integer',
                ],

                'schedule_activity_id' => [
                    'nullable',
                    'integer',
                ],

                'submittal_date' => [
                    'required',
                    'date',
                ],

                'submittal_type' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'title' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                    'string',
                ],

                'submitted_by' => [
                    'nullable',
                    'integer',
                    'exists:users,id',
                ],

                'submitted_to' => [
                    'nullable',
                    'integer',
                    'exists:users,id',
                ],

                'document_reference' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'revision_number' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'submission_date' => [
                    'nullable',
                    'date',
                ],

                'review_due_date' => [
                    'nullable',
                    'date',
                ],

                'review_date' => [
                    'nullable',
                    'date',
                ],

                'review_comments' => [
                    'nullable',
                    'string',
                ],

                'response' => [
                    'nullable',
                    'string',
                ],

                'approval_date' => [
                    'nullable',
                    'date',
                ],

                'approved_by' => [
                    'nullable',
                    'integer',
                    'exists:users,id',
                ],

                'location' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'priority' => [
                    'required',
                    'in:Low,Normal,High,Critical',
                ],

                'remarks' => [
                    'nullable',
                    'string',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | Optional Values
        |--------------------------------------------------------------------------
        */

        $optionalFields = [

            'procurement_contract_id',
            'work_order_id',
            'consultant_id',
            'schedule_activity_id',

            'submittal_type',
            'description',

            'submitted_by',
            'submitted_to',

            'document_reference',
            'revision_number',

            'submission_date',
            'review_due_date',
            'review_date',

            'review_comments',
            'response',

            'approval_date',
            'approved_by',

            'location',
            'remarks',
        ];


        foreach ($optionalFields as $field) {

            $validated[$field] =
                $validated[$field] ?? null;
        }


        return $validated;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Number
    |--------------------------------------------------------------------------
    */

    protected function generateSubmittalNumber(
        Project $project
    ): string {

        do {

            $number =
                'SUB-'
                . str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                )
                . '-'
                . str_pad(
                    (string) (
                        ConstructionSubmittal::query()
                            ->where(
                                'project_id',
                                $project->id
                            )
                            ->count() + 1
                    ),
                    4,
                    '0',
                    STR_PAD_LEFT
                );

        } while (
            ConstructionSubmittal::query()
                ->where(
                    'submittal_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }


    /*
    |--------------------------------------------------------------------------
    | Project Contracts
    |--------------------------------------------------------------------------
    */

    protected function projectContracts(
        Project $project
    ) {

        return ProcurementContract::query()
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
    }


    /*
    |--------------------------------------------------------------------------
    | Find Project Contract
    |--------------------------------------------------------------------------
    */

    protected function findProjectContract(
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

    public function submit(
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if (
	        !in_array(
	            $submittal->status,
	            [
	                'Draft',
	                'Revise & Resubmit',
	            ],
	            true
	        )
	    ) {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'This submittal cannot be submitted in its current status.',
	            ]);
	    }

	    $submittal->update([

	        'status' =>
	            'Submitted',

	        'submission_date' =>
	            now()->toDateString(),

	        'submitted_by' =>
	            auth()->id(),

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal '
	            . $submittal->submittal_number
	            . ' submitted successfully.'
	        );
	}

	public function startReview(
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if ($submittal->status !== 'Submitted') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only submitted submittals can be moved to review.',
	            ]);
	    }

	    $submittal->update([

	        'status' =>
	            'Under Review',

	        'review_date' =>
	            now()->toDateString(),

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal moved to Under Review.'
	        );
	}

	public function approve(
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if ($submittal->status !== 'Under Review') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only submittals under review can be approved.',
	            ]);
	    }

	    $submittal->update([

	        'status' =>
	            'Approved',

	        'approval_date' =>
	            now()->toDateString(),

	        'approved_by' =>
	            auth()->id(),

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal approved successfully.'
	        );
	}

	public function approveWithComments(
	    Request $request,
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if ($submittal->status !== 'Under Review') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only submittals under review can be approved.',
	            ]);
	    }

	    $validated = $request->validate([

	        'review_comments' =>
	            'required|string',

	    ]);

	    $submittal->update([

	        'status' =>
	            'Approved With Comments',

	        'review_date' =>
	            now()->toDateString(),

	        'review_comments' =>
	            $validated['review_comments'],

	        'approval_date' =>
	            now()->toDateString(),

	        'approved_by' =>
	            auth()->id(),

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal approved with comments.'
	        );
	}

	public function revise(
	    Request $request,
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if ($submittal->status !== 'Under Review') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only submittals under review can be returned for revision.',
	            ]);
	    }

	    $validated = $request->validate([

	        'review_comments' =>
	            'required|string',

	    ]);

	    $submittal->update([

	        'status' =>
	            'Revise & Resubmit',

	        'review_date' =>
	            now()->toDateString(),

	        'review_comments' =>
	            $validated['review_comments'],

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal returned for revision.'
	        );
	}

	public function reject(
	    Request $request,
	    Project $project,
	    ConstructionSubmittal $submittal
	): RedirectResponse {

	    $this->ensureProjectSubmittal(
	        $project,
	        $submittal
	    );

	    if ($submittal->status !== 'Under Review') {

	        return back()
	            ->withErrors([
	                'status' =>
	                    'Only submittals under review can be rejected.',
	            ]);
	    }

	    $validated = $request->validate([

	        'review_comments' =>
	            'required|string',

	    ]);

	    $submittal->update([

	        'status' =>
	            'Rejected',

	        'review_date' =>
	            now()->toDateString(),

	        'review_comments' =>
	            $validated['review_comments'],

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return back()
	        ->with(
	            'success',
	            'Submittal rejected.'
	        );
	}

	private function ensureProjectSubmittal(
	    Project $project,
	    ConstructionSubmittal $submittal
	): void {

	    abort_unless(
	        (int) $submittal->project_id === (int) $project->id,
	        404
	    );
	}

}