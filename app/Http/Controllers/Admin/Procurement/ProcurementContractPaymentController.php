<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementContractPayment;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementContractPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        $this->validateContract(
            $procurementTender,
            $contract
        );

        $payments = $contract->payments()
            ->with([
                'invoice',
                'milestone',
            ])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        return view(
            'procurement.contract-payments.index',
            compact(
                'procurementTender',
                'contract',
                'payments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        $this->validateContract(
            $procurementTender,
            $contract
        );

        /*
        |--------------------------------------------------------------------------
        | Selected Invoice
        |--------------------------------------------------------------------------
        */

        $selectedInvoice = null;

        if ($request->filled('invoice_id')) {

            $selectedInvoice = ProcurementContractInvoice::query()
                ->where(
                    'id',
                    $request->invoice_id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->with([
                    'milestone',
                ])
                ->first();

            if (!$selectedInvoice) {

                abort(
                    404,
                    'Invoice does not belong to this contract.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Invoice Must Be Payable
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $selectedInvoice->status,
                    [
                        'Approved',
                        'Partially Paid',
                    ],
                    true
                )
            ) {

                return redirect()
                    ->route(
                        'admin.procurement.tenders.contracts.invoices.show',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'contract' =>
                                $contract,

                            'invoice' =>
                                $selectedInvoice,
                        ]
                    )
                    ->with(
                        'error',
                        'This invoice is not available for payment.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Outstanding Amount
            |--------------------------------------------------------------------------
            */

            $paidAmount = $selectedInvoice
                ->payments()
                ->where(
                    'status',
                    'Processed'
                )
                ->sum('amount');

            $outstandingAmount = max(
                0,
                (float) $selectedInvoice->net_amount
                    - (float) $paidAmount
            );


            /*
            |--------------------------------------------------------------------------
            | Already Fully Paid
            |--------------------------------------------------------------------------
            */

            if ($outstandingAmount <= 0) {

                return redirect()
                    ->route(
                        'admin.procurement.tenders.contracts.invoices.show',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'contract' =>
                                $contract,

                            'invoice' =>
                                $selectedInvoice,
                        ]
                    )
                    ->with(
                        'error',
                        'This invoice has already been fully paid.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Available Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = $contract
            ->invoices()
            ->with([
                'milestone',
            ])
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Partially Paid',
                ]
            )
            ->latest('invoice_date')
            ->latest('id')
            ->get()
            ->filter(function ($invoice) {

                $paidAmount = $invoice
                    ->payments()
                    ->where(
                        'status',
                        'Processed'
                    )
                    ->sum('amount');

                $outstandingAmount = max(
                    0,
                    (float) $invoice->net_amount
                        - (float) $paidAmount
                );

                return $outstandingAmount > 0;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        */

        $milestones = $contract
            ->milestones()
            ->orderBy(
                'planned_start_date'
            )
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Selected Invoice Payment Information
        |--------------------------------------------------------------------------
        */

        $selectedInvoicePaidAmount = 0;
        $selectedInvoiceOutstandingAmount = 0;

        if ($selectedInvoice) {

            $selectedInvoicePaidAmount =
                (float) $selectedInvoice
                    ->payments()
                    ->where(
                        'status',
                        'Processed'
                    )
                    ->sum('amount');

            $selectedInvoiceOutstandingAmount =
                max(
                    0,
                    (float) $selectedInvoice->net_amount
                        - $selectedInvoicePaidAmount
                );
        }


        return view(
            'procurement.contract-payments.create',
            compact(
                'procurementTender',
                'contract',
                'invoices',
                'milestones',
                'selectedInvoice',
                'selectedInvoicePaidAmount',
                'selectedInvoiceOutstandingAmount'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        |
        | Payment Number is intentionally NOT accepted from the user.
        | It is generated automatically after the payment record is created.
        |
        */

        $validated = $request->validate([

            'procurement_contract_invoice_id' =>
                'required|integer|exists:procurement_contract_invoices,id',

            'procurement_contract_milestone_id' =>
                'nullable|integer|exists:procurement_contract_milestones,id',

            'payment_date' =>
                'required|date',

            'payment_type' =>
                'required|string|max:50',

            'amount' =>
                'required|numeric|min:0.01',

            'payment_method' =>
                'nullable|string|max:50',

            'transaction_reference' =>
                'nullable|string|max:150',

            'bank_name' =>
                'nullable|string|max:150',

            'account_reference' =>
                'nullable|string|max:150',

            'description' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        DB::transaction(function () use (
            $validated,
            $contract
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Invoice
            |--------------------------------------------------------------------------
            */

            $invoice = ProcurementContractInvoice::query()
                ->whereKey(
                    $validated[
                        'procurement_contract_invoice_id'
                    ]
                )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Invoice Must Belong To Contract
            |--------------------------------------------------------------------------
            */

            if (
                (int) $invoice->procurement_contract_id
                !==
                (int) $contract->id
            ) {

                abort(
                    422,
                    'Selected invoice does not belong to this contract.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Invoice Must Be Payable
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $invoice->status,
                    [
                        'Approved',
                        'Partially Paid',
                    ],
                    true
                )
            ) {

                abort(
                    422,
                    'Payment can only be created for an approved or partially paid invoice.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Current Balance
            |--------------------------------------------------------------------------
            */

            $processedAmount =
                (float) $invoice
                    ->payments()
                    ->where(
                        'status',
                        'Processed'
                    )
                    ->sum('amount');


            $balance =
                max(
                    0,
                    (float) $invoice->net_amount
                        - $processedAmount
                );


            /*
            |--------------------------------------------------------------------------
            | Prevent Overpayment
            |--------------------------------------------------------------------------
            */

            if (
                (float) $validated['amount']
                >
                $balance
            ) {

                abort(
                    422,
                    'Payment amount cannot exceed the invoice outstanding amount of '
                    . number_format(
                        $balance,
                        2
                    )
                    . '.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Milestone
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated[
                        'procurement_contract_milestone_id'
                    ]
                )
            ) {

                $milestoneExists =
                    ProcurementContractMilestone::query()
                        ->whereKey(
                            $validated[
                                'procurement_contract_milestone_id'
                            ]
                        )
                        ->where(
                            'procurement_contract_id',
                            $contract->id
                        )
                        ->exists();


                if (!$milestoneExists) {

                    abort(
                        422,
                        'Selected milestone does not belong to this contract.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            |
            | A temporary unique payment number is used initially because
            | the actual number is generated from the newly-created ID.
            |
            */

            $payment = ProcurementContractPayment::create([

                'procurement_contract_id' =>
                    $contract->id,

                'procurement_contract_invoice_id' =>
                    $invoice->id,

                'procurement_contract_milestone_id' =>
                    $validated[
                        'procurement_contract_milestone_id'
                    ] ?? null,

                /*
                | Temporary unique value.
                */
                'payment_number' =>
                    'TEMP-' . uniqid('', true),

                'payment_date' =>
                    $validated['payment_date'],

                'payment_type' =>
                    $validated['payment_type'],

                'amount' =>
                    $validated['amount'],

                /*
                | Currency is taken from the invoice/contract.
                | Keep this commented if your payment table does not
                | contain a currency column.
                */
                // 'currency' =>
                //     $invoice->currency,

                'payment_method' =>
                    $validated['payment_method']
                    ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference']
                    ?? null,

                'bank_name' =>
                    $validated['bank_name']
                    ?? null,

                'account_reference' =>
                    $validated['account_reference']
                    ?? null,

                'description' =>
                    $validated['description']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'status' =>
                    'Draft',

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate Final Payment Number
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | PAY-2026-000001
            | PAY-2026-000002
            | PAY-2026-000003
            |
            | Using the database ID guarantees uniqueness.
            |
            */

            $paymentNumber =
                'PAY-'
                . now()->format('Y')
                . '-'
                . str_pad(
                    $payment->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            $payment->update([

                'payment_number' =>
                    $paymentNumber,

                'updated_by' =>
                    auth()->id(),

            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.payments.index',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,
                ]
            )
            ->with(
                'success',
                'Payment created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): View {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        $this->validatePayment(
            $contract,
            $payment
        );


        $payment->load([
            'contract',
            'invoice',
            'milestone',
        ]);


        return view(
            'procurement.contract-payments.show',
            compact(
                'procurementTender',
                'contract',
                'payment'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        $this->validatePayment(
            $contract,
            $payment
        );


        if ($payment->status !== 'Draft') {

            return back()
                ->with(
                    'error',
                    'Only Draft payments can be submitted.'
                );
        }


        $payment->update([

            'status' =>
                'Submitted',

            'submitted_at' =>
                now(),

            'submitted_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Payment submitted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        $this->validatePayment(
            $contract,
            $payment
        );


        if ($payment->status !== 'Submitted') {

            return back()
                ->with(
                    'error',
                    'Only Submitted payments can be approved.'
                );
        }


        DB::transaction(function () use (
            $payment
        ) {

            $lockedPayment =
                ProcurementContractPayment::query()
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            if ($lockedPayment->status !== 'Submitted') {

                abort(
                    422,
                    'Payment is no longer in Submitted status.'
                );
            }


            $lockedPayment->update([

                'status' =>
                    'Approved',

                'approved_at' =>
                    now(),

                'approved_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);
        });


        return back()
            ->with(
                'success',
                'Payment approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS
    |--------------------------------------------------------------------------
    */

    public function process(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        $this->validatePayment(
            $contract,
            $payment
        );


        if ($payment->status === 'Processed') {

            return back()
                ->with(
                    'error',
                    'This payment has already been processed.'
                );
        }


        if (
            !$payment->procurement_contract_invoice_id
        ) {

            return back()
                ->with(
                    'error',
                    'Payment is not linked to an invoice.'
                );
        }


        $invoice =
            ProcurementContractInvoice::query()
                ->find(
                    $payment->procurement_contract_invoice_id
                );


        if (!$invoice) {

            return back()
                ->with(
                    'error',
                    'The invoice linked to this payment could not be found.'
                );
        }


        if (
            (int) $invoice->procurement_contract_id
            !==
            (int) $contract->id
        ) {

            return back()
                ->with(
                    'error',
                    'The payment invoice does not belong to this contract.'
                );
        }


        if (
            !in_array(
                $invoice->status,
                [
                    'Approved',
                    'Partially Paid',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Invoice is not available for payment processing.'
                );
        }


        $paymentAmount =
            (float) $payment->amount;


        if ($paymentAmount <= 0) {

            return back()
                ->with(
                    'error',
                    'Payment amount must be greater than zero.'
                );
        }


        $processedAmount =
            (float) ProcurementContractPayment::query()
                ->where(
                    'procurement_contract_invoice_id',
                    $invoice->id
                )
                ->where(
                    'status',
                    'Processed'
                )
                ->where(
                    'id',
                    '!=',
                    $payment->id
                )
                ->sum('amount');


        $invoiceAmount =
            (float) $invoice->net_amount;


        $balance =
            max(
                0,
                $invoiceAmount - $processedAmount
            );


        if ($paymentAmount > $balance) {

            return back()
                ->with(
                    'error',
                    'Payment cannot be processed because it exceeds the current invoice outstanding balance. '
                    . 'Payment: ₹'
                    . number_format(
                        $paymentAmount,
                        2
                    )
                    . ' | Outstanding: ₹'
                    . number_format(
                        $balance,
                        2
                    )
                );
        }


        DB::transaction(function () use (
            $payment,
            $invoice
        ) {

            $lockedPayment =
                ProcurementContractPayment::query()
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            if (
                $lockedPayment->status !== 'Approved'
            ) {

                throw new \RuntimeException(
                    'Only Approved payments can be processed.'
                );
            }


            $lockedInvoice =
                ProcurementContractInvoice::query()
                    ->whereKey($invoice->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            $processedAmount =
                (float) ProcurementContractPayment::query()
                    ->where(
                        'procurement_contract_invoice_id',
                        $lockedInvoice->id
                    )
                    ->where(
                        'status',
                        'Processed'
                    )
                    ->where(
                        'id',
                        '!=',
                        $lockedPayment->id
                    )
                    ->sum('amount');


            $invoiceAmount =
                (float) $lockedInvoice->net_amount;


            $balance =
                max(
                    0,
                    $invoiceAmount - $processedAmount
                );


            if (
                (float) $lockedPayment->amount
                >
                $balance
            ) {

                throw new \RuntimeException(
                    'Payment exceeds the current invoice outstanding balance.'
                );
            }


            $lockedPayment->update([

                'status' =>
                    'Processed',

                'processed_at' =>
                    now(),

                'processed_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);


            $newProcessedAmount =
                $processedAmount
                +
                (float) $lockedPayment->amount;


            $newBalance =
                max(
                    0,
                    $invoiceAmount - $newProcessedAmount
                );


            if ($newBalance <= 0) {

                $lockedInvoice->update([

                    'status' =>
                        'Paid',

                    'paid_at' =>
                        now(),

                    'updated_at' =>
                        now(),

                ]);

            } else {

                $lockedInvoice->update([

                    'status' =>
                        'Partially Paid',

                    'paid_at' =>
                        null,

                    'updated_at' =>
                        now(),

                ]);
            }
        });


        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.payments.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'payment' =>
                        $payment,
                ]
            )
            ->with(
                'success',
                'Payment processed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        $this->validatePayment(
            $contract,
            $payment
        );


        $validated = $request->validate([

            'rejection_remarks' =>
                'required|string|max:5000',

        ]);


        if (
            !in_array(
                $payment->status,
                [
                    'Draft',
                    'Submitted',
                    'Approved',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This payment cannot be rejected.'
                );
        }


        $payment->update([

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


        return back()
            ->with(
                'success',
                'Payment rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE CONTRACT
    |--------------------------------------------------------------------------
    */

    protected function validateContract(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): void {

        if (
            (int) $contract->procurement_tender_id
            !==
            (int) $procurementTender->id
        ) {

            abort(
                404,
                'Contract does not belong to this tender.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PAYMENT
    |--------------------------------------------------------------------------
    */

    protected function validatePayment(
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Payment → Contract
        |--------------------------------------------------------------------------
        */

        if (
            (int) $payment->procurement_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Payment does not belong to this contract.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment → Invoice
        |--------------------------------------------------------------------------
        */

        if (
            !$payment->procurement_contract_invoice_id
        ) {

            abort(
                422,
                'Payment must be linked to an invoice.'
            );
        }


        $invoice =
            ProcurementContractInvoice::query()
                ->select([
                    'id',
                    'procurement_contract_id',
                ])
                ->findOrFail(
                    $payment->procurement_contract_invoice_id
                );


        /*
        |--------------------------------------------------------------------------
        | Invoice → Contract
        |--------------------------------------------------------------------------
        */

        if (
            (int) $invoice->procurement_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Payment invoice does not belong to this contract.'
            );
        }
    }
}