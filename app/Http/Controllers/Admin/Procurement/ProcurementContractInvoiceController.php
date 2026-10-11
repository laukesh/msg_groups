<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementContractInvoiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract
        |--------------------------------------------------------------------------
        */

        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        /*
        |--------------------------------------------------------------------------
        | Get Invoices
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | project_id is explicitly checked here so an invoice from another
        | project can never appear inside this project.
        |
        */

        $invoices = $contract
            ->invoices()
            ->where('project_id', $project->id)
            ->with([
                'milestone',
                'payments',
            ])
            ->latest('id')
            ->get();

        return view(
            'procurement.contract-invoices.index',
            compact(
                'project',
                'procurementTender',
                'contract',
                'invoices'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Milestone
        |--------------------------------------------------------------------------
        */

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );

        /*
        |--------------------------------------------------------------------------
        | Contract Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $contract->status === 'Active',
            403,
            'Invoice can only be created for an Active Contract.'
        );

        /*
        |--------------------------------------------------------------------------
        | Milestone Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $milestone->status === 'Completed',
            403,
            'Invoice can only be created for a Completed Milestone.'
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Already Invoiced BASE Amount
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Milestone amount is compared against invoice BASE amounts.
        | Tax is NOT counted against the milestone amount.
        |
        */

        $totalInvoiced = $milestone
            ->invoices()
            ->where('project_id', $project->id)
            ->whereNotIn(
                'status',
                [
                    'Rejected',
                    'Cancelled',
                ]
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Remaining Amount
        |--------------------------------------------------------------------------
        */

        $remainingToInvoice = max(
            0,
            (float) $milestone->milestone_amount
                - (float) $totalInvoiced
        );


        /*
        |--------------------------------------------------------------------------
        | No Remaining Amount
        |--------------------------------------------------------------------------
        */

        if ($remainingToInvoice <= 0) {

            return redirect()
                ->route(
                    'admin.projects.procurement.tenders.contracts.invoices.index',
                    [
                        'project' => $project,

                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,
                    ]
                )
                ->with(
                    'error',
                    'This milestone has already been fully invoiced.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Preview Invoice Number
        |--------------------------------------------------------------------------
        */

        $invoiceNumber =
            $this->generateInvoiceNumber();


        return view(
            'procurement.contract-invoices.create',
            compact(
                'project',
                'procurementTender',
                'contract',
                'milestone',
                'totalInvoiced',
                'remainingToInvoice',
                'invoiceNumber'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Milestone
        |--------------------------------------------------------------------------
        */

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );


        /*
        |--------------------------------------------------------------------------
        | Contract Validation
        |--------------------------------------------------------------------------
        */

        if ($contract->status !== 'Active') {

            return back()
                ->withInput()
                ->withErrors([
                    'invoice' =>
                        'Invoice can only be created for an Active Contract.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Milestone Validation
        |--------------------------------------------------------------------------
        */

        if ($milestone->status !== 'Completed') {

            return back()
                ->withInput()
                ->withErrors([
                    'invoice' =>
                        'Invoice can only be created for a Completed Milestone.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Input
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'invoice_date' => [
                'required',
                'date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Amounts
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $taxAmount = (float) (
            $validated['tax_amount'] ?? 0
        );

        $discountAmount = (float) (
            $validated['discount_amount'] ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Net Invoice Amount
        |--------------------------------------------------------------------------
        */

        $netAmount =
            $amount
            + $taxAmount
            - $discountAmount;


        if ($netAmount <= 0) {

            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        'Invoice net amount must be greater than zero.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Invoice
        |--------------------------------------------------------------------------
        */

        $createdInvoice = null;


        try {

            DB::transaction(function () use (
                $project,
                $validated,
                $contract,
                $milestone,
                $amount,
                $taxAmount,
                $discountAmount,
                $netAmount,
                &$createdInvoice
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Milestone
                |--------------------------------------------------------------------------
                */

                $lockedMilestone =
                    ProcurementContractMilestone::query()
                        ->whereKey($milestone->id)
                        ->where(
                            'procurement_contract_id',
                            $contract->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Recalculate Existing BASE Invoices
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Use amount, NOT net_amount.
                |
                */

                $totalInvoiced =
                    ProcurementContractInvoice::query()
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->where(
                            'procurement_contract_id',
                            $contract->id
                        )
                        ->where(
                            'procurement_contract_milestone_id',
                            $lockedMilestone->id
                        )
                        ->whereNotIn(
                            'status',
                            [
                                'Rejected',
                                'Cancelled',
                            ]
                        )
                        ->sum('amount');


                /*
                |--------------------------------------------------------------------------
                | Remaining BASE Amount
                |--------------------------------------------------------------------------
                */

                $remainingToInvoice = max(
                    0,
                    (float) $lockedMilestone->milestone_amount
                        - (float) $totalInvoiced
                );


                /*
                |--------------------------------------------------------------------------
                | Prevent Over-Invoicing
                |--------------------------------------------------------------------------
                |
                | Compare BASE amount with remaining milestone amount.
                |
                */

                if ($amount > $remainingToInvoice) {

                    throw new \RuntimeException(
                        'Invoice base amount exceeds the remaining milestone amount of '
                        . number_format(
                            $remainingToInvoice,
                            2
                        )
                        . ' '
                        . $lockedMilestone->currency
                        . '.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Currency Validation
                |--------------------------------------------------------------------------
                */

                if (
                    strtoupper($validated['currency'])
                    !==
                    strtoupper($lockedMilestone->currency)
                ) {

                    throw new \RuntimeException(
                        'Invoice currency must match the milestone currency ('
                        . $lockedMilestone->currency
                        . ').'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Generate Invoice Number
                |--------------------------------------------------------------------------
                */

                $invoiceNumber =
                    $this->generateInvoiceNumber();


                /*
                |--------------------------------------------------------------------------
                | Create Invoice
                |--------------------------------------------------------------------------
                */

                $createdInvoice =
                    ProcurementContractInvoice::create([

                        /*
                        |--------------------------------------------------------------------------
                        | PROJECT
                        |--------------------------------------------------------------------------
                        */

                        'project_id' =>
                            $project->id,

                        /*
                        |--------------------------------------------------------------------------
                        | CONTRACT
                        |--------------------------------------------------------------------------
                        */

                        'procurement_contract_id' =>
                            $contract->id,

                        /*
                        |--------------------------------------------------------------------------
                        | MILESTONE
                        |--------------------------------------------------------------------------
                        */

                        'procurement_contract_milestone_id' =>
                            $lockedMilestone->id,

                        /*
                        |--------------------------------------------------------------------------
                        | Invoice Number
                        |--------------------------------------------------------------------------
                        */

                        'invoice_number' =>
                            $invoiceNumber,

                        /*
                        |--------------------------------------------------------------------------
                        | Invoice Date
                        |--------------------------------------------------------------------------
                        */

                        'invoice_date' =>
                            $validated['invoice_date'],

                        /*
                        |--------------------------------------------------------------------------
                        | Invoice Type
                        |--------------------------------------------------------------------------
                        */

                        'invoice_type' =>
                            'Milestone',

                        /*
                        |--------------------------------------------------------------------------
                        | Description
                        |--------------------------------------------------------------------------
                        */

                        'description' =>
                            $validated['description'] ?? null,

                        /*
                        |--------------------------------------------------------------------------
                        | Base Amount
                        |--------------------------------------------------------------------------
                        */

                        'amount' =>
                            $amount,

                        /*
                        |--------------------------------------------------------------------------
                        | Tax
                        |--------------------------------------------------------------------------
                        */

                        'tax_amount' =>
                            $taxAmount,

                        /*
                        |--------------------------------------------------------------------------
                        | Discount
                        |--------------------------------------------------------------------------
                        */

                        'discount_amount' =>
                            $discountAmount,

                        /*
                        |--------------------------------------------------------------------------
                        | Net Invoice Amount
                        |--------------------------------------------------------------------------
                        */

                        'net_amount' =>
                            $netAmount,

                        /*
                        |--------------------------------------------------------------------------
                        | Currency
                        |--------------------------------------------------------------------------
                        */

                        'currency' =>
                            strtoupper($validated['currency']),

                        /*
                        |--------------------------------------------------------------------------
                        | Status
                        |--------------------------------------------------------------------------
                        */

                        'status' =>
                            'Draft',

                        /*
                        |--------------------------------------------------------------------------
                        | Remarks
                        |--------------------------------------------------------------------------
                        */

                        'remarks' =>
                            $validated['remarks'] ?? null,

                        /*
                        |--------------------------------------------------------------------------
                        | Audit
                        |--------------------------------------------------------------------------
                        */

                        'created_by' =>
                            auth()->id(),

                        'updated_by' =>
                            auth()->id(),

                    ]);
            });


        } catch (\RuntimeException $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'amount' =>
                        $e->getMessage(),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.contracts.invoices.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'invoice' =>
                        $createdInvoice,
                ]
            )
            ->with(
                'success',
                'Invoice '
                . $createdInvoice->invoice_number
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractInvoice $invoice
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Invoice
        |--------------------------------------------------------------------------
        */

        $this->validateInvoice(
            $project,
            $procurementTender,
            $contract,
            $invoice
        );


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $invoice->load([
            'project',
            'milestone',
            'payments',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Paid Amount
        |--------------------------------------------------------------------------
        */

        $totalPaid = $invoice
            ->payments()
            ->where('status', 'Processed')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Payment Outstanding
        |--------------------------------------------------------------------------
        */

        $paymentOutstanding = max(
            0,
            (float) $invoice->net_amount
                - (float) $totalPaid
        );


        return view(
            'procurement.contract-invoices.show',
            compact(
                'project',
                'procurementTender',
                'contract',
                'invoice',
                'totalPaid',
                'paymentOutstanding'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractInvoice $invoice
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Invoice
        |--------------------------------------------------------------------------
        */

        $this->validateInvoice(
            $project,
            $procurementTender,
            $contract,
            $invoice
        );


        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if ($invoice->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft invoices can be submitted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Invoice
        |--------------------------------------------------------------------------
        */

        $invoice->update([

            'status' =>
                'Submitted',

            'submitted_at' =>
                now(),

            'submitted_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.contracts.invoices.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'invoice' =>
                        $invoice,
                ]
            )
            ->with(
                'success',
                'Invoice submitted successfully.'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractInvoice $invoice
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Invoice
        |--------------------------------------------------------------------------
        */

        $this->validateInvoice(
            $project,
            $procurementTender,
            $contract,
            $invoice
        );


        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if ($invoice->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted invoices can be approved.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approve Invoice
        |--------------------------------------------------------------------------
        */

        $invoice->update([

            'status' =>
                'Approved',

            'approved_at' =>
                now(),

            'approved_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.contracts.invoices.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'invoice' =>
                        $invoice,
                ]
            )
            ->with(
                'success',
                'Invoice approved successfully.'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractInvoice $invoice
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Project → Tender → Contract → Invoice
        |--------------------------------------------------------------------------
        */

        $this->validateInvoice(
            $project,
            $procurementTender,
            $contract,
            $invoice
        );


        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        */

        if ($invoice->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted invoices can be rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Rejection Remarks
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'rejection_remarks' => [
                'required',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Reject Invoice
        |--------------------------------------------------------------------------
        */

        $invoice->update([

            'status' =>
                'Rejected',

            'rejected_at' =>
                now(),

            'rejected_by' =>
                auth()->id(),

            'rejection_remarks' =>
                $validated['rejection_remarks'],

            'updated_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.contracts.invoices.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'invoice' =>
                        $invoice,
                ]
            )
            ->with(
                'success',
                'Invoice rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE INVOICE NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');


        $lastInvoice = ProcurementContractInvoice::query()
            ->where(
                'invoice_number',
                'like',
                'INV-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if (!$lastInvoice) {

            $nextNumber = 1;

        } else {

            $lastNumber = (int) substr(
                $lastInvoice->invoice_number,
                strrpos(
                    $lastInvoice->invoice_number,
                    '-'
                ) + 1
            );

            $nextNumber = $lastNumber + 1;
        }


        return sprintf(
            'INV-%s-%03d',
            $year,
            $nextNumber
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE CONTRACT
    |--------------------------------------------------------------------------
    |
    | Project
    |   ↓
    | Tender
    |   ↓
    | Contract
    |
    */

    private function validateContract(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Tender must belong to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id ===
            (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract must belong to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->project_id ===
            (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract must belong to Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INVOICE
    |--------------------------------------------------------------------------
    |
    | Project
    |   ↓
    | Tender
    |   ↓
    | Contract
    |   ↓
    | Invoice
    |
    */

    private function validateInvoice(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractInvoice $invoice
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Validate Tender + Contract
        |--------------------------------------------------------------------------
        */

        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );


        /*
        |--------------------------------------------------------------------------
        | Invoice must belong to Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $invoice->project_id ===
            (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Invoice must belong to Contract
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $invoice->procurement_contract_id ===
            (int) $contract->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE MILESTONE
    |--------------------------------------------------------------------------
    |
    | Project
    |   ↓
    | Tender
    |   ↓
    | Contract
    |   ↓
    | Milestone
    |
    */

    private function validateMilestone(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Validate Tender + Contract
        |--------------------------------------------------------------------------
        */

        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );


        /*
        |--------------------------------------------------------------------------
        | Milestone must belong to Contract
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $milestone->procurement_contract_id ===
            (int) $contract->id,
            404
        );
    }
}