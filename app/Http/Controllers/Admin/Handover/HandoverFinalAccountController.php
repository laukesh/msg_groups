<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\ConstructionClaim;
use App\Models\ConstructionVariation;
use App\Models\HandoverFinalAccount;
use App\Models\HandoverProject;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractPayment;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverFinalAccountController extends Controller
{
    /**
     * Final Account Register
     */
    public function index(Project $project): View
    {
        $handover = $this->getHandover($project);

        $accounts = $handover->finalAccounts()
            ->with([
                'procurementContract.bidder',
            ])
            ->latest('id')
            ->get();

        foreach ($accounts as $account) {
            $this->syncFinancialValues($account);
        }

        $accounts = $handover->finalAccounts()
            ->with([
                'procurementContract.bidder',
            ])
            ->latest('id')
            ->get();

        $availableContracts = $this->projectContracts($project)
            ->whereDoesntHave('finalAccounts', function ($q) use ($handover) {
                $q->where(
                    'handover_project_id',
                    $handover->id
                );
            })
            ->with('bidder')
            ->get();

        return view(
            'admin.handover.final-account.index',
            [
                'project' => $project,
                'handover' => $handover,
                'accounts' => $accounts,
                'availableContracts' => $availableContracts,

                'totalAccounts' => $accounts->count(),

                'draftAccounts' => $accounts
                    ->where('status', 'Draft')
                    ->count(),

                'preparedAccounts' => $accounts
                    ->where('status', 'Prepared')
                    ->count(),

                'submittedAccounts' => $accounts
                    ->where('status', 'Submitted')
                    ->count(),

                'underReviewAccounts' => $accounts
                    ->where('status', 'Under Review')
                    ->count(),

                'approvedAccounts' => $accounts
                    ->where('status', 'Approved')
                    ->count(),

                'rejectedAccounts' => $accounts
                    ->where('status', 'Rejected')
                    ->count(),

                'totalContractValue' =>
                    $accounts->sum('contract_value'),

                'totalVariations' =>
                    $accounts->sum('approved_variations'),

                'totalClaims' =>
                    $accounts->sum('approved_claims'),

                'totalGrossFinalAmount' =>
                    $accounts->sum('gross_final_amount'),

                'totalCertifiedAmount' =>
                    $accounts->sum('certified_amount'),

                'totalAmountPaid' =>
                    $accounts->sum('amount_paid'),

                'totalBalancePayable' =>
                    $accounts->sum('balance_payable'),
            ]
        );
    }


    /**
     * Create page
     */
    public function create(Project $project): View
    {
        $handover = $this->getHandover($project);

        $contracts = $this->projectContracts($project)
            ->with('bidder')
            ->whereDoesntHave('finalAccounts', function ($q) use ($handover) {
                $q->where('handover_project_id', $handover->id);
            })
            ->get();

        return view(
            'admin.handover.final-account.create',
            compact(
                'project',
                'handover',
                'contracts'
            )
        );
    }


    /**
     * Store
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $handover = $this->getHandover($project);

        /*
         * Get all project procurement contracts
         * that do not yet have a Final Account
         * for this handover.
         */
        $contracts = $this->projectContracts($project)
            ->whereDoesntHave('finalAccounts', function ($q) use ($handover) {
                $q->where(
                    'handover_project_id',
                    $handover->id
                );
            })
            ->get();

        if ($contracts->isEmpty()) {
            return redirect()
                ->route(
                    'admin.projects.handover.final-account.index',
                    $project
                )
                ->with(
                    'info',
                    'All procurement contracts already have Final Accounts.'
                );
        }

        $createdCount = 0;

        DB::transaction(function () use (
            $project,
            $handover,
            $contracts,
            &$createdCount
        ) {

            foreach ($contracts as $contract) {

                /*
                 * Double-check inside transaction.
                 */
                $exists = HandoverFinalAccount::query()
                    ->where(
                        'handover_project_id',
                        $handover->id
                    )
                    ->where(
                        'procurement_contract_id',
                        $contract->id
                    )
                    ->exists();

                if ($exists) {
                    continue;
                }

                $account = HandoverFinalAccount::create([
                    'project_id' => $project->id,

                    'handover_project_id' => $handover->id,

                    'final_account_no' =>
                        $this->generateFinalAccountNo($project),

                    'procurement_contract_id' =>
                        $contract->id,

                    'status' => 'Draft',

                    'created_by' => auth()->id(),

                    'updated_by' => auth()->id(),
                ]);

                /*
                 * Automatically calculate:
                 *
                 * Contract Value
                 * Approved Variations
                 * Approved Claims
                 * Payments
                 * Gross Final Amount
                 * Balance Payable
                 */
                $this->syncFinancialValues($account);

                $createdCount++;
            }
        });

        return redirect()
            ->route(
                'admin.projects.handover.final-account.index',
                $project
            )
            ->with(
                'success',
                $createdCount . ' Final Account(s) created successfully.'
            );
    }


    /**
     * Show
     */
    public function show(
        Project $project,
        HandoverFinalAccount $account
    ): View {

        $this->validateAccountProject(
            $project,
            $account
        );

        $this->syncFinancialValues(
            $account
        );

        $account->refresh();

        $account->load([
            'procurementContract.bidder',
            'handoverProject',
        ]);

        $contract = $account->procurementContract;

        /*
         * Approved Variations
         */
        $variations = ConstructionVariation::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'procurement_contract_id',
                $account->procurement_contract_id
            )
            ->where(
                'status',
                'Approved'
            )
            ->latest('variation_date')
            ->get();

        /*
         * Approved Claims
         */
        $claims = ConstructionClaim::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'procurement_contract_id',
                $account->procurement_contract_id
            )
            ->where(
                'status',
                'Approved'
            )
            ->latest('claim_date')
            ->get();

        /*
         * IMPORTANT:
         *
         * Load ALL payments for this contract.
         *
         * Do NOT filter by currency here.
         */
        $payments = ProcurementContractPayment::query()
            ->where(
                'procurement_contract_id',
                $account->procurement_contract_id
            )
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        /*
         * Payment currency summary
         */
        $paymentSummary = $this->getPaymentCurrencySummary(
            $account->procurement_contract_id
        );

        /*
         * Check whether any payment currency
         * differs from contract currency.
         */
        $currencyMismatch =
            $this->hasPaymentCurrencyMismatch(
                $contract,
                $payments
            );

        return view(
            'admin.handover.final-account.show',
            [
                'project' => $project,

                'handover' =>
                    $account->handoverProject,

                'account' =>
                    $account,

                'contract' =>
                    $contract,

                'variations' =>
                    $variations,

                'claims' =>
                    $claims,

                'payments' =>
                    $payments,

                'currency' =>
                    $this->getContractCurrency($contract)
                    ?? '',

                'paymentSummary' =>
                    $paymentSummary,

                'currencyMismatch' =>
                    $currencyMismatch,
            ]
        );
    }


    /**
     * Edit
     */
    public function edit(
        Project $project,
        HandoverFinalAccount $account
    ): View {

        $this->validateAccountProject(
            $project,
            $account
        );

        if (!in_array(
            $account->status,
            [
                'Draft',
                'Prepared',
                'Rejected',
            ],
            true
        )) {
            abort(
                403,
                'This Final Account cannot be edited in its current status.'
            );
        }

        $this->syncFinancialValues(
            $account
        );

        $account->refresh();

        $account->load([
            'procurementContract.bidder',
        ]);

        $contract =
            $account->procurementContract;

        return view(
            'admin.handover.final-account.edit',
            [
                'project' =>
                    $project,

                'handover' =>
                    $account->handoverProject,

                'account' =>
                    $account,

                'contract' =>
                    $contract,

                'currency' =>
                    $this->getContractCurrency(
                        $contract
                    ) ?? '',
            ]
        );
    }


    /**
     * Update
     */
    public function update(
        Request $request,
        Project $project,
        HandoverFinalAccount $account
    ): RedirectResponse {

        $this->validateAccountProject(
            $project,
            $account
        );

        if (!in_array(
            $account->status,
            [
                'Draft',
                'Prepared',
                'Rejected',
            ],
            true
        )) {
            return back()
                ->with(
                    'error',
                    'This Final Account cannot be edited in its current status.'
                );
        }

        $data = $request->validate([
            'advance_payment' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance_recovery' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'retention_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'deductions' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_adjustments' => [
                'nullable',
                'numeric',
            ],

            'certified_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'contractor_statement' => [
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
        ]);

        $account->fill([
            'advance_payment' =>
                $data['advance_payment'] ?? 0,

            'advance_recovery' =>
                $data['advance_recovery'] ?? 0,

            'retention_amount' =>
                $data['retention_amount'] ?? 0,

            'deductions' =>
                $data['deductions'] ?? 0,

            'other_adjustments' =>
                $data['other_adjustments'] ?? 0,

            'certified_amount' =>
                $data['certified_amount'] ?? 0,

            'contractor_statement' =>
                $data['contractor_statement'] ?? null,

            'finance_remarks' =>
                $data['finance_remarks'] ?? null,

            'remarks' =>
                $data['remarks'] ?? null,

            'status' =>
                'Prepared',

            'updated_by' =>
                auth()->id(),
        ]);

        $account->save();

        $this->syncFinancialValues(
            $account
        );

        return redirect()
            ->route(
                'admin.projects.handover.final-account.show',
                [$project, $account]
            )
            ->with(
                'success',
                'Final Account saved successfully and marked Prepared.'
            );
    }


    /**
     * Submit
     */
    public function submit(
        Project $project,
        HandoverFinalAccount $account
    ): RedirectResponse {

        $this->validateAccountProject(
            $project,
            $account
        );

        if (!in_array(
            $account->status,
            [
                'Prepared',
                'Rejected',
            ],
            true
        )) {
            return back()
                ->with(
                    'error',
                    'Only Prepared or Rejected Final Accounts can be submitted.'
                );
        }

        $this->syncFinancialValues(
            $account
        );

        $account->update([
            'status' =>
                'Submitted',

            'submitted_date' =>
                now()->toDateString(),

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'rejection_reason' =>
                null,

            'updated_by' =>
                auth()->id(),
        ]);

        return back()
            ->with(
                'success',
                'Final Account submitted for review.'
            );
    }


    /**
     * Review
     */
    public function review(
        Project $project,
        HandoverFinalAccount $account
    ): RedirectResponse {

        $this->validateAccountProject(
            $project,
            $account
        );

        if ($account->status !== 'Submitted') {
            return back()
                ->with(
                    'error',
                    'Only Submitted Final Accounts can enter review.'
                );
        }

        $this->syncFinancialValues(
            $account
        );

        $account->update([
            'status' =>
                'Under Review',

            'reviewed_by' =>
                auth()->id(),

            'reviewed_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()
            ->with(
                'success',
                'Final Account moved to Under Review.'
            );
    }


    /**
     * Approve
     */
    public function approve(
        Project $project,
        HandoverFinalAccount $account
    ): RedirectResponse {

        $this->validateAccountProject(
            $project,
            $account
        );

        if ($account->status !== 'Under Review') {
            return back()
                ->with(
                    'error',
                    'Only Final Accounts Under Review can be approved.'
                );
        }

        $this->syncFinancialValues(
            $account
        );

        $account->update([
            'status' =>
                'Approved',

            'approved_date' =>
                now()->toDateString(),

            'approved_by' =>
                auth()->id(),

            'approved_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()
            ->with(
                'success',
                'Final Account approved successfully.'
            );
    }


    /**
     * Reject form
     */
    public function rejectForm(
        Project $project,
        HandoverFinalAccount $account
    ): View {

        $this->validateAccountProject(
            $project,
            $account
        );

        if ($account->status !== 'Under Review') {
            abort(
                403,
                'Only Final Accounts Under Review can be rejected.'
            );
        }

        return view(
            'admin.handover.final-account.reject',
            [
                'project' =>
                    $project,

                'handover' =>
                    $account->handoverProject,

                'account' =>
                    $account,
            ]
        );
    }


    /**
     * Reject
     */
    public function reject(
        Request $request,
        Project $project,
        HandoverFinalAccount $account
    ): RedirectResponse {

        $this->validateAccountProject(
            $project,
            $account
        );

        if ($account->status !== 'Under Review') {
            return back()
                ->with(
                    'error',
                    'Only Final Accounts Under Review can be rejected.'
                );
        }

        $data = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $account->update([
            'status' =>
                'Rejected',

            'rejection_reason' =>
                $data['rejection_reason'],

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.handover.final-account.show',
                [$project, $account]
            )
            ->with(
                'success',
                'Final Account rejected. It can now be corrected and resubmitted.'
            );
    }


    /**
     * Payment History
     */
    public function payments(
        Project $project,
        HandoverFinalAccount $account
    ): View {

        $this->validateAccountProject(
            $project,
            $account
        );

        $this->syncFinancialValues(
            $account
        );

        $account->refresh();

        $contract =
            $account->procurementContract;

        /*
         * IMPORTANT:
         *
         * Show ALL payments for this contract.
         * No currency filter here.
         */
        $payments = ProcurementContractPayment::query()
            ->where(
                'procurement_contract_id',
                $account->procurement_contract_id
            )
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        $paymentSummary =
            $this->getPaymentCurrencySummary(
                $account->procurement_contract_id
            );

        $currencyMismatch =
            $this->hasPaymentCurrencyMismatch(
                $contract,
                $payments
            );

        return view(
            'admin.handover.final-account.payments',
            [
                'project' =>
                    $project,

                'handover' =>
                    $account->handoverProject,

                'account' =>
                    $account,

                'contract' =>
                    $contract,

                'payments' =>
                    $payments,

                'currency' =>
                    $this->getContractCurrency(
                        $contract
                    ) ?? '',

                'paymentSummary' =>
                    $paymentSummary,

                'currencyMismatch' =>
                    $currencyMismatch,
            ]
        );
    }


    /**
     * Synchronize Final Account financial values.
     */
    private function syncFinancialValues(
        HandoverFinalAccount $account
    ): void {

        /*
         * Get contract directly using FK.
         */
        $contract = ProcurementContract::query()
            ->find(
                $account->procurement_contract_id
            );

        if (!$contract) {
            return;
        }

        /*
         * Contract amount.
         */
        $contractValue =
            (float) (
                $contract->contract_amount
                ?? $contract->contract_value
                ?? $contract->total_amount
                ?? $contract->amount
                ?? 0
            );


        /*
         * Approved variations.
         */
        $variations = ConstructionVariation::query()
            ->where(
                'project_id',
                $account->project_id
            )
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->sum('amount');


        /*
         * Approved claims.
         */
        $claims = ConstructionClaim::query()
            ->where(
                'project_id',
                $account->project_id
            )
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->sum('approved_amount');


        /*
         * Contract currency.
         */
        $contractCurrency =
            $this->getContractCurrency(
                $contract
            );


        /*
         * --------------------------------------------------
         * PAYMENT CALCULATION
         * --------------------------------------------------
         *
         * Payments are always restricted to this contract.
         *
         * Only same-currency payments can be included
         * in amount_paid because there is currently no
         * exchange-rate field in procurement_contract_payments.
         */
        /*$paymentQuery = ProcurementContractPayment::query()
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Processed',
                ]
            );

        if ($contractCurrency) {
            $paymentQuery->where(
                'currency',
                $contractCurrency
            );
        }

        $amountPaid =
            (float) $paymentQuery->sum('amount');*/

        $amountPaid = (float) ProcurementContractPayment::query()
    ->where('procurement_contract_id', $contract->id)
    ->where('status', 'Processed')
    ->sum('amount');


        /*
         * Gross final amount.
         */
        $gross =
            (float) $contractValue
            + (float) $variations
            + (float) $claims
            + (float) $account->other_adjustments
            - (float) $account->advance_recovery
            - (float) $account->retention_amount
            - (float) $account->deductions;


        /*
         * Balance.
         */
        $balance =
            max(
                0,
                $gross - $amountPaid
            );


        /*
         * Save calculated values.
         */
        $account->updateQuietly([
            'contract_value' =>
                $contractValue,

            'approved_variations' =>
                $variations,

            'approved_claims' =>
                $claims,

            'amount_paid' =>
                $amountPaid,

            'gross_final_amount' =>
                $gross,

            'balance_payable' =>
                $balance,

            'updated_by' =>
                auth()->id(),
        ]);
    }


    /**
     * Get payment summary grouped by currency.
     */
    private function getPaymentCurrencySummary(
        int $contractId
    ) {

        return ProcurementContractPayment::query()
            ->where(
                'procurement_contract_id',
                $contractId
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Processed',
                ]
            )
            ->select(
                'currency',
                DB::raw('COUNT(*) as payment_count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();
    }


    /**
     * Check payment currency mismatch.
     */
    private function hasPaymentCurrencyMismatch(
        ?ProcurementContract $contract,
        $payments
    ): bool {

        if (!$contract) {
            return false;
        }

        $contractCurrency =
            $this->getContractCurrency(
                $contract
            );

        if (!$contractCurrency) {
            return false;
        }

        return $payments->contains(
            function ($payment) use ($contractCurrency) {

                return !empty($payment->currency)
                    && strtoupper(
                        trim($payment->currency)
                    ) !== strtoupper(
                        trim($contractCurrency)
                    );
            }
        );
    }


    /**
     * Get contract currency.
     */
    private function getContractCurrency(
        ?ProcurementContract $contract
    ): ?string {

        if (!$contract) {
            return null;
        }

        return $contract->currency
            ?? $contract->contract_currency
            ?? null;
    }


    /**
     * Get project procurement contracts.
     */
    private function projectContracts(
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
            );
    }


    /**
     * Get latest handover.
     */
    private function getHandover(
        Project $project
    ): HandoverProject {

        $handover =
            $project
                ->handoverProjects()
                ->latest('id')
                ->first();

        abort_unless(
            $handover,
            404,
            'Handover has not been started for this project.'
        );

        return $handover;
    }


    /**
     * Validate account belongs to project.
     */
    private function validateAccountProject(
        Project $project,
        HandoverFinalAccount $account
    ): void {

        abort_unless(
            (int) $account->project_id ===
            (int) $project->id,
            404
        );
    }


    /**
     * Generate Final Account number.
     */
    private function generateFinalAccountNo(
        Project $project
    ): string {

        $next =
            HandoverFinalAccount::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->count() + 1;

        return 'FA-'
            . str_pad(
                (string) $project->id,
                3,
                '0',
                STR_PAD_LEFT
            )
            . '-'
            . str_pad(
                (string) $next,
                3,
                '0',
                STR_PAD_LEFT
            );
    }
}