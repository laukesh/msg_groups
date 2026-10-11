<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementTender;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractPayment;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProcurementContractPaymentController extends Controller
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
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $payments = $contract->payments()
            ->where('project_id', $project->id)
            ->with([
                'invoice',
                'milestone',
            ])
            ->latest('id')
            ->paginate(20);

        return view(
            'procurement.contract-payments.index',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $selectedInvoice = null;

        if ($request->filled('invoice_id')) {
            $selectedInvoice = ProcurementContractInvoice::where(
                'id',
                $request->invoice_id
            )
                ->where('project_id', $project->id)
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->firstOrFail();
        }

        /*
         * Only invoices which are payable.
         */
        $invoices = ProcurementContractInvoice::where(
            'project_id',
            $project->id
        )
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Partially Paid',
                ]
            )
            ->with([
                'payments' => function ($query) {
                    $query->where('status', 'Processed');
                }
            ])
            ->orderByDesc('id')
            ->get();

        /*
         * Calculate outstanding amount for each invoice.
         */
        $invoices->each(function ($invoice) {
            $paidAmount = $invoice->payments->sum(
                fn ($payment) => (float) $payment->amount
            );

            $invoice->calculated_paid_amount = $paidAmount;

            $invoice->calculated_balance = max(
                0,
                (float) $invoice->net_amount - $paidAmount
            );
        });

        /*
         * Remove fully paid invoices.
         */
        $invoices = $invoices->filter(
            fn ($invoice) =>
                $invoice->calculated_balance > 0
        );

        /*
         * Milestones belonging to this contract.
         */
        $milestones = ProcurementContractMilestone::where(
            'procurement_contract_id',
            $contract->id
        )
            ->orderBy('milestone_number')
            ->orderBy('id')
            ->get();

        return view(
            'procurement.contract-payments.create',
            compact(
                'project',
                'procurementTender',
                'contract',
                'selectedInvoice',
                'invoices',
                'milestones'
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
        ProcurementContract $contract
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $validated = $request->validate([
            'procurement_contract_invoice_id' => [
                'required',
                'integer',
            ],

            'procurement_contract_milestone_id' => [
                'nullable',
                'integer',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_type' => [
                'required',
                'string',
                'max:100',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'currency' => [
                'nullable',
                'string',
                'max:10',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'account_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {

            /*
             * Lock invoice while calculating balance.
             */
            $invoice = ProcurementContractInvoice::where(
                'id',
                $validated['procurement_contract_invoice_id']
            )
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                throw ValidationException::withMessages([
                    'procurement_contract_invoice_id' =>
                        'Selected invoice does not belong to this contract/project.',
                ]);
            }

            if (!in_array(
                $invoice->status,
                [
                    'Approved',
                    'Partially Paid',
                ],
                true
            )) {
                throw ValidationException::withMessages([
                    'procurement_contract_invoice_id' =>
                        'Selected invoice is not available for payment.',
                ]);
            }

            /*
             * Calculate already processed amount.
             */
            $paidAmount = ProcurementContractPayment::where(
                'project_id',
                $project->id
            )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->where(
                    'procurement_contract_invoice_id',
                    $invoice->id
                )
                ->where(
                    'status',
                    'Processed'
                )
                ->sum('amount');

            $invoiceAmount = (float) $invoice->net_amount;

            $balance = max(
                0,
                $invoiceAmount - (float) $paidAmount
            );

            $paymentAmount = (float) $validated['amount'];

            if ($paymentAmount > $balance) {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payment amount cannot exceed the outstanding invoice balance of '
                        . number_format($balance, 2),
                ]);
            }

            /*
             * Validate milestone if selected.
             */
            if (!empty(
                $validated['procurement_contract_milestone_id']
            )) {

                $milestoneExists =
                    ProcurementContractMilestone::where(
                        'id',
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
                    throw ValidationException::withMessages([
                        'procurement_contract_milestone_id' =>
                            'Selected milestone does not belong to this contract.',
                    ]);
                }
            }

            /*
             * Create temporary payment.
             */
            $payment = ProcurementContractPayment::create([
                'project_id' =>
                    $project->id,

                'procurement_contract_id' =>
                    $contract->id,

                'procurement_contract_invoice_id' =>
                    $invoice->id,

                'procurement_contract_milestone_id' =>
                    $validated[
                        'procurement_contract_milestone_id'
                    ] ?? null,

                'payment_number' =>
                    'TEMP-' . uniqid(),

                'payment_date' =>
                    $validated['payment_date'],

                'payment_type' =>
                    $validated['payment_type'],

                'amount' =>
                    $paymentAmount,

                'currency' =>
                    $validated['currency'] ?? 'INR',

                'payment_method' =>
                    $validated['payment_method'] ?? null,

                'transaction_reference' =>
                    $validated['transaction_reference'] ?? null,

                'bank_name' =>
                    $validated['bank_name'] ?? null,

                'account_reference' =>
                    $validated['account_reference'] ?? null,

                'description' =>
                    $validated['description'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'status' =>
                    'Draft',

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);

            /*
             * Generate final payment number.
             */
            $payment->payment_number =
                'PAY-' .
                now()->format('Y') .
                '-' .
                str_pad(
                    (string) $payment->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );

            $payment->save();

            DB::commit();

            return redirect()->route(
                'admin.projects.procurement.tenders.contracts.payments.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'payment' =>
                        $payment,
                ]
            )->with(
                'success',
                'Payment created successfully.'
            );

        } catch (
            ValidationException $e
        ) {

            DB::rollBack();

            throw $e;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Procurement contract payment creation failed.',
                [
                    'project_id' =>
                        $project->id,

                    'contract_id' =>
                        $contract->id,

                    'user_id' =>
                        Auth::id(),

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create payment. Please try again.'
                );
        }
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
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        $payment->load([
            'project',
            'contract',
            'invoice',
            'milestone',
            'submittedBy',
            'approvedBy',
            'rejectedBy',
            'processedBy',
            'creator',
            'updater',
        ]);

        return view(
            'procurement.contract-payments.show',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        if (!$payment->canBeSubmitted()) {
            return back()->with(
                'error',
                'This payment cannot be submitted in its current status.'
            );
        }

        $payment->status = 'Submitted';
        $payment->submitted_at = now();
        $payment->submitted_by = Auth::id();
        $payment->updated_by = Auth::id();
        $payment->save();

        return redirect()->route(
            'admin.projects.procurement.tenders.contracts.payments.show',
            [
                'project' =>
                    $project,

                'procurementTender' =>
                    $procurementTender,

                'contract' =>
                    $contract,

                'payment' =>
                    $payment,
            ]
        )->with(
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        if (!$payment->canBeApproved()) {
            return back()->with(
                'error',
                'This payment cannot be approved in its current status.'
            );
        }

        DB::beginTransaction();

        try {

            $payment = ProcurementContractPayment::where(
                'id',
                $payment->id
            )
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status !== 'Submitted') {
                throw new \RuntimeException(
                    'Payment is no longer available for approval.'
                );
            }

            $payment->status = 'Approved';
            $payment->approved_at = now();
            $payment->approved_by = Auth::id();
            $payment->updated_by = Auth::id();
            $payment->save();

            DB::commit();

            return redirect()->route(
                'admin.projects.procurement.tenders.contracts.payments.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'payment' =>
                        $payment,
                ]
            )->with(
                'success',
                'Payment approved successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Procurement contract payment approval failed.',
                [
                    'payment_id' =>
                        $payment->id,

                    'project_id' =>
                        $project->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'Unable to approve payment.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT FORM
    |--------------------------------------------------------------------------
    */

    public function rejectForm(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        if (!$payment->canBeRejected()) {
            return redirect()->route(
                'admin.projects.procurement.tenders.contracts.payments.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'payment' =>
                        $payment,
                ]
            )->with(
                'error',
                'This payment cannot be rejected in its current status.'
            );
        }

        return view(
            'procurement.contract-payments.reject',
            compact(
                'project',
                'procurementTender',
                'contract',
                'payment'
            )
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
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        $validated = $request->validate([
            'rejection_remarks' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ]);

        if (!$payment->canBeRejected()) {
            return back()->with(
                'error',
                'This payment cannot be rejected in its current status.'
            );
        }

        $payment->status = 'Rejected';
        $payment->rejected_at = now();
        $payment->rejected_by = Auth::id();
        $payment->rejection_remarks =
            $validated['rejection_remarks'];
        $payment->updated_by = Auth::id();
        $payment->save();

        return redirect()->route(
            'admin.projects.procurement.tenders.contracts.payments.show',
            [
                'project' =>
                    $project,

                'procurementTender' =>
                    $procurementTender,

                'contract' =>
                    $contract,

                'payment' =>
                    $payment,
            ]
        )->with(
            'success',
            'Payment rejected successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS
    |--------------------------------------------------------------------------
    */

    public function process(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ) {
        $this->validateContract(
            $project,
            $procurementTender,
            $contract
        );

        $this->validatePayment(
            $project,
            $contract,
            $payment
        );

        if (!$payment->canBeProcessed()) {
            return back()->with(
                'error',
                'This payment cannot be processed in its current status.'
            );
        }

        DB::beginTransaction();

        try {

            /*
             * Lock payment.
             */
            $lockedPayment =
                ProcurementContractPayment::where(
                    'id',
                    $payment->id
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPayment->status !== 'Approved') {
                throw new \RuntimeException(
                    'Payment is no longer available for processing.'
                );
            }

            /*
             * Lock invoice.
             */
            $invoice =
                ProcurementContractInvoice::where(
                    'id',
                    $lockedPayment
                        ->procurement_contract_invoice_id
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                throw new \RuntimeException(
                    'Payment invoice could not be found.'
                );
            }

            if (!in_array(
                $invoice->status,
                [
                    'Approved',
                    'Partially Paid',
                ],
                true
            )) {
                throw new \RuntimeException(
                    'Invoice is not available for payment processing.'
                );
            }

            /*
             * Existing processed payments excluding current payment.
             */
            $processedAmount =
                ProcurementContractPayment::where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
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
                    $lockedPayment->id
                )
                ->sum('amount');

            $invoiceAmount =
                (float) $invoice->net_amount;

            $paymentAmount =
                (float) $lockedPayment->amount;

            $remaining =
                $invoiceAmount -
                (float) $processedAmount;

            if ($paymentAmount > $remaining) {
                throw new \RuntimeException(
                    'Payment exceeds the remaining invoice balance.'
                );
            }

            /*
             * Process payment.
             */
            $lockedPayment->status = 'Processed';
            $lockedPayment->processed_at = now();
            $lockedPayment->processed_by = Auth::id();
            $lockedPayment->updated_by = Auth::id();
            $lockedPayment->save();

            /*
             * Calculate new paid amount.
             */
            $totalProcessed =
                (float) $processedAmount +
                $paymentAmount;

            if ($totalProcessed >= $invoiceAmount) {

                $invoice->status = 'Paid';

            } else {

                $invoice->status = 'Partially Paid';
            }

            $invoice->save();

            DB::commit();

            return redirect()->route(
                'admin.projects.procurement.tenders.contracts.payments.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'payment' =>
                        $lockedPayment,
                ]
            )->with(
                'success',
                'Payment processed successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Procurement contract payment processing failed.',
                [
                    'payment_id' =>
                        $payment->id,

                    'project_id' =>
                        $project->id,

                    'contract_id' =>
                        $contract->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE CONTRACT
    |--------------------------------------------------------------------------
    */

    private function validateContract(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): void {

        /*
         * Tender must belong to project.
         */
        if (
            (int) $procurementTender->project_id !==
            (int) $project->id
        ) {
            abort(
                404,
                'Procurement tender does not belong to this project.'
            );
        }

        /*
         * Contract must belong to project.
         */
        if (
            (int) $contract->project_id !==
            (int) $project->id
        ) {
            abort(
                404,
                'Contract does not belong to this project.'
            );
        }

        /*
         * Contract must belong to tender.
         */
        if (
            (int) $contract->procurement_tender_id !==
            (int) $procurementTender->id
        ) {
            abort(
                404,
                'Contract does not belong to this procurement tender.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE PAYMENT
    |--------------------------------------------------------------------------
    */

    private function validatePayment(
        Project $project,
        ProcurementContract $contract,
        ProcurementContractPayment $payment
    ): void {

        /*
         * Payment must belong to project.
         */
        if (
            (int) $payment->project_id !==
            (int) $project->id
        ) {
            abort(
                404,
                'Payment does not belong to this project.'
            );
        }

        /*
         * Payment must belong to contract.
         */
        if (
            (int) $payment->procurement_contract_id !==
            (int) $contract->id
        ) {
            abort(
                404,
                'Payment does not belong to this contract.'
            );
        }

        /*
         * Payment must have invoice.
         */
        if (!$payment->procurement_contract_invoice_id) {
            abort(
                404,
                'Payment invoice not found.'
            );
        }

        /*
         * Invoice must belong to same project and contract.
         */
        $invoice =
            ProcurementContractInvoice::where(
                'id',
                $payment->procurement_contract_invoice_id
            )
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->exists();

        if (!$invoice) {
            abort(
                404,
                'Payment invoice does not belong to this project or contract.'
            );
        }
    }
}