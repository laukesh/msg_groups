<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementAdvancePayment;
use App\Models\ContractManagementContract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractManagementAdvancePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $transactions =
            ContractManagementAdvancePayment::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalReleased =
            (float) $transactions
                ->where(
                    'transaction_type',
                    'Advance Released'
                )
                ->sum('advance_amount');


        $totalRecovered =
            (float) $transactions
                ->sum('recovered_amount');


        /*
        |--------------------------------------------------------------------------
        | Adjustments / Refunds
        |--------------------------------------------------------------------------
        */

        $totalAdjustments =
            (float) $transactions
                ->where(
                    'transaction_type',
                    'Adjustment'
                )
                ->sum('advance_amount');


        $totalRefunds =
            (float) $transactions
                ->where(
                    'transaction_type',
                    'Refund'
                )
                ->sum('advance_amount');


        /*
        |--------------------------------------------------------------------------
        | Outstanding
        |--------------------------------------------------------------------------
        */

        $outstanding =
            max(
                0,
                $totalReleased
                -
                $totalRecovered
                -
                $totalAdjustments
                -
                $totalRefunds
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($totalReleased <= 0) {

            $overallStatus = 'Not Released';

        } elseif ($outstanding <= 0) {

            $overallStatus = 'Fully Recovered';

        } elseif ($totalRecovered > 0) {

            $overallStatus = 'Partially Recovered';

        } else {

            $overallStatus = 'Released';
        }


        /*
        |--------------------------------------------------------------------------
        | Transaction Counts
        |--------------------------------------------------------------------------
        */

        $releasedCount =
            $transactions
                ->where(
                    'transaction_type',
                    'Advance Released'
                )
                ->count();


        $recoveryCount =
            $transactions
                ->where(
                    'transaction_type',
                    'Advance Recovery'
                )
                ->count();


        $upcomingRecoveries =
            $transactions->filter(
                function ($transaction) {

                    if (
                        !$transaction->expected_recovery_date
                    ) {
                        return false;
                    }

                    if (
                        (float)
                        $transaction->balance_amount <= 0
                    ) {
                        return false;
                    }

                    $days =
                        now()
                            ->startOfDay()
                            ->diffInDays(
                                $transaction->expected_recovery_date,
                                false
                            );

                    return
                        $days >= 0 &&
                        $days <= 30;
                }
            )->count();


        $summary = [

            'total_transactions' =>
                $transactions->count(),

            'total_released' =>
                $totalReleased,

            'total_recovered' =>
                $totalRecovered,

            'total_adjustments' =>
                $totalAdjustments,

            'total_refunds' =>
                $totalRefunds,

            'outstanding' =>
                $outstanding,

            'released_count' =>
                $releasedCount,

            'recovery_count' =>
                $recoveryCount,

            'upcoming_recoveries' =>
                $upcomingRecoveries,

            'advance_required' =>
                (bool)
                $contract->advance_payment_required,

            'contract_advance_amount' =>
                (float)
                $contract->advance_payment_amount,

            'overall_status' =>
                $overallStatus,
        ];


        return view(
            'contract-management.advance-payments.index',
            compact(
                'project',
                'contract',
                'transactions',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        return view(
            'contract-management.advance-payments.create',
            compact(
                'project',
                'contract'
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
        Project $project,
        ContractManagementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $validated = $request->validate([

            'transaction_date' =>
                'required|date',

            'transaction_type' =>
                'required|in:Advance Released,Advance Recovery,Adjustment,Refund',

            'reference_number' =>
                'nullable|string|max:100',

            'certified_amount' =>
                'nullable|numeric|min:0',

            'advance_amount' =>
                'nullable|numeric|min:0',

            'recovered_amount' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'expected_recovery_date' =>
                'nullable|date',

            'recovery_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'remarks' =>
                'nullable|string',
        ]);


        $transactionType =
            $validated['transaction_type'];


        $amount =
            (float) (
                $validated['advance_amount']
                ??
                0
            );


        $recoveredAmount =
            (float) (
                $validated['recovered_amount']
                ??
                0
            );


        /*
        |--------------------------------------------------------------------------
        | Transaction Validation
        |--------------------------------------------------------------------------
        */

        if (
            $transactionType ===
            'Advance Released'
        ) {

            if ($amount <= 0) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'advance_amount' =>
                            'Advance amount must be greater than zero.',
                    ]);
            }

            $validated['recovered_amount'] =
                0;

        } elseif (
            $transactionType ===
            'Advance Recovery'
        ) {

            if ($recoveredAmount <= 0) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'recovered_amount' =>
                            'Recovery amount must be greater than zero.',
                    ]);
            }

            $validated['advance_amount'] =
                0;
        }


        /*
        |--------------------------------------------------------------------------
        | Current Ledger
        |--------------------------------------------------------------------------
        */

        $existing =
            ContractManagementAdvancePayment::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->get();


        $totalReleased =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Advance Released'
                )
                ->sum('advance_amount');


        $totalRecovered =
            (float) $existing
                ->sum('recovered_amount');


        $totalAdjustments =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Adjustment'
                )
                ->sum('advance_amount');


        $totalRefunds =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Refund'
                )
                ->sum('advance_amount');


        $currentOutstanding =
            max(
                0,
                $totalReleased
                -
                $totalRecovered
                -
                $totalAdjustments
                -
                $totalRefunds
            );


        /*
        |--------------------------------------------------------------------------
        | Recovery Cannot Exceed Outstanding
        |--------------------------------------------------------------------------
        */

        if (
            $transactionType ===
            'Advance Recovery'
            &&
            $recoveredAmount >
            $currentOutstanding
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'recovered_amount' =>
                        'Recovery amount cannot exceed the outstanding advance of '
                        .
                        number_format(
                            $currentOutstanding,
                            2
                        )
                        .
                        '.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Advance Release Cannot Exceed Contract Term
        |--------------------------------------------------------------------------
        */

        if (
            $transactionType ===
            'Advance Released'
            &&
            (float)
            $contract->advance_payment_amount > 0
        ) {

            $newTotal =
                $totalReleased +
                $amount;


            if (
                $newTotal >
                (float)
                $contract->advance_payment_amount
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'advance_amount' =>
                            'Total advance released cannot exceed the contract advance amount of '
                            .
                            number_format(
                                $contract->advance_payment_amount,
                                2
                            )
                            .
                            '.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | New Balance
        |--------------------------------------------------------------------------
        */

        $newReleased =
            $totalReleased;


        $newRecovered =
            $totalRecovered;


        $newAdjustments =
            $totalAdjustments;


        $newRefunds =
            $totalRefunds;


        if (
            $transactionType ===
            'Advance Released'
        ) {

            $newReleased +=
                $amount;

        } elseif (
            $transactionType ===
            'Advance Recovery'
        ) {

            $newRecovered +=
                $recoveredAmount;

        } elseif (
            $transactionType ===
            'Adjustment'
        ) {

            $newAdjustments +=
                $amount;

        } elseif (
            $transactionType ===
            'Refund'
        ) {

            $newRefunds +=
                $amount;
        }


        $newBalance =
            max(
                0,
                $newReleased
                -
                $newRecovered
                -
                $newAdjustments
                -
                $newRefunds
            );


        /*
        |--------------------------------------------------------------------------
        | Row Values
        |--------------------------------------------------------------------------
        */

        $validated['advance_amount'] =
            $transactionType ===
            'Advance Released'
                ? $amount
                : 0;


        $validated['recovered_amount'] =
            $transactionType ===
            'Advance Recovery'
                ? $recoveredAmount
                : 0;


        $validated['balance_amount'] =
            $newBalance;


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($newReleased <= 0) {

            $validated['status'] =
                'Not Released';

        } elseif ($newBalance <= 0) {

            $validated['status'] =
                'Fully Recovered';

        } elseif ($newRecovered > 0) {

            $validated['status'] =
                'Partially Recovered';

        } else {

            $validated['status'] =
                'Released';
        }


        /*
        |--------------------------------------------------------------------------
        | Project / Contract
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        $validated[
            'contract_management_contract_id'
        ] =
            $contract->id;


        /*
        |--------------------------------------------------------------------------
        | Currency
        |--------------------------------------------------------------------------
        */

        $validated['currency'] =
            $validated['currency']
            ??
            ($contract->currency ?? 'INR');


        /*
        |--------------------------------------------------------------------------
        | Advance Number
        |--------------------------------------------------------------------------
        */

        $validated['advance_number'] =
            $this->generateAdvanceNumber();


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        ContractManagementAdvancePayment::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.advance-payments.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Advance payment transaction added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementAdvancePayment $advancePayment
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateTransaction(
            $contract,
            $advancePayment
        );


        return view(
            'contract-management.advance-payments.edit',
            compact(
                'project',
                'contract',
                'advancePayment'
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
        ContractManagementContract $contract,
        ContractManagementAdvancePayment $advancePayment
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateTransaction(
            $contract,
            $advancePayment
        );


        $validated = $request->validate([

            'transaction_date' =>
                'required|date',

            'transaction_type' =>
                'required|in:Advance Released,Advance Recovery,Adjustment,Refund',

            'reference_number' =>
                'nullable|string|max:100',

            'certified_amount' =>
                'nullable|numeric|min:0',

            'advance_amount' =>
                'nullable|numeric|min:0',

            'recovered_amount' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'expected_recovery_date' =>
                'nullable|date',

            'recovery_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Rebuild Ledger Excluding Current Transaction
        |--------------------------------------------------------------------------
        */

        $existing =
            ContractManagementAdvancePayment::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->where(
                    'id',
                    '!=',
                    $advancePayment->id
                )
                ->get();


        $totalReleased =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Advance Released'
                )
                ->sum('advance_amount');


        $totalRecovered =
            (float) $existing
                ->sum('recovered_amount');


        $totalAdjustments =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Adjustment'
                )
                ->sum('advance_amount');


        $totalRefunds =
            (float) $existing
                ->where(
                    'transaction_type',
                    'Refund'
                )
                ->sum('advance_amount');


        $transactionType =
            $validated['transaction_type'];


        $amount =
            (float) (
                $validated['advance_amount']
                ??
                0
            );


        $recoveredAmount =
            (float) (
                $validated['recovered_amount']
                ??
                0
            );


        if (
            $transactionType ===
            'Advance Released'
        ) {

            if ($amount <= 0) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'advance_amount' =>
                            'Advance amount must be greater than zero.',
                    ]);
            }

            $validated['recovered_amount'] =
                0;

        } elseif (
            $transactionType ===
            'Advance Recovery'
        ) {

            if ($recoveredAmount <= 0) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'recovered_amount' =>
                            'Recovery amount must be greater than zero.',
                    ]);
            }

            $validated['advance_amount'] =
                0;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Release Limit
        |--------------------------------------------------------------------------
        */

        if (
            $transactionType ===
            'Advance Released'
            &&
            (float)
            $contract->advance_payment_amount > 0
            &&
            (
                $totalReleased +
                $amount
            ) >
            (float)
            $contract->advance_payment_amount
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'advance_amount' =>
                        'Total advance released cannot exceed the contract advance amount.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Recovery Limit
        |--------------------------------------------------------------------------
        */

        $currentOutstanding =
            max(
                0,
                $totalReleased
                -
                $totalRecovered
                -
                $totalAdjustments
                -
                $totalRefunds
            );


        if (
            $transactionType ===
            'Advance Recovery'
            &&
            $recoveredAmount >
            $currentOutstanding
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'recovered_amount' =>
                        'Recovery amount cannot exceed the current outstanding advance.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Recalculate
        |--------------------------------------------------------------------------
        */

        $newReleased =
            $totalReleased;


        $newRecovered =
            $totalRecovered;


        $newAdjustments =
            $totalAdjustments;


        $newRefunds =
            $totalRefunds;


        if (
            $transactionType ===
            'Advance Released'
        ) {

            $newReleased +=
                $amount;

        } elseif (
            $transactionType ===
            'Advance Recovery'
        ) {

            $newRecovered +=
                $recoveredAmount;

        } elseif (
            $transactionType ===
            'Adjustment'
        ) {

            $newAdjustments +=
                $amount;

        } elseif (
            $transactionType ===
            'Refund'
        ) {

            $newRefunds +=
                $amount;
        }


        $newBalance =
            max(
                0,
                $newReleased
                -
                $newRecovered
                -
                $newAdjustments
                -
                $newRefunds
            );


        $validated['advance_amount'] =
            $transactionType ===
            'Advance Released'
                ? $amount
                : 0;


        $validated['recovered_amount'] =
            $transactionType ===
            'Advance Recovery'
                ? $recoveredAmount
                : 0;


        $validated['balance_amount'] =
            $newBalance;


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($newReleased <= 0) {

            $validated['status'] =
                'Not Released';

        } elseif ($newBalance <= 0) {

            $validated['status'] =
                'Fully Recovered';

        } elseif ($newRecovered > 0) {

            $validated['status'] =
                'Partially Recovered';

        } else {

            $validated['status'] =
                'Released';
        }


        $validated['currency'] =
            $validated['currency']
            ??
            ($contract->currency ?? 'INR');


        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $advancePayment->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.advance-payments.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Advance payment transaction updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementAdvancePayment $advancePayment
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateTransaction(
            $contract,
            $advancePayment
        );


        $advancePayment->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.advance-payments.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Advance payment transaction deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Number
    |--------------------------------------------------------------------------
    */

    protected function generateAdvanceNumber(): string
    {
        $lastId =
            ContractManagementAdvancePayment::max('id')
            ??
            0;


        return 'ADV-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Contract
    |--------------------------------------------------------------------------
    */

    protected function validateContract(
        Project $project,
        ContractManagementContract $contract
    ): void {

        if (
            (int) $contract->project_id !==
            (int) $project->id
        ) {

            abort(
                404,
                'Contract does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Transaction
    |--------------------------------------------------------------------------
    */

    protected function validateTransaction(
        ContractManagementContract $contract,
        ContractManagementAdvancePayment $advancePayment
    ): void {

        if (
            (int)
            $advancePayment
                ->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Advance payment transaction does not belong to this contract.'
            );
        }
    }
}