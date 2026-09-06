<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionClaim;
use App\Models\ConstructionClaimHistory;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConstructionClaimController extends Controller
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

        $query = ConstructionClaim::query()
            ->with([
                'procurementContract',
                'workOrder',
                'creator',
            ])
            ->where(
                'project_id',
                $project->id
            );


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'claim_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'subject',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'claimant_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'workOrder',
                    function ($wo) use ($search) {

                        $wo->where(
                            'work_order_number',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'work_order_title',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'procurementContract',
                    function ($contract) use ($search) {

                        $contract
                            ->where(
                                'contract_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'contract_title',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'bidder_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
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
        | CLAIM TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('claim_type')) {

            $query->where(
                'claim_type',
                $request->claim_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PRIORITY
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {

            $query->where(
                'priority',
                $request->priority
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'claim_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'claim_date',
                '<=',
                $request->date_to
            );
        }


        $claims = $query
            ->latest('claim_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $projectClaims =
            ConstructionClaim::where(
                'project_id',
                $project->id
            );


        $summary = [

            'total' =>
                (clone $projectClaims)->count(),

            'draft' =>
                (clone $projectClaims)
                    ->where('status', 'Draft')
                    ->count(),

            'under_review' =>
                (clone $projectClaims)
                    ->whereIn(
                        'status',
                        [
                            'Submitted',
                            'Under Review',
                            'Under Assessment',
                        ]
                    )
                    ->count(),

            'approved' =>
                (clone $projectClaims)
                    ->whereIn(
                        'status',
                        [
                            'Approved',
                            'Partially Approved',
                        ]
                    )
                    ->count(),

            'rejected' =>
                (clone $projectClaims)
                    ->where('status', 'Rejected')
                    ->count(),

            'claimed_amount' =>
                (clone $projectClaims)
                    ->sum('claimed_amount'),

            'approved_amount' =>
                (clone $projectClaims)
                    ->sum('approved_amount'),

            'claimed_days' =>
                (clone $projectClaims)
                    ->sum('claimed_days'),

            'approved_days' =>
                (clone $projectClaims)
                    ->sum('approved_days'),
        ];


        return view(
            'construction.claims.index',
            compact(
                'project',
                'claims',
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

        $workOrders =
            ConstructionWorkOrder::where(
                'project_id',
                $project->id
            )
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Procurement Contracts
        |--------------------------------------------------------------------------
        |
        | Same construction-side contract chain:
        |
        | Procurement Plan
        |      ↓
        | Package
        |      ↓
        | Tender
        |      ↓
        | Procurement Contract
        |
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
                    'tender.package.procurementPlan',
                ])

                ->orderByDesc('id')
                ->get();


        return view(
            'construction.claims.create',
            compact(
                'project',
                'workOrders',
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

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'claim_type' => [
                'required',
                'in:Variation,Delay,Extension of Time,Additional Cost,Price Escalation,Loss and Expense,Other',
            ],

            'claim_date' => [
                'required',
                'date',
            ],

            'event_date' => [
                'nullable',
                'date',
            ],

            'claimant_type' => [
                'required',
                'in:Contractor,Consultant,Client,Other',
            ],

            'claimant_name' => [
                'nullable',
                'string',
                'max:150',
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

            'justification' => [
                'nullable',
                'string',
            ],

            'claimed_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'claimed_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | WORK ORDER
        |--------------------------------------------------------------------------
        */

        $workOrder = null;

        if (!empty(
            $validated['construction_work_order_id']
        )) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_work_order_id'
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
                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT CONTRACT
        |--------------------------------------------------------------------------
        */

        $procurementContract = null;

        if (!empty(
            $validated['procurement_contract_id']
        )) {

            $procurementContract =
                ProcurementContract::query()

                    ->where(
                        'id',
                        $validated[
                            'procurement_contract_id'
                        ]
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


            if (!$procurementContract) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Procurement Contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CONTRACT / WORK ORDER MATCH
        |--------------------------------------------------------------------------
        */

        if (
            $workOrder &&
            $procurementContract &&
            $workOrder->procurement_contract_id
        ) {

            if (
                (int) $workOrder->procurement_contract_id !==
                (int) $procurementContract->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Procurement Contract does not belong to the selected Work Order.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AUTO DERIVE CONTRACT FROM WORK ORDER
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['procurement_contract_id']
            ) &&
            $workOrder &&
            $workOrder->procurement_contract_id
        ) {

            $validated['procurement_contract_id'] =
                $workOrder->procurement_contract_id;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE CLAIM
        |--------------------------------------------------------------------------
        */

        $claim = DB::transaction(
            function () use (
                $validated,
                $project
            ) {

                $claim =
                    new ConstructionClaim();

                $claim->project_id =
                    $project->id;

                $claim->procurement_contract_id =
                    $validated[
                        'procurement_contract_id'
                    ] ?? null;

                $claim->construction_work_order_id =
                    $validated[
                        'construction_work_order_id'
                    ] ?? null;

                $claim->claim_number =
                    $this->generateClaimNumber();

                $claim->claim_type =
                    $validated['claim_type'];

                $claim->claim_date =
                    $validated['claim_date'];

                $claim->event_date =
                    $validated['event_date'] ?? null;

                $claim->claimant_type =
                    $validated['claimant_type'];

                $claim->claimant_name =
                    $validated['claimant_name'] ?? null;

                $claim->subject =
                    $validated['subject'];

                $claim->description =
                    $validated['description'] ?? null;

                $claim->justification =
                    $validated['justification'] ?? null;

                $claim->claimed_amount =
                    $validated['claimed_amount'] ?? 0;

                $claim->claimed_days =
                    $validated['claimed_days'] ?? 0;

                $claim->priority =
                    $validated['priority'];

                $claim->status =
                    'Draft';

                $claim->remarks =
                    $validated['remarks'] ?? null;

                $claim->created_by =
                    Auth::id();

                $claim->updated_by =
                    Auth::id();

                $claim->save();


                /*
                |--------------------------------------------------------------------------
                | HISTORY
                |--------------------------------------------------------------------------
                */

                $this->addHistory(
                    $claim,
                    'Created',
                    null,
                    'Draft',
                    'Claim created.'
                );


                return $claim;
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.claims.show',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            )
            ->with(
                'success',
                'Construction Claim created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionClaim $claim
    ): View {

        abort_unless(
            (int) $claim->project_id ===
            (int) $project->id,
            404
        );


        $claim->load([
            'procurementContract',
            'workOrder',
            'creator',
            'updater',
            'closedBy',
            'documents.uploadedBy',
            'history.performedBy',
        ]);


        return view(
            'construction.claims.show',
            compact(
                'project',
                'claim'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Project $project, ConstructionClaim $claim): View
	{
	    $this->checkProject($project, $claim);

	    // Work orders belonging to this project
	    $workOrders = ConstructionWorkOrder::where('project_id', $project->id)
	        ->orderBy('work_order_number')
	        ->get();

	    // Procurement contracts belonging to this project
	    $procurementContracts = ProcurementContract::whereHas(
	        'tender.package.procurementPlan',
	        function ($query) use ($project) {
	            $query->where('project_id', $project->id);
	        }
	    )
	        ->with(['bidder'])
	        ->orderBy('contract_number')
	        ->get();

	    return view('construction.claims.edit', compact(
	        'project',
	        'claim',
	        'workOrders',
	        'procurementContracts'
	    ));
	}


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionClaim $claim
    ) {

        abort_unless(
            (int) $claim->project_id ===
            (int) $project->id,
            404
        );


        if (!in_array(
            $claim->status,
            [
                'Draft',
                'Rejected',
            ]
        )) {

            return back()->with(
                'error',
                'Only Draft or Rejected claims can be edited.'
            );
        }


        $validated = $request->validate([

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'claim_type' => [
                'required',
                'in:Variation,Delay,Extension of Time,Additional Cost,Price Escalation,Loss and Expense,Other',
            ],

            'claim_date' => [
                'required',
                'date',
            ],

            'event_date' => [
                'nullable',
                'date',
            ],

            'claimant_type' => [
                'required',
                'in:Contractor,Consultant,Client,Other',
            ],

            'claimant_name' => [
                'nullable',
                'string',
                'max:150',
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

            'justification' => [
                'nullable',
                'string',
            ],

            'claimed_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'claimed_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | WORK ORDER
        |--------------------------------------------------------------------------
        */

        $workOrder = null;

        if (!empty(
            $validated['construction_work_order_id']
        )) {

            $workOrder =
                ConstructionWorkOrder::where(
                    'id',
                    $validated[
                        'construction_work_order_id'
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
                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT CONTRACT
        |--------------------------------------------------------------------------
        */

        $procurementContract = null;

        if (!empty(
            $validated['procurement_contract_id']
        )) {

            $procurementContract =
                ProcurementContract::query()

                    ->where(
                        'id',
                        $validated[
                            'procurement_contract_id'
                        ]
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


            if (!$procurementContract) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Procurement Contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MATCH CONTRACT WITH WORK ORDER
        |--------------------------------------------------------------------------
        */

        if (
            $workOrder &&
            $procurementContract &&
            $workOrder->procurement_contract_id
        ) {

            if (
                (int) $workOrder->procurement_contract_id !==
                (int) $procurementContract->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Procurement Contract does not belong to the selected Work Order.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AUTO DERIVE CONTRACT
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['procurement_contract_id']
            ) &&
            $workOrder &&
            $workOrder->procurement_contract_id
        ) {

            $validated['procurement_contract_id'] =
                $workOrder->procurement_contract_id;
        }


        /*
        |--------------------------------------------------------------------------
        | RESET ASSESSMENT AFTER REJECTION
        |--------------------------------------------------------------------------
        */

        $claim->update([

            'procurement_contract_id' =>
                $validated[
                    'procurement_contract_id'
                ] ?? null,

            'construction_work_order_id' =>
                $validated[
                    'construction_work_order_id'
                ] ?? null,

            'claim_type' =>
                $validated['claim_type'],

            'claim_date' =>
                $validated['claim_date'],

            'event_date' =>
                $validated['event_date'] ?? null,

            'claimant_type' =>
                $validated['claimant_type'],

            'claimant_name' =>
                $validated['claimant_name'] ?? null,

            'subject' =>
                $validated['subject'],

            'description' =>
                $validated['description'] ?? null,

            'justification' =>
                $validated['justification'] ?? null,

            'claimed_amount' =>
                $validated['claimed_amount'] ?? 0,

            'claimed_days' =>
                $validated['claimed_days'] ?? 0,

            'priority' =>
                $validated['priority'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'assessed_amount' =>
                0,

            'assessed_days' =>
                0,

            'approved_amount' =>
                0,

            'approved_days' =>
                0,

            'assessment_remarks' =>
                null,

            'approval_remarks' =>
                null,

            'rejection_remarks' =>
                null,

            'status' =>
                'Draft',

            'updated_by' =>
                Auth::id(),
        ]);


        $this->addHistory(
            $claim,
            'Updated',
            $claim->getOriginal('status'),
            'Draft',
            'Claim updated.'
        );


        return redirect()
            ->route(
                'admin.projects.construction.claims.show',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            )
            ->with(
                'success',
                'Construction Claim updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionClaim $claim
    ) {

        $this->checkProject(
            $project,
            $claim
        );


        if ($claim->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft claims can be submitted.'
            );
        }


        $oldStatus = $claim->status;

        $claim->update([
            'status' => 'Submitted',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $claim,
            'Submitted',
            $oldStatus,
            'Submitted',
            'Claim submitted for review.'
        );


        return back()->with(
            'success',
            'Claim submitted for review.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START REVIEW
    |--------------------------------------------------------------------------
    */

    public function review(
        Project $project,
        ConstructionClaim $claim
    ) {

        $this->checkProject(
            $project,
            $claim
        );


        if ($claim->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted claims can be moved to review.'
            );
        }


        $oldStatus = $claim->status;

        $claim->update([
            'status' => 'Under Review',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $claim,
            'Review Started',
            $oldStatus,
            'Under Review',
            'Claim review started.'
        );


        return back()->with(
            'success',
            'Claim moved to Under Review.'
        );
    }


    /*
	|--------------------------------------------------------------------------
	| ASSESS
	|--------------------------------------------------------------------------
	*/

	public function assess(
	    Request $request,
	    Project $project,
	    ConstructionClaim $claim
	) {

	    $this->checkProject(
	        $project,
	        $claim
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | STATUS CHECK
	    |--------------------------------------------------------------------------
	    */

	    if (!in_array($claim->status, [
	        'Under Review',
	        'Under Assessment',
	    ])) {

	        return back()
	            ->with(
	                'error',
	                'Only claims under review or under assessment can be assessed.'
	            );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | VALIDATION
	    |--------------------------------------------------------------------------
	    */

	    $validated = $request->validate([

	        'assessed_amount' => [
	            'required',
	            'numeric',
	            'min:0',
	        ],

	        'assessed_days' => [
	            'required',
	            'integer',
	            'min:0',
	        ],

	        'assessment_remarks' => [
	            'required',
	            'string',
	        ],

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | ASSESSED AMOUNT CANNOT EXCEED CLAIMED AMOUNT
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $claim->claimed_amount !== null &&
	        (float) $validated['assessed_amount'] >
	        (float) $claim->claimed_amount
	    ) {

	        return back()
	            ->withInput()
	            ->withErrors([
	                'assessed_amount' =>
	                    'Assessed amount cannot be greater than the claimed amount.',
	            ]);
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | ASSESSED DAYS CANNOT EXCEED CLAIMED DAYS
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $claim->claimed_days !== null &&
	        (int) $validated['assessed_days'] >
	        (int) $claim->claimed_days
	    ) {

	        return back()
	            ->withInput()
	            ->withErrors([
	                'assessed_days' =>
	                    'Assessed days cannot be greater than the claimed days.',
	            ]);
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | OLD STATUS
	    |--------------------------------------------------------------------------
	    */

	    $oldStatus = $claim->status;


	    /*
	    |--------------------------------------------------------------------------
	    | UPDATE ASSESSMENT
	    |--------------------------------------------------------------------------
	    */

	    $claim->update([

	        'assessed_amount' =>
	            $validated['assessed_amount'],

	        'assessed_days' =>
	            $validated['assessed_days'],

	        'assessment_remarks' =>
	            $validated['assessment_remarks'],

	        'status' =>
	            'Under Assessment',

	        'updated_by' =>
	            Auth::id(),

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | HISTORY
	    |--------------------------------------------------------------------------
	    */

	    $this->addHistory(
	        $claim,
	        'Assessment',
	        $oldStatus,
	        'Under Assessment',
	        $validated['assessment_remarks']
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | REDIRECT
	    |--------------------------------------------------------------------------
	    */

	    return redirect()
	        ->route(
	            'admin.projects.construction.claims.show',
	            [
	                'project' => $project,
	                'claim'   => $claim,
	            ]
	        )
	        ->with(
	            'success',
	            'Claim assessment saved successfully.'
	        );
	}

	/**
	 * Show claim approval form.
	 */
	public function approval(
	    Project $project,
	    ConstructionClaim $claim
	): View {
	    $this->checkProject($project, $claim);

	    if ($claim->status !== 'Under Assessment') {
	        abort(
	            422,
	            'Only claims under assessment can be approved.'
	        );
	    }

	    $claim->load([
	        'project',
	        'procurementContract.bidder',
	        'workOrder',
	    ]);

	    return view(
	        'construction.claims.approval',
	        compact(
	            'project',
	            'claim'
	        )
	    );
	}


    /**
	 * Approve claim.
	 */
	public function approve(
	    Request $request,
	    Project $project,
	    ConstructionClaim $claim
	): RedirectResponse {
	    $this->checkProject($project, $claim);

	    if ($claim->status !== 'Under Assessment') {
	        return back()->withErrors([
	            'error' => 'Only claims under assessment can be approved.',
	        ]);
	    }

	    $validated = $request->validate([
	        'approved_amount' => [
	            'required',
	            'numeric',
	            'min:0',
	        ],

	        'approved_days' => [
	            'required',
	            'integer',
	            'min:0',
	        ],

	        'approval_remarks' => [
	            'nullable',
	            'string',
	            'max:5000',
	        ],
	    ]);

	    /*
	    |--------------------------------------------------------------------------
	    | Validate approved amount
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $claim->assessed_amount !== null &&
	        $validated['approved_amount'] > $claim->assessed_amount
	    ) {
	        return back()
	            ->withInput()
	            ->withErrors([
	                'approved_amount' =>
	                    'Approved amount cannot be greater than assessed amount.',
	            ]);
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Validate approved days
	    |--------------------------------------------------------------------------
	    */

	    if (
	        $claim->assessed_days !== null &&
	        $validated['approved_days'] > $claim->assessed_days
	    ) {
	        return back()
	            ->withInput()
	            ->withErrors([
	                'approved_days' =>
	                    'Approved days cannot be greater than assessed days.',
	            ]);
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Determine final status
	    |--------------------------------------------------------------------------
	    */

	    $isPartiallyApproved =
	        (
	            $claim->assessed_amount !== null &&
	            (float) $validated['approved_amount'] <
	            (float) $claim->assessed_amount
	        )
	        ||
	        (
	            $claim->assessed_days !== null &&
	            (int) $validated['approved_days'] <
	            (int) $claim->assessed_days
	        );

	    $oldStatus = $claim->status;

	    $newStatus = $isPartiallyApproved
	        ? 'Partially Approved'
	        : 'Approved';

	    /*
	    |--------------------------------------------------------------------------
	    | Update claim
	    |--------------------------------------------------------------------------
	    */

	    $claim->update([
	        'approved_amount' =>
	            $validated['approved_amount'],

	        'approved_days' =>
	            $validated['approved_days'],

	        'approval_remarks' =>
	            $validated['approval_remarks'] ?? null,

	        'status' =>
	            $newStatus,

	        'approved_by' =>
	            Auth::id(),

	        'approval_date' =>
	            now(),
	    ]);

	    /*
	    |--------------------------------------------------------------------------
	    | History
	    |--------------------------------------------------------------------------
	    */

	    ConstructionClaimHistory::create([
	        'construction_claim_id' => $claim->id,

	        'action' => 'Approved',

	        'old_status' => $oldStatus,

	        'new_status' => $newStatus,

	        'remarks' =>
	            $validated['approval_remarks'] ?? null,

	        'performed_by' => Auth::id(),

	        'performed_at' => now(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.projects.construction.claims.show',
	            [
	                'project' => $project,
	                'claim' => $claim,
	            ]
	        )
	        ->with(
	            'success',
	            $newStatus === 'Partially Approved'
	                ? 'Claim partially approved successfully.'
	                : 'Claim approved successfully.'
	        );
	}

    /**
     * Show claim rejection form.
     */
    public function rejection(
        Project $project,
        ConstructionClaim $claim
    ): View {
        $this->checkProject($project, $claim);

        if (!in_array($claim->status, [
            'Submitted',
            'Under Review',
            'Under Assessment',
        ])) {
            abort(
                422,
                'This claim cannot be rejected in its current status.'
            );
        }

        $claim->load([
            'project',
            'procurementContract.bidder',
            'workOrder',
        ]);

        return view(
            'construction.claims.rejection',
            compact(
                'project',
                'claim'
            )
        );
    }


    /**
     * Reject claim.
     */
    public function reject(
        Request $request,
        Project $project,
        ConstructionClaim $claim
    ): RedirectResponse {
        $this->checkProject($project, $claim);

        if (!in_array($claim->status, [
            'Submitted',
            'Under Review',
            'Under Assessment',
        ])) {
            return back()->withErrors([
                'error' =>
                    'This claim cannot be rejected in its current status.',
            ]);
        }

        $validated = $request->validate([
            'rejection_remarks' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $oldStatus = $claim->status;

        $claim->update([
            'status' => 'Rejected',

            'rejection_remarks' =>
                $validated['rejection_remarks'],

            'rejected_by' =>
                Auth::id(),

            'rejection_date' =>
                now(),
        ]);

        ConstructionClaimHistory::create([
            'construction_claim_id' => $claim->id,

            'action' => 'Rejected',

            'old_status' => $oldStatus,

            'new_status' => 'Rejected',

            'remarks' =>
                $validated['rejection_remarks'],

            'performed_by' => Auth::id(),

            'performed_at' => now(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.claims.show',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            )
            ->with(
                'success',
                'Claim rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    public function close(
        Request $request,
        Project $project,
        ConstructionClaim $claim
    ) {

        $this->checkProject(
            $project,
            $claim
        );


        if (!in_array(
            $claim->status,
            [
                'Approved',
                'Partially Approved',
            ]
        )) {

            return back()->with(
                'error',
                'Only approved claims can be closed.'
            );
        }


        $validated = $request->validate([

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $oldStatus = $claim->status;


        $claim->update([

            'status' =>
                'Closed',

            'closed_date' =>
                now()->toDateString(),

            'closed_by' =>
                Auth::id(),

            'remarks' =>
                $validated['remarks'] ?? $claim->remarks,

            'updated_by' =>
                Auth::id(),

        ]);


        $this->addHistory(
            $claim,
            'Closed',
            $oldStatus,
            'Closed',
            $validated['remarks'] ?? 'Claim closed.'
        );


        return back()->with(
            'success',
            'Claim closed successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionClaim $claim
    ) {

        $this->checkProject(
            $project,
            $claim
        );


        if (!in_array(
            $claim->status,
            [
                'Draft',
                'Rejected',
            ]
        )) {

            return back()->with(
                'error',
                'Only Draft or Rejected claims can be deleted.'
            );
        }


        $claim->delete();


        return redirect()
            ->route(
                'admin.projects.construction.claims.index',
                $project
            )
            ->with(
                'success',
                'Claim deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT CHECK
    |--------------------------------------------------------------------------
    */

    private function checkProject(
        Project $project,
        ConstructionClaim $claim
    ): void {

        abort_unless(
            (int) $claim->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    private function addHistory(
        ConstructionClaim $claim,
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $remarks = null
    ): void {

        ConstructionClaimHistory::create([

            'construction_claim_id' =>
                $claim->id,

            'action' =>
                $action,

            'old_status' =>
                $oldStatus,

            'new_status' =>
                $newStatus,

            'remarks' =>
                $remarks,

            'performed_by' =>
                Auth::id(),

            'performed_at' =>
                now(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CLAIM NUMBER
    |--------------------------------------------------------------------------
    |
    | CLM-2026-000001
    |
    */

    private function generateClaimNumber(): string
    {
        $year = now()->format('Y');


        $last =
            ConstructionClaim::withTrashed()
                ->whereYear(
                    'created_at',
                    $year
                )
                ->orderByDesc('id')
                ->first();


        $next = $last
            ? (
                (int) substr(
                    $last->claim_number,
                    -6
                ) + 1
            )
            : 1;


        return 'CLM-' .
            $year .
            '-' .
            str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }

    /*
	|--------------------------------------------------------------------------
	| ASSESSMENT FORM
	|--------------------------------------------------------------------------
	*/

	public function assessment(
	    Project $project,
	    ConstructionClaim $claim
	): View {

	    $this->checkProject($project, $claim);

	    /*
	    |--------------------------------------------------------------------------
	    | Only Under Review / Under Assessment claims
	    |--------------------------------------------------------------------------
	    |
	    | Under Review       = First assessment
	    | Under Assessment  = Re-open / modify assessment
	    |
	    */

	    if (!in_array($claim->status, [
	        'Under Review',
	        'Under Assessment',
	    ])) {

	        return redirect()
	            ->route(
	                'admin.projects.construction.claims.show',
	                [
	                    'project' => $project,
	                    'claim'   => $claim,
	                ]
	            )
	            ->with(
	                'error',
	                'Only claims under review or under assessment can be assessed.'
	            );
	    }

	    return view(
	        'construction.claims.assess',
	        compact(
	            'project',
	            'claim'
	        )
	    );
	}
}