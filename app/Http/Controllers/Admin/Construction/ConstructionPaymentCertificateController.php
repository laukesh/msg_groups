<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionPaymentCertificate;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConstructionPaymentCertificateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Project $project
    ) {
        /*
         * Project-scoped certificates only.
         */

        $query = ConstructionPaymentCertificate::with([
            'workOrder',
            'procurementContract',
            'creator',
        ])
            ->where(
                'project_id',
                $project->id
            );


        /*
         * Search
         */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );


            $query->where(function ($q) use ($search) {

                /*
                 * Certificate
                 */

                $q->where(
                    'certificate_number',
                    'like',
                    "%{$search}%"
                );


                /*
                 * Work Order
                 */

                $q->orWhereHas(
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
                );


                /*
                 * Procurement Contract
                 */

                $q->orWhereHas(
                    'procurementContract',
                    function ($contract) use ($search) {

                        $contract->where(
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
                            'contract_type',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'bidder_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'loa_number',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }


        /*
         * Status filter
         */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
         * Date From
         */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'certificate_date',
                '>=',
                $request->date_from
            );

        }


        /*
         * Date To
         */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'certificate_date',
                '<=',
                $request->date_to
            );

        }


        /*
         * Pagination
         */

        $certificates = $query
            ->latest('certificate_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
         * Summary
         */

        $projectCertificates =
            ConstructionPaymentCertificate::where(
                'project_id',
                $project->id
            );


        $summary = [

            'total' =>
                (clone $projectCertificates)->count(),

            'draft' =>
                (clone $projectCertificates)
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'submitted' =>
                (clone $projectCertificates)
                    ->whereIn(
                        'status',
                        [
                            'Submitted',
                            'Under Review',
                        ]
                    )
                    ->count(),

            'approved' =>
                (clone $projectCertificates)
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->count(),

            'paid' =>
                (clone $projectCertificates)
                    ->where(
                        'status',
                        'Paid'
                    )
                    ->count(),

            'certified_amount' =>
                (clone $projectCertificates)
                    ->sum(
                        'net_certified_amount'
                    ),
        ];


        return view(
            'construction.payment_certificates.index',
            compact(
                'project',
                'certificates',
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
    ) {

        /*
         * Work Orders belonging to project.
         */

        $workOrders = ConstructionWorkOrder::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->get();


        /*
         * IMPORTANT:
         *
         * Construction Contract listing must follow:
         *
         * Project
         *   -> Procurement Plan
         *   -> Package
         *   -> Tender
         *   -> Procurement Contract
         *
         * Same query used in Construction Contract listing.
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

            ->orderByDesc('id')

            ->get();

            //echo "<pre>";print_r($contracts);die();


        return view(
            'construction.payment_certificates.create',
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

        /*
         * Validation
         */

        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'certificate_date' => [
                'required',
                'date',
            ],

            'period_from' => [
                'nullable',
                'date',
            ],

            'period_to' => [
                'nullable',
                'date',
                'after_or_equal:period_from',
            ],

            'gross_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'previous_certified_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'current_certified_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'retention_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance_recovery' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
         * ----------------------------------------------------------
         * WORK ORDER VALIDATION
         * ----------------------------------------------------------
         */

        $workOrder = null;


        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

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
         * ----------------------------------------------------------
         * PROCUREMENT CONTRACT VALIDATION
         * ----------------------------------------------------------
         */

        $procurementContract = null;


        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            /*
             * Use exactly the same project-scoped
             * Procurement Contract query as construction.
             */

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
         * ----------------------------------------------------------
         * WORK ORDER / CONTRACT MATCHING
         * ----------------------------------------------------------
         *
         * Work Order:
         *
         * construction_work_orders.procurement_contract_id
         *
         * Payment Certificate:
         *
         * construction_payment_certificates.procurement_contract_id
         *
         * These must refer to the same Procurement Contract.
         */

        if (
            $workOrder &&
            $procurementContract
        ) {

            if (
                $workOrder->procurement_contract_id &&
                (
                    (int) $workOrder->procurement_contract_id
                    !==
                    (int) $procurementContract->id
                )
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
         * ----------------------------------------------------------
         * AUTO DERIVE CONTRACT
         * ----------------------------------------------------------
         *
         * Only when the user did NOT select a contract.
         *
         * NEVER overwrite an explicitly selected contract.
         */

        if (
            empty(
                $validated[
                    'procurement_contract_id'
                ]
            ) &&
            $workOrder
        ) {

            if (
                $workOrder->procurement_contract_id
            ) {

                $derivedContract =
                    ProcurementContract::query()

                        ->where(
                            'id',
                            $workOrder->procurement_contract_id
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


                if ($derivedContract) {

                    $validated[
                        'procurement_contract_id'
                    ] =
                        $derivedContract->id;

                }

            }

        }


        /*
         * ----------------------------------------------------------
         * AMOUNT CALCULATION
         * ----------------------------------------------------------
         */

        $retention =
            (float) (
                $validated[
                    'retention_amount'
                ] ?? 0
            );


        $advanceRecovery =
            (float) (
                $validated[
                    'advance_recovery'
                ] ?? 0
            );


        $otherDeductions =
            (float) (
                $validated[
                    'other_deductions'
                ] ?? 0
            );


        $currentCertified =
            (float) (
                $validated[
                    'current_certified_amount'
                ]
            );


        $netCertified =
            $currentCertified
            - $retention
            - $advanceRecovery
            - $otherDeductions;


        /*
         * Net amount cannot be negative.
         */

        if ($netCertified < 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'current_certified_amount' =>
                        'Deductions cannot exceed the current certified amount.',
                ]);

        }


        /*
         * ----------------------------------------------------------
         * CREATE
         * ----------------------------------------------------------
         */

        $certificate = DB::transaction(
            function () use (
                $validated,
                $project,
                $netCertified
            ) {

                $certificate =
                    new ConstructionPaymentCertificate();


                $certificate->project_id =
                    $project->id;


                $certificate->construction_work_order_id =
                    $validated[
                        'construction_work_order_id'
                    ] ?? null;


                /*
                 * IMPORTANT:
                 *
                 * Save procurement_contract_id,
                 * NOT contract_id.
                 */

                $certificate->procurement_contract_id =
                    $validated[
                        'procurement_contract_id'
                    ] ?? null;


                $certificate->certificate_number =
                    $this->generateCertificateNumber();


                $certificate->certificate_date =
                    $validated[
                        'certificate_date'
                    ];


                $certificate->period_from =
                    $validated[
                        'period_from'
                    ] ?? null;


                $certificate->period_to =
                    $validated[
                        'period_to'
                    ] ?? null;


                $certificate->gross_amount =
                    $validated[
                        'gross_amount'
                    ];


                $certificate->previous_certified_amount =
                    $validated[
                        'previous_certified_amount'
                    ];


                $certificate->current_certified_amount =
                    $validated[
                        'current_certified_amount'
                    ];


                $certificate->retention_amount =
                    $validated[
                        'retention_amount'
                    ] ?? 0;


                $certificate->advance_recovery =
                    $validated[
                        'advance_recovery'
                    ] ?? 0;


                $certificate->other_deductions =
                    $validated[
                        'other_deductions'
                    ] ?? 0;


                $certificate->net_certified_amount =
                    $netCertified;


                $certificate->status =
                    'Draft';


                $certificate->remarks =
                    $validated[
                        'remarks'
                    ] ?? null;


                $certificate->created_by =
                    Auth::id();


                $certificate->updated_by =
                    Auth::id();

                //echo "<pre>";print_r($certificate);die();


                $certificate->save();


                return $certificate;
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.payment-certificates.show',
                [
                    'project' =>
                        $project,

                    'payment_certificate' =>
                        $certificate,
                ]
            )
            ->with(
                'success',
                'Payment Certificate created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionPaymentCertificate $payment_certificate
    ) {

        /*
         * Project isolation.
         */

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        $payment_certificate->load([

            'workOrder',

            'procurementContract',

            'submittedBy',

            'approvedBy',

            'rejectedBy',

            'creator',

            'updater',

        ]);

        //echo "<pre>";print_r($payment_certificate);die();


        return view(
            'construction.payment_certificates.show',
            compact(
                'project',
                'payment_certificate'
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
        ConstructionPaymentCertificate $payment_certificate
    ) {

        /*
         * Project isolation.
         */

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        /*
         * Only Draft / Rejected certificates
         * can be edited.
         */

        if (
            !in_array(
                $payment_certificate->status,
                [
                    'Draft',
                    'Rejected',
                ]
            )
        ) {

            return back()->with(
                'error',
                'Only Draft or Rejected certificates can be edited.'
            );

        }


        /*
         * Work Orders
         */

        $workOrders =
            ConstructionWorkOrder::where(
                'project_id',
                $project->id
            )
            ->orderByDesc('id')
            ->get();


        /*
         * Procurement Contracts
         *
         * Same construction listing query.
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
            'construction.payment_certificates.edit',
            compact(
                'project',
                'payment_certificate',
                'workOrders',
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
        ConstructionPaymentCertificate $payment_certificate
    ) {
        /*
        |--------------------------------------------------------------------------
        | PROJECT ISOLATION
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | EDITABLE STATUS
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $payment_certificate->status,
            ['Draft', 'Rejected']
        )) {

            return back()->with(
                'error',
                'Only Draft or Rejected certificates can be edited.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'certificate_date' => [
                'required',
                'date',
            ],

            'period_from' => [
                'nullable',
                'date',
            ],

            'period_to' => [
                'nullable',
                'date',
                'after_or_equal:period_from',
            ],

            'gross_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'previous_certified_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'current_certified_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'retention_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance_recovery' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_deductions' => [
                'nullable',
                'numeric',
                'min:0',
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
        |
        | If a Work Order is selected, it MUST belong to this project.
        |
        */

        $workOrder = null;

        if (!empty($validated['construction_work_order_id'])) {

            $workOrder = ConstructionWorkOrder::query()
                ->where('id', $validated['construction_work_order_id'])
                ->where('project_id', $project->id)
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
        |
        | IMPORTANT:
        |
        | Always use procurement_contract_id.
        |
        | Contract must belong to this project through:
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

        $procurementContract = null;

        if (!empty($validated['procurement_contract_id'])) {

            $procurementContract = ProcurementContract::query()

                ->where(
                    'id',
                    $validated['procurement_contract_id']
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
        | WORK ORDER / CONTRACT CONSISTENCY
        |--------------------------------------------------------------------------
        |
        | If both are selected and Work Order already has a contract,
        | they must be the same contract.
        |
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
        |
        | ONLY derive the contract when the user did NOT select one.
        |
        | Never overwrite an explicitly selected contract.
        |
        */

        if (
            empty($validated['procurement_contract_id']) &&
            $workOrder &&
            $workOrder->procurement_contract_id
        ) {

            $derivedContract = ProcurementContract::query()

                ->where(
                    'id',
                    $workOrder->procurement_contract_id
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


            if ($derivedContract) {

                $validated['procurement_contract_id'] =
                    $derivedContract->id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AMOUNTS
        |--------------------------------------------------------------------------
        */

        $retention =
            (float) (
                $validated['retention_amount'] ?? 0
            );

        $advanceRecovery =
            (float) (
                $validated['advance_recovery'] ?? 0
            );

        $otherDeductions =
            (float) (
                $validated['other_deductions'] ?? 0
            );

        $currentCertified =
            (float) (
                $validated['current_certified_amount']
            );


        /*
        |--------------------------------------------------------------------------
        | NET CERTIFIED AMOUNT
        |--------------------------------------------------------------------------
        */

        $netCertified =
            $currentCertified
            - $retention
            - $advanceRecovery
            - $otherDeductions;


        if ($netCertified < 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'current_certified_amount' =>
                        'Deductions cannot exceed the current certified amount.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $payment_certificate->update([

            /*
            |--------------------------------------------------------------------------
            | RELATIONSHIPS
            |--------------------------------------------------------------------------
            */

            'construction_work_order_id' =>
                $validated['construction_work_order_id'] ?? null,

            'procurement_contract_id' =>
                $validated['procurement_contract_id'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | CERTIFICATE DETAILS
            |--------------------------------------------------------------------------
            */

            'certificate_date' =>
                $validated['certificate_date'],

            'period_from' =>
                $validated['period_from'] ?? null,

            'period_to' =>
                $validated['period_to'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | AMOUNTS
            |--------------------------------------------------------------------------
            */

            'gross_amount' =>
                $validated['gross_amount'],

            'previous_certified_amount' =>
                $validated['previous_certified_amount'],

            'current_certified_amount' =>
                $validated['current_certified_amount'],

            'retention_amount' =>
                $retention,

            'advance_recovery' =>
                $advanceRecovery,

            'other_deductions' =>
                $otherDeductions,

            'net_certified_amount' =>
                $netCertified,


            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            'remarks' =>
                $validated['remarks'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | AUDIT
            |--------------------------------------------------------------------------
            */

            'updated_by' =>
                Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.construction.payment-certificates.show',
                [
                    'project' =>
                        $project,

                    'payment_certificate' =>
                        $payment_certificate,
                ]
            )
            ->with(
                'success',
                'Payment Certificate updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            $payment_certificate->status !==
            'Draft'
        ) {

            return back()->with(
                'error',
                'Only Draft certificates can be submitted.'
            );

        }


        $payment_certificate->update([

            'status' =>
                'Submitted',

            'submitted_date' =>
                now()->toDateString(),

            'submitted_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Payment Certificate submitted for review.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    */

    public function review(
        Project $project,
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            $payment_certificate->status !==
            'Submitted'
        ) {

            return back()->with(
                'error',
                'Only Submitted certificates can be moved to review.'
            );

        }


        $payment_certificate->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Payment Certificate moved to Under Review.'
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
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            !in_array(
                $payment_certificate->status,
                [
                    'Submitted',
                    'Under Review',
                ]
            )
        ) {

            return back()->with(
                'error',
                'Only Submitted or Under Review certificates can be approved.'
            );

        }


        $payment_certificate->update([

            'status' =>
                'Approved',

            'approved_by' =>
                Auth::id(),

            'approval_date' =>
                now()->toDateString(),

            'approval_remarks' =>
                $request->approval_remarks,

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Payment Certificate approved successfully.'
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
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            !in_array(
                $payment_certificate->status,
                [
                    'Submitted',
                    'Under Review',
                ]
            )
        ) {

            return back()->with(
                'error',
                'Only Submitted or Under Review certificates can be rejected.'
            );

        }


        $request->validate([

            'rejection_remarks' => [
                'required',
                'string',
            ],

        ]);


        $payment_certificate->update([

            'status' =>
                'Rejected',

            'rejected_by' =>
                Auth::id(),

            'rejection_date' =>
                now()->toDateString(),

            'rejection_remarks' =>
                $request->rejection_remarks,

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Payment Certificate rejected.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK PAID
    |--------------------------------------------------------------------------
    */

    public function markPaid(
        Project $project,
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            $payment_certificate->status !==
            'Approved'
        ) {

            return back()->with(
                'error',
                'Only Approved certificates can be marked as Paid.'
            );

        }


        $payment_certificate->update([

            'status' =>
                'Paid',

            'paid_date' =>
                now()->toDateString(),

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Payment Certificate marked as Paid.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionPaymentCertificate $payment_certificate
    ) {

        abort_unless(
            (int) $payment_certificate->project_id ===
            (int) $project->id,
            404
        );


        if (
            !in_array(
                $payment_certificate->status,
                [
                    'Draft',
                    'Rejected',
                ]
            )
        ) {

            return back()->with(
                'error',
                'Only Draft or Rejected certificates can be deleted.'
            );

        }


        $payment_certificate->delete();


        return redirect()
            ->route(
                'admin.projects.construction.payment-certificates.index',
                $project
            )
            ->with(
                'success',
                'Payment Certificate deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE CERTIFICATE NUMBER
    |--------------------------------------------------------------------------
    |
    | Format:
    |
    | PC-2026-000001
    |
    */

    private function generateCertificateNumber()
    {
        $year = now()->format('Y');


        $last =
            ConstructionPaymentCertificate::withTrashed()
                ->whereYear(
                    'created_at',
                    $year
                )
                ->orderByDesc('id')
                ->first();


        $next = $last
            ? (
                (int) substr(
                    $last->certificate_number,
                    -6
                )
                + 1
            )
            : 1;


        return 'PC-' .
            $year .
            '-' .
            str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}