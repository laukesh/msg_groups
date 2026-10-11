<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverFinalAccount;
use App\Models\HandoverFinalPayment;
use App\Models\HandoverProject;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HandoverFinalPaymentController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Final Payment Dashboard
     * -------------------------------------------------------------------------
     *
     * One row is shown for every Final Account / Procurement Contract.
     */
    public function index(Project $project): View
    {
        $handover = $this->getHandover($project);

        /*
         * Load ALL Final Accounts for this Handover.
         *
         * Important:
         * One Final Account = One Procurement Contract.
         */
        $finalAccounts = HandoverFinalAccount::query()
            ->where('handover_project_id', $handover->id)
            ->with([
                'procurementContract.bidder',
                'payment',
            ])
            ->orderBy('procurement_contract_id')
            ->orderBy('id')
            ->get();

       // echo "<pre>";print_r($finalAccounts);die();


        /*
         * ---------------------------------------------------------------------
         * KPI calculations
         * ---------------------------------------------------------------------
         */

        $totalFinalAccounts = $finalAccounts->count();

        $approvedFinalAccounts = $finalAccounts
            ->where('status', 'Approved')
            ->count();

        $pendingFinalAccounts = $finalAccounts
            ->whereIn('status', [
                'Draft',
                'Prepared',
                'Submitted',
                'Under Review',
                'Rejected',
            ])
            ->count();


        /*
         * Total amount approved from Final Accounts.
         */
        $totalApprovedAmount = $finalAccounts->sum(
            function ($account) {

                if ($account->status !== 'Approved') {
                    return 0;
                }

                return max(
                    0,
                    (float) $account->balance_payable
                );
            }
        );


        /*
         * Total amount already paid through Final Payments.
         */
        $totalPaymentAmount = $finalAccounts->sum(
            function ($account) {

                if (!$account->payment) {
                    return 0;
                }

                return max(
                    0,
                    (float) $account->payment->amount_paid
                );
            }
        );


        /*
         * Total outstanding amount.
         */
        $totalBalanceAmount = $finalAccounts->sum(
            function ($account) {

                /*
                 * If payment does not exist, the entire
                 * approved Final Account balance is pending.
                 */
                if (!$account->payment) {
                    return max(
                        0,
                        (float) $account->balance_payable
                    );
                }

                return max(
                    0,
                    (float) $account->payment->balance_amount
                );
            }
        );


        /*
         * Payment statistics.
         */
        $paymentsCreated = $finalAccounts
            ->filter(
                fn ($account) => $account->payment
            )
            ->count();


        $paymentsPaid = $finalAccounts
            ->filter(function ($account) {

                return $account->payment
                    && $account->payment->status === 'Paid'
                    && (float) $account->payment->balance_amount <= 0;
            })
            ->count();


        $paymentsPending = $finalAccounts
            ->filter(function ($account) {

                return $account->payment
                    && in_array(
                        $account->payment->status,
                        [
                            'Pending',
                            'Prepared',
                            'Submitted',
                            'Under Review',
                            'Approved',
                        ],
                        true
                    );
            })
            ->count();


        $paymentsRejected = $finalAccounts
            ->filter(function ($account) {

                return $account->payment
                    && $account->payment->status === 'Rejected';
            })
            ->count();


        /*
         * Final Payment completion.
         *
         * Every Final Account must have a fully paid payment.
         */
        $financialCloseoutComplete =
            $totalFinalAccounts > 0
            && $finalAccounts->every(function ($account) {

                $payment = $account->payment;

                return $account->status === 'Approved'
                    && $payment
                    && $payment->status === 'Paid'
                    && (float) $payment->balance_amount <= 0;
            });


        return view(
            'admin.handover.final-payment.index',
            compact(
                'project',
                'handover',
                'finalAccounts',
                'totalFinalAccounts',
                'approvedFinalAccounts',
                'pendingFinalAccounts',
                'totalApprovedAmount',
                'totalPaymentAmount',
                'totalBalanceAmount',
                'paymentsCreated',
                'paymentsPaid',
                'paymentsPending',
                'paymentsRejected',
                'financialCloseoutComplete'
            )
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Create Final Payment
     * -------------------------------------------------------------------------
     *
     * Payment is created for a specific Final Account.
     */
    public function store(
        Project $project,
        Request $request,
        HandoverFinalAccount $finalAccount
    ): RedirectResponse {

        $handover = $this->getHandover($project);


        /*
         * Ensure Final Account belongs to current project.
         */
        if (
            (int) $finalAccount->project_id !== (int) $project->id
            ||
            (int) $finalAccount->handover_project_id !== (int) $handover->id
        ) {
            abort(404);
        }


        /*
         * Final Account must be approved.
         */
        if ($finalAccount->status !== 'Approved') {

            return back()->with(
                'error',
                'Final Payment can only be created after this Final Account is Approved.'
            );
        }


        /*
         * One Final Payment per Final Account.
         */
        $existingPayment = HandoverFinalPayment::query()
            ->where(
                'final_account_id',
                $finalAccount->id
            )
            ->first();

        if ($existingPayment) {

            return redirect()
                ->route(
                    'admin.projects.handover.final-payment.show',
                    [
                        $project,
                        $existingPayment,
                    ]
                )
                ->with(
                    'error',
                    'Final Payment already exists for this Final Account.'
                );
        }


        $approvedAmount = max(
            0,
            (float) $finalAccount->balance_payable
        );


        $paymentNo = $this->generatePaymentNo($project);


        $payment = HandoverFinalPayment::create([
            'project_id' => $project->id,
            'handover_project_id' => $handover->id,
            'final_account_id' => $finalAccount->id,

            'payment_no' => $paymentNo,

            'approved_amount' => $approvedAmount,
            'amount_paid' => 0,
            'balance_amount' => $approvedAmount,

            'status' => 'Pending',

            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.handover.final-payment.show',
                [
                    $project,
                    $payment,
                ]
            )
            ->with(
                'success',
                "Final Payment {$payment->payment_no} created successfully."
            );
    }


    /**
     * -------------------------------------------------------------------------
     * Show Payment
     * -------------------------------------------------------------------------
     */
    public function show(
        Project $project,
        HandoverFinalPayment $payment
    ): View {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        $handover = $this->getHandover($project);


        if (
            (int) $payment->handover_project_id !==
            (int) $handover->id
        ) {
            abort(404);
        }


        $payment->load([
            'finalAccount.procurementContract.bidder',
            'preparedBy',
            'submittedBy',
            'reviewedBy',
            'approvedBy',
            'paidBy',
        ]);


        $finalAccount = $payment->finalAccount;


        if (!$finalAccount) {
            abort(
                404,
                'Final Account associated with this payment was not found.'
            );
        }


        $approvedAmount = max(
            0,
            (float) $finalAccount->balance_payable
        );


        /*
         * Keep the payment amount synchronized
         * with the currently approved Final Account.
         */
        if (
            $finalAccount->status === 'Approved'
            &&
            $payment->status !== 'Paid'
        ) {

            $amountPaid = max(
                0,
                (float) $payment->amount_paid
            );

            $balanceAmount = max(
                0,
                $approvedAmount - $amountPaid
            );


            if (
                (float) $payment->approved_amount !==
                    $approvedAmount
                ||
                (float) $payment->balance_amount !==
                    $balanceAmount
            ) {

                $payment->update([
                    'approved_amount' => $approvedAmount,
                    'balance_amount' => $balanceAmount,
                    'updated_by' => auth()->id(),
                ]);

                $payment->refresh();
            }
        }


        $amountPaid = max(
            0,
            (float) $payment->amount_paid
        );


        $balanceAmount = max(
            0,
            (float) $payment->balance_amount
        );


        $paymentComplete =
            $payment->status === 'Paid'
            && $balanceAmount <= 0;


        return view(
            'admin.handover.final-payment.show',
            compact(
                'project',
                'handover',
                'finalAccount',
                'payment',
                'approvedAmount',
                'amountPaid',
                'balanceAmount',
                'paymentComplete'
            )
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Update Payment Preparation
     * -------------------------------------------------------------------------
     */
    public function update(
        Project $project,
        Request $request,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if (!in_array(
            $payment->status,
            [
                'Pending',
                'Prepared',
                'Rejected',
            ],
            true
        )) {

            return back()->with(
                'error',
                'Payment can only be edited in Pending, Prepared or Rejected status.'
            );
        }


        $handover = $this->getHandover($project);


        if (
            (int) $payment->handover_project_id !==
            (int) $handover->id
        ) {
            abort(404);
        }


        /*
         * IMPORTANT:
         * Never search Final Account using handover_project_id.
         *
         * The payment already knows exactly which Final Account
         * it belongs to.
         */
        $finalAccount = $payment->finalAccount;


        if (
            !$finalAccount
            ||
            $finalAccount->status !== 'Approved'
        ) {

            return back()->with(
                'error',
                'Approved Final Account is required before updating Final Payment.'
            );
        }


        $approvedAmount = max(
            0,
            (float) $finalAccount->balance_payable
        );


        $validated = $request->validate([
            'amount_paid' => [
                'required',
                'numeric',
                'min:0',
                'lte:' . $approvedAmount,
            ],

            'payment_date' => [
                'nullable',
                'date',
            ],

            'payment_method' => [
                'nullable',
                'in:Bank Transfer,Cheque,Online,Other',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_remarks' => [
                'nullable',
                'string',
            ],

            'finance_remarks' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'payment_document' => [
                'nullable',
                'file',
                'max:51200',
            ],
        ]);


        $amountPaid = (float) $validated['amount_paid'];


        $balanceAmount = max(
            0,
            $approvedAmount - $amountPaid
        );


        $data = [
            'approved_amount' => $approvedAmount,
            'amount_paid' => $amountPaid,
            'balance_amount' => $balanceAmount,

            'payment_date' =>
                $validated['payment_date'] ?? null,

            'payment_method' =>
                $validated['payment_method'] ?? null,

            'transaction_reference' =>
                $validated['transaction_reference'] ?? null,

            'payment_remarks' =>
                $validated['payment_remarks'] ?? null,

            'finance_remarks' =>
                $validated['finance_remarks'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' => auth()->id(),
        ];


        /*
         * Rejected → Prepared.
         */
        if ($payment->status === 'Rejected') {

            $data['status'] = 'Prepared';

            $data['rejection_reason'] = null;

            $data['prepared_by'] = auth()->id();

            $data['prepared_at'] = now();
        }


        /*
         * Pending → Prepared.
         */
        elseif ($payment->status === 'Pending') {

            $data['status'] = 'Prepared';

            $data['prepared_by'] = auth()->id();

            $data['prepared_at'] = now();
        }


        /*
         * Upload payment document.
         */
        if ($request->hasFile('payment_document')) {

            if (
                $payment->payment_document_path
                &&
                Storage::disk('public')->exists(
                    $payment->payment_document_path
                )
            ) {

                Storage::disk('public')->delete(
                    $payment->payment_document_path
                );
            }


            $path = $request
                ->file('payment_document')
                ->store(
                    'handover-final-payments/' . $project->id,
                    'public'
                );


            $data['payment_document_path'] = $path;
        }


        $payment->update($data);


        return back()->with(
            'success',
            'Final Payment details updated successfully.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Submit
     * -------------------------------------------------------------------------
     */
    public function submit(
        Project $project,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if (!in_array(
            $payment->status,
            [
                'Prepared',
                'Rejected',
            ],
            true
        )) {

            return back()->with(
                'error',
                'Only Prepared or Rejected payments can be submitted.'
            );
        }


        $finalAccount = $payment->finalAccount;


        if (
            !$finalAccount
            ||
            $finalAccount->status !== 'Approved'
        ) {

            return back()->with(
                'error',
                'Approved Final Account is required.'
            );
        }


        if ((float) $payment->approved_amount < 0) {

            return back()->with(
                'error',
                'Invalid approved payment amount.'
            );
        }


        $payment->update([
            'status' => 'Submitted',

            'submitted_by' => auth()->id(),
            'submitted_at' => now(),

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Final Payment submitted successfully.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Review
     * -------------------------------------------------------------------------
     */
    public function review(
        Project $project,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if ($payment->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted payments can be moved to review.'
            );
        }


        $payment->update([
            'status' => 'Under Review',

            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Final Payment moved to Under Review.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Approve
     * -------------------------------------------------------------------------
     */
    public function approve(
        Project $project,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if ($payment->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only payments Under Review can be approved.'
            );
        }


        $handover = $this->getHandover($project);


        if (
            (int) $payment->handover_project_id !==
            (int) $handover->id
        ) {
            abort(404);
        }


        /*
         * IMPORTANT:
         * Get the Final Account from THIS payment.
         */
        $finalAccount = $payment->finalAccount;


        if (
            !$finalAccount
            ||
            (int) $finalAccount->handover_project_id !==
                (int) $handover->id
            ||
            $finalAccount->status !== 'Approved'
        ) {

            return back()->with(
                'error',
                'Approved Final Account is required.'
            );
        }


        $approvedAmount = max(
            0,
            (float) $finalAccount->balance_payable
        );


        $amountPaid = max(
            0,
            (float) $payment->amount_paid
        );


        if ($amountPaid > $approvedAmount) {

            return back()->with(
                'error',
                'Paid amount cannot exceed the approved Final Account balance.'
            );
        }


        $balanceAmount = max(
            0,
            $approvedAmount - $amountPaid
        );


        /*
         * Approval only authorizes payment.
         * Actual settlement is done by Paid action.
         */
        $payment->update([
            'approved_amount' => $approvedAmount,

            'amount_paid' => $amountPaid,

            'balance_amount' => $balanceAmount,

            'status' => 'Approved',

            'approved_by' => auth()->id(),
            'approved_at' => now(),

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Final Payment approved successfully.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Mark Paid
     * -------------------------------------------------------------------------
     */
    public function paid(
        Project $project,
        Request $request,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if ($payment->status !== 'Approved') {

            return back()->with(
                'error',
                'Only Approved payments can be marked as Paid.'
            );
        }


        $finalAccount = $payment->finalAccount;


        if (
            !$finalAccount
            ||
            $finalAccount->status !== 'Approved'
        ) {

            return back()->with(
                'error',
                'Approved Final Account is required.'
            );
        }


        $validated = $request->validate([
            'amount_paid' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'in:Bank Transfer,Cheque,Online,Other',
            ],

            'transaction_reference' => [
                'required',
                'string',
                'max:255',
            ],

            'payment_remarks' => [
                'nullable',
                'string',
            ],

            'finance_remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $approvedAmount =
            (float) $payment->approved_amount;


        $amountPaid =
            (float) $validated['amount_paid'];


        /*
         * Final Payment must settle the full
         * approved amount.
         */
        if ($amountPaid !== $approvedAmount) {

            return back()->with(
                'error',
                'For Final Payment, the paid amount must equal the approved amount.'
            );
        }


        DB::transaction(
            function () use (
                $payment,
                $validated,
                $amountPaid
            ) {

                $payment->update([

                    'amount_paid' =>
                        $amountPaid,

                    'balance_amount' =>
                        0,

                    'payment_date' =>
                        $validated['payment_date'],

                    'payment_method' =>
                        $validated['payment_method'],

                    'transaction_reference' =>
                        $validated['transaction_reference'],

                    'payment_remarks' =>
                        $validated['payment_remarks'] ?? null,

                    'finance_remarks' =>
                        $validated['finance_remarks'] ?? null,

                    'status' =>
                        'Paid',

                    'paid_by' =>
                        auth()->id(),

                    'paid_at' =>
                        now(),

                    'updated_by' =>
                        auth()->id(),
                ]);
            }
        );


        return back()->with(
            'success',
            'Final Payment marked as Paid successfully.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Reject
     * -------------------------------------------------------------------------
     */
    public function reject(
        Project $project,
        Request $request,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if ($payment->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only payments Under Review can be rejected.'
            );
        }


        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);


        $payment->update([
            'status' => 'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Final Payment rejected. It can be rectified and prepared again.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Cancel
     * -------------------------------------------------------------------------
     */
    public function cancel(
        Project $project,
        HandoverFinalPayment $payment
    ): RedirectResponse {

        $this->validatePaymentProject(
            $project,
            $payment
        );


        if (!in_array(
            $payment->status,
            [
                'Pending',
                'Prepared',
                'Rejected',
            ],
            true
        )) {

            return back()->with(
                'error',
                'Only Pending, Prepared or Rejected payments can be cancelled.'
            );
        }


        $payment->update([
            'status' => 'Cancelled',

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Final Payment cancelled successfully.'
        );
    }


    /**
     * -------------------------------------------------------------------------
     * Get latest handover
     * -------------------------------------------------------------------------
     */
    protected function getHandover(
        Project $project
    ): HandoverProject {

        $handover = HandoverProject::query()
            ->where(
                'project_id',
                $project->id
            )
            ->latest('id')
            ->first();


        if (!$handover) {

            abort(
                404,
                'Handover process has not been started for this project.'
            );
        }


        return $handover;
    }


    /**
     * -------------------------------------------------------------------------
     * Validate payment belongs to current project
     * -------------------------------------------------------------------------
     */
    protected function validatePaymentProject(
        Project $project,
        HandoverFinalPayment $payment
    ): void {

        if (
            (int) $payment->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }


    /**
     * -------------------------------------------------------------------------
     * Generate unique payment number
     * -------------------------------------------------------------------------
     */
    protected function generatePaymentNo(
        Project $project
    ): string {

        $count = HandoverFinalPayment::query()
            ->where(
                'project_id',
                $project->id
            )
            ->count() + 1;


        do {

            $paymentNo =
                'FP-' .
                str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    (string) $count,
                    4,
                    '0',
                    STR_PAD_LEFT
                );


            $exists = HandoverFinalPayment::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'payment_no',
                    $paymentNo
                )
                ->exists();


            if ($exists) {
                $count++;
            }

        } while ($exists);


        return $paymentNo;
    }
}