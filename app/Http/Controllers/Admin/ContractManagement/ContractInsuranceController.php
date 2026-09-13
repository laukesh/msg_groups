<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractInsurance;
use App\Models\ContractManagementContract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractInsuranceController extends Controller
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


        $insurances = ContractInsurance::query()

            ->where(
                'contract_management_contract_id',
                $contract->id
            )

            ->orderBy(
                'policy_expiry_date'
            )

            ->orderBy(
                'insurance_type'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Refresh Compliance Status
        |--------------------------------------------------------------------------
        */

        foreach ($insurances as $insurance) {

            $newComplianceStatus =
                $this->calculateComplianceStatus(
                    $insurance
                );


            if (
                $insurance->compliance_status
                !==
                $newComplianceStatus
            ) {

                $insurance->updateQuietly([

                    'compliance_status' =>
                        $newComplianceStatus,

                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $insurances->count(),

            'active' =>
                $insurances
                    ->where(
                        'status',
                        'Active'
                    )
                    ->count(),

            'expiring' =>
                $insurances
                    ->filter(
                        function ($insurance) {

                            return $insurance
                                ->isExpiringSoon();
                        }
                    )
                    ->count(),

            'expired' =>
                $insurances
                    ->filter(
                        function ($insurance) {

                            return $insurance
                                ->isExpired();
                        }
                    )
                    ->count(),

            'compliant' =>
                $insurances
                    ->where(
                        'compliance_status',
                        'Compliant'
                    )
                    ->count(),

            'non_compliant' =>
                $insurances
                    ->where(
                        'compliance_status',
                        'Non-Compliant'
                    )
                    ->count(),

            'total_coverage' =>
                (float) $insurances->sum(
                    'coverage_amount'
                ),

            'total_premium' =>
                (float) $insurances->sum(
                    'premium_amount'
                ),
        ];


        return view(
            'contract-management.insurances.index',
            compact(
                'project',
                'contract',
                'insurances',
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
            'contract-management.insurances.create',
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

            'insurance_type' =>
                'required|string|max:100',

            'policy_number' =>
                'nullable|string|max:150',

            'insurer_name' =>
                'required|string|max:255',

            'insured_party' =>
                'nullable|string|max:255',

            'beneficiary' =>
                'nullable|string|max:255',

            'coverage_amount' =>
                'required|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'policy_start_date' =>
                'nullable|date',

            'policy_expiry_date' =>
                'nullable|date|after_or_equal:policy_start_date',

            'submission_date' =>
                'nullable|date',

            'verification_date' =>
                'nullable|date',

            'renewal_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'days_before_expiry_alert' =>
                'required|integer|min:0|max:365',

            'premium_amount' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Insurance Number
        |--------------------------------------------------------------------------
        */

        $validated['insurance_number'] =
            $this->generateInsuranceNumber();


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


        $validated['premium_amount'] =
            $validated['premium_amount']
            ??
            0;


        $validated['days_before_expiry_alert'] =
            $validated['days_before_expiry_alert']
            ??
            30;


        /*
        |--------------------------------------------------------------------------
        | Compliance
        |--------------------------------------------------------------------------
        */

        $validated['compliance_status'] =
            'Pending';


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

        $insurance =
            ContractInsurance::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Calculate Compliance
        |--------------------------------------------------------------------------
        */

        $insurance->updateQuietly([

            'compliance_status' =>
                $this->calculateComplianceStatus(
                    $insurance
                ),

        ]);


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.insurances.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Insurance policy added successfully.'
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
        ContractInsurance $insurance
    ): View {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateInsurance(
            $contract,
            $insurance
        );


        return view(
            'contract-management.insurances.edit',
            compact(
                'project',
                'contract',
                'insurance'
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
        ContractInsurance $insurance
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateInsurance(
            $contract,
            $insurance
        );


        $validated = $request->validate([

            'insurance_type' =>
                'required|string|max:100',

            'policy_number' =>
                'nullable|string|max:150',

            'insurer_name' =>
                'required|string|max:255',

            'insured_party' =>
                'nullable|string|max:255',

            'beneficiary' =>
                'nullable|string|max:255',

            'coverage_amount' =>
                'required|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'policy_start_date' =>
                'nullable|date',

            'policy_expiry_date' =>
                'nullable|date|after_or_equal:policy_start_date',

            'submission_date' =>
                'nullable|date',

            'verification_date' =>
                'nullable|date',

            'renewal_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'days_before_expiry_alert' =>
                'required|integer|min:0|max:365',

            'premium_amount' =>
                'nullable|numeric|min:0',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['currency'] =
            $validated['currency']
            ??
            ($contract->currency ?? 'INR');


        $validated['premium_amount'] =
            $validated['premium_amount']
            ??
            0;


        /*
        |--------------------------------------------------------------------------
        | Compliance
        |--------------------------------------------------------------------------
        */

        $insurance->fill(
            $validated
        );


        $insurance->compliance_status =
            $this->calculateComplianceStatus(
                $insurance
            );


        $insurance->updated_by =
            Auth::id();


        $insurance->save();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.insurances.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Insurance policy updated successfully.'
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
        ContractInsurance $insurance
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateInsurance(
            $contract,
            $insurance
        );


        $insurance->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.insurances.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Insurance policy deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Insurance Number
    |--------------------------------------------------------------------------
    */

    protected function generateInsuranceNumber(): string
    {
        $lastId =
            ContractInsurance::max('id')
            ??
            0;


        return 'INS-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Compliance Status
    |--------------------------------------------------------------------------
    */

    protected function calculateComplianceStatus(
        ContractInsurance $insurance
    ): string {

        /*
        |--------------------------------------------------------------------------
        | No Policy Dates
        |--------------------------------------------------------------------------
        */

        if (
            !$insurance->policy_start_date ||
            !$insurance->policy_expiry_date
        ) {

            return 'Pending';
        }


        /*
        |--------------------------------------------------------------------------
        | Expired
        |--------------------------------------------------------------------------
        */

        if (
            $insurance->policy_expiry_date
                ->isPast()
        ) {

            return 'Expired';
        }


        /*
        |--------------------------------------------------------------------------
        | Cancelled
        |--------------------------------------------------------------------------
        */

        if (
            $insurance->status ===
            'Cancelled'
        ) {

            return 'Non-Compliant';
        }


        /*
        |--------------------------------------------------------------------------
        | Verification
        |--------------------------------------------------------------------------
        */

        if (
            !$insurance->verification_date
        ) {

            return 'Pending';
        }


        /*
        |--------------------------------------------------------------------------
        | Active + Verified
        |--------------------------------------------------------------------------
        */

        if (
            $insurance->status ===
            'Active'
        ) {

            return 'Compliant';
        }


        /*
        |--------------------------------------------------------------------------
        | Submitted / Under Verification
        |--------------------------------------------------------------------------
        */

        return 'Partially Compliant';
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
    | Validate Insurance
    |--------------------------------------------------------------------------
    */

    protected function validateInsurance(
        ContractManagementContract $contract,
        ContractInsurance $insurance
    ): void {

        if (
            (int)
            $insurance->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Insurance does not belong to this contract.'
            );
        }
    }
}