<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementContract;
use App\Models\ContractManagementRetention;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractManagementRetentionController extends Controller
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


        $retentions =
            ContractManagementRetention::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->orderByDesc('retention_date')
                ->orderBy('retention_number')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalRetained =
            (float) $retentions->sum(
                'retention_amount'
            );


        $totalReleased =
            (float) $retentions->sum(
                'released_amount'
            );


        $totalBalance =
            (float) $retentions->sum(
                'balance_amount'
            );


        $totalCertified =
            (float) $retentions->sum(
                'certified_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Retention Configuration
        |--------------------------------------------------------------------------
        */

        $retentionRequired =
            (bool)
            $contract->retention_required;


        $retentionPercentage =
            (float)
            $contract->retention_percentage;


        /*
        |--------------------------------------------------------------------------
        | Status Counts
        |--------------------------------------------------------------------------
        */

        $retainedCount =
            $retentions
                ->where(
                    'status',
                    'Retained'
                )
                ->count();


        $partiallyReleasedCount =
            $retentions
                ->where(
                    'status',
                    'Partially Released'
                )
                ->count();


        $fullyReleasedCount =
            $retentions
                ->where(
                    'status',
                    'Fully Released'
                )
                ->count();


        $disputedCount =
            $retentions
                ->where(
                    'status',
                    'Disputed'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Expected Releases
        |--------------------------------------------------------------------------
        */

        $upcomingReleases =
            $retentions->filter(
                function ($retention) {

                    $days =
                        $retention->daysUntilRelease();

                    return
                        $days !== null &&
                        $days >= 0 &&
                        $days <= 30 &&
                        (float)
                        $retention->balance_amount > 0;
                }
            )->count();


        /*
        |--------------------------------------------------------------------------
        | Summary Array
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $retentions->count(),

            'total_certified' =>
                $totalCertified,

            'total_retained' =>
                $totalRetained,

            'total_released' =>
                $totalReleased,

            'total_balance' =>
                $totalBalance,

            'retention_required' =>
                $retentionRequired,

            'retention_percentage' =>
                $retentionPercentage,

            'retained_count' =>
                $retainedCount,

            'partially_released_count' =>
                $partiallyReleasedCount,

            'fully_released_count' =>
                $fullyReleasedCount,

            'disputed_count' =>
                $disputedCount,

            'upcoming_releases' =>
                $upcomingReleases,
        ];


        return view(
            'contract-management.retentions.index',
            compact(
                'project',
                'contract',
                'retentions',
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
            'contract-management.retentions.create',
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

            'retention_date' =>
                'required|date',

            'invoice_number' =>
                'nullable|string|max:100',

            'payment_reference' =>
                'nullable|string|max:100',

            'certified_amount' =>
                'required|numeric|min:0',

            'retention_percentage' =>
                'required|numeric|min:0|max:100',

            'retention_amount' =>
                'nullable|numeric|min:0',

            'released_amount' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'expected_release_date' =>
                'nullable|date',

            'release_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'release_remarks' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Retention
        |--------------------------------------------------------------------------
        */

        $certifiedAmount =
            (float)
            $validated['certified_amount'];


        $retentionPercentage =
            (float)
            $validated['retention_percentage'];


        $retentionAmount =
            round(
                $certifiedAmount
                *
                $retentionPercentage
                /
                100,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Ignore manually supplied calculated amount
        |--------------------------------------------------------------------------
        */

        $validated['retention_amount'] =
            $retentionAmount;


        /*
        |--------------------------------------------------------------------------
        | Released Amount
        |--------------------------------------------------------------------------
        */

        $releasedAmount =
            (float) (
                $validated['released_amount']
                ??
                0
            );


        if (
            $releasedAmount >
            $retentionAmount
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'released_amount' =>
                        'Released amount cannot exceed retention amount.',
                ]);
        }


        $balanceAmount =
            round(
                $retentionAmount
                -
                $releasedAmount,
                2
            );


        $validated['released_amount'] =
            $releasedAmount;


        $validated['balance_amount'] =
            $balanceAmount;


        /*
        |--------------------------------------------------------------------------
        | Automatic Status
        |--------------------------------------------------------------------------
        */

        if ($balanceAmount <= 0) {

            $validated['status'] =
                'Fully Released';

        } elseif ($releasedAmount > 0) {

            $validated['status'] =
                'Partially Released';

        }


        /*
        |--------------------------------------------------------------------------
        | Contract / Project
        |--------------------------------------------------------------------------
        */

        $validated[
            'project_id'
        ] = $project->id;


        $validated[
            'contract_management_contract_id'
        ] = $contract->id;


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['currency'] =
            $validated['currency']
            ??
            ($contract->currency ?? 'INR');


        /*
        |--------------------------------------------------------------------------
        | Generate Retention Number
        |--------------------------------------------------------------------------
        */

        $validated['retention_number'] =
            $this->generateRetentionNumber();


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        ContractManagementRetention::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.retentions.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Retention entry added successfully.'
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
        ContractManagementRetention $retention
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateRetention(
            $contract,
            $retention
        );


        return view(
            'contract-management.retentions.edit',
            compact(
                'project',
                'contract',
                'retention'
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
        ContractManagementRetention $retention
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateRetention(
            $contract,
            $retention
        );


        $validated = $request->validate([

            'retention_date' =>
                'required|date',

            'invoice_number' =>
                'nullable|string|max:100',

            'payment_reference' =>
                'nullable|string|max:100',

            'certified_amount' =>
                'required|numeric|min:0',

            'retention_percentage' =>
                'required|numeric|min:0|max:100',

            'released_amount' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'expected_release_date' =>
                'nullable|date',

            'release_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'release_remarks' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Recalculate
        |--------------------------------------------------------------------------
        */

        $certifiedAmount =
            (float)
            $validated['certified_amount'];


        $retentionPercentage =
            (float)
            $validated['retention_percentage'];


        $retentionAmount =
            round(
                $certifiedAmount
                *
                $retentionPercentage
                /
                100,
                2
            );


        $releasedAmount =
            (float) (
                $validated['released_amount']
                ??
                0
            );


        if (
            $releasedAmount >
            $retentionAmount
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'released_amount' =>
                        'Released amount cannot exceed retention amount.',
                ]);
        }


        $balanceAmount =
            round(
                $retentionAmount
                -
                $releasedAmount,
                2
            );


        $validated['retention_amount'] =
            $retentionAmount;


        $validated['released_amount'] =
            $releasedAmount;


        $validated['balance_amount'] =
            $balanceAmount;


        /*
        |--------------------------------------------------------------------------
        | Automatic Status
        |--------------------------------------------------------------------------
        */

        if ($balanceAmount <= 0) {

            $validated['status'] =
                'Fully Released';

        } elseif ($releasedAmount > 0) {

            $validated['status'] =
                'Partially Released';

        }


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
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $retention->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.retentions.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Retention entry updated successfully.'
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
        ContractManagementRetention $retention
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateRetention(
            $contract,
            $retention
        );


        $retention->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.retentions.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Retention entry deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Retention Number
    |--------------------------------------------------------------------------
    */

    protected function generateRetentionNumber(): string
    {
        $lastId =
            ContractManagementRetention::max('id')
            ??
            0;


        return 'RET-' .
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
    | Validate Retention
    |--------------------------------------------------------------------------
    */

    protected function validateRetention(
        ContractManagementContract $contract,
        ContractManagementRetention $retention
    ): void {

        if (
            (int)
            $retention->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Retention entry does not belong to this contract.'
            );
        }
    }
}