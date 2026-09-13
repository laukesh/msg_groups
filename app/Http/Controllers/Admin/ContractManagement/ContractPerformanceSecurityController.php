<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementContract;
use App\Models\ContractPerformanceSecurity;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractPerformanceSecurityController extends Controller
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


        $securities = ContractPerformanceSecurity::query()
            ->where(
                'contract_management_contract_id',
                $contract->id
            )
            ->orderByDesc('issue_date')
            ->orderBy('security_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Required Amount
        |--------------------------------------------------------------------------
        */

        $requiredAmount =
            (float) (
                $contract->performance_security_required
                    ? $contract->performance_security_amount
                    : 0
            );


        /*
        |--------------------------------------------------------------------------
        | Active / Valid Security Amount
        |--------------------------------------------------------------------------
        */

        $activeStatuses = [
            'Active',
            'Extended',
        ];


        $activeSecurities =
            $securities->filter(
                function ($security) use ($activeStatuses) {

                    return in_array(
                        $security->status,
                        $activeStatuses,
                        true
                    );
                }
            );


        $activeAmount =
            (float) $activeSecurities->sum(
                'security_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Total Submitted
        |--------------------------------------------------------------------------
        */

        $totalSubmitted =
            (float) $securities->sum(
                'security_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Shortfall
        |--------------------------------------------------------------------------
        */

        $shortfall =
            max(
                0,
                $requiredAmount - $activeAmount
            );


        /*
        |--------------------------------------------------------------------------
        | Coverage Percentage
        |--------------------------------------------------------------------------
        */

        $coveragePercentage = 0;

        if ($requiredAmount > 0) {

            $coveragePercentage =
                min(
                    100,
                    ($activeAmount / $requiredAmount) * 100
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Expiry
        |--------------------------------------------------------------------------
        */

        $expiringSoon =
            $securities->filter(
                function ($security) {

                    $days =
                        $security->daysUntilExpiry();

                    return
                        $days !== null &&
                        $days >= 0 &&
                        $days <= 30 &&
                        in_array(
                            $security->status,
                            [
                                'Active',
                                'Extended',
                            ],
                            true
                        );
                }
            )->count();


        $expired =
            $securities->filter(
                function ($security) {

                    return $security->isExpired();
                }
            )->count();


        /*
        |--------------------------------------------------------------------------
        | Verification
        |--------------------------------------------------------------------------
        */

        $verified =
            $securities->where(
                'verification_status',
                'Verified'
            )->count();


        $pendingVerification =
            $securities->where(
                'verification_status',
                'Pending'
            )->count();


        $rejected =
            $securities->where(
                'verification_status',
                'Rejected'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $securities->count(),

            'required_amount' =>
                $requiredAmount,

            'active_amount' =>
                $activeAmount,

            'total_submitted' =>
                $totalSubmitted,

            'shortfall' =>
                $shortfall,

            'coverage_percentage' =>
                $coveragePercentage,

            'expiring_soon' =>
                $expiringSoon,

            'expired' =>
                $expired,

            'verified' =>
                $verified,

            'pending_verification' =>
                $pendingVerification,

            'rejected' =>
                $rejected,
        ];


        return view(
            'contract-management.performance-securities.index',
            compact(
                'project',
                'contract',
                'securities',
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
            'contract-management.performance-securities.create',
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

            'security_type' =>
                'required|string|max:100',

            'instrument_number' =>
                'nullable|string|max:150',

            'issuing_bank' =>
                'nullable|string|max:255',

            'issuing_branch' =>
                'nullable|string|max:255',

            'beneficiary' =>
                'nullable|string|max:255',

            'security_amount' =>
                'required|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'issue_date' =>
                'nullable|date',

            'expiry_date' =>
                'nullable|date|after_or_equal:issue_date',

            'submission_date' =>
                'nullable|date',

            'verification_date' =>
                'nullable|date',

            'claim_expiry_date' =>
                'nullable|date|after_or_equal:expiry_date',

            'release_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'verification_status' =>
                'required|string|max:50',

            'extension_required' =>
                'nullable|boolean',

            'extended_expiry_date' =>
                'nullable|date|after_or_equal:expiry_date',

            'released_amount' =>
                'nullable|numeric|min:0',

            'release_remarks' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Release Amount Validation
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['released_amount']) &&
            isset($validated['security_amount']) &&
            $validated['released_amount']
                > $validated['security_amount']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'released_amount' =>
                        'Released amount cannot exceed security amount.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Number
        |--------------------------------------------------------------------------
        */

        $validated['security_number'] =
            $this->generateSecurityNumber();


        /*
        |--------------------------------------------------------------------------
        | Contract
        |--------------------------------------------------------------------------
        */

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


        $validated['released_amount'] =
            $validated['released_amount']
            ??
            0;


        $validated['extension_required'] =
            $request->boolean(
                'extension_required'
            );


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

        ContractPerformanceSecurity::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.performance-securities.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Performance security added successfully.'
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
        ContractPerformanceSecurity $security
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateSecurity(
            $contract,
            $security
        );


        return view(
            'contract-management.performance-securities.edit',
            compact(
                'project',
                'contract',
                'security'
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
        ContractPerformanceSecurity $security
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateSecurity(
            $contract,
            $security
        );


        $validated = $request->validate([

            'security_type' =>
                'required|string|max:100',

            'instrument_number' =>
                'nullable|string|max:150',

            'issuing_bank' =>
                'nullable|string|max:255',

            'issuing_branch' =>
                'nullable|string|max:255',

            'beneficiary' =>
                'nullable|string|max:255',

            'security_amount' =>
                'required|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'issue_date' =>
                'nullable|date',

            'expiry_date' =>
                'nullable|date|after_or_equal:issue_date',

            'submission_date' =>
                'nullable|date',

            'verification_date' =>
                'nullable|date',

            'claim_expiry_date' =>
                'nullable|date|after_or_equal:expiry_date',

            'release_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'verification_status' =>
                'required|string|max:50',

            'extension_required' =>
                'nullable|boolean',

            'extended_expiry_date' =>
                'nullable|date|after_or_equal:expiry_date',

            'released_amount' =>
                'nullable|numeric|min:0',

            'release_remarks' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Release Amount Validation
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['released_amount']) &&
            $validated['released_amount']
                > $validated['security_amount']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'released_amount' =>
                        'Released amount cannot exceed security amount.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Extension
        |--------------------------------------------------------------------------
        */

        $validated['extension_required'] =
            $request->boolean(
                'extension_required'
            );


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

        $security->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.performance-securities.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Performance security updated successfully.'
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
        ContractPerformanceSecurity $security
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateSecurity(
            $contract,
            $security
        );


        $security->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.performance-securities.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Performance security deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Security Number
    |--------------------------------------------------------------------------
    */

    protected function generateSecurityNumber(): string
    {
        $lastId =
            ContractPerformanceSecurity::max('id')
            ??
            0;


        return 'SEC-' .
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
    | Validate Security
    |--------------------------------------------------------------------------
    */

    protected function validateSecurity(
        ContractManagementContract $contract,
        ContractPerformanceSecurity $security
    ): void {

        if (
            (int)
            $security->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Performance security does not belong to this contract.'
            );
        }
    }
}