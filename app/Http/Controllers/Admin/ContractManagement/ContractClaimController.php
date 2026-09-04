<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractClaim;
use App\Models\ContractManagementContract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractClaimController extends Controller
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


        $claims = ContractClaim::query()

            ->where(
                'contract_management_contract_id',
                $contract->id
            )

            ->orderByDesc(
                'claim_date'
            )

            ->orderByDesc(
                'id'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $claims->count(),

            'submitted' =>
                $claims
                    ->where(
                        'status',
                        'Submitted'
                    )
                    ->count(),

            'under_review' =>
                $claims
                    ->whereIn(
                        'status',
                        [
                            'Under Review',
                            'Under Negotiation',
                        ]
                    )
                    ->count(),

            'approved' =>
                $claims
                    ->whereIn(
                        'status',
                        [
                            'Approved',
                            'Partially Approved',
                        ]
                    )
                    ->count(),

            'claimed_amount' =>
                (float)
                $claims->sum(
                    'claimed_amount'
                ),

            'approved_amount' =>
                (float)
                $claims->sum(
                    'approved_amount'
                ),
        ];


        return view(
            'contract-management.claims.index',
            compact(
                'project',
                'contract',
                'claims',
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
            'contract-management.claims.create',
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

            'claim_date' =>
                'required|date',

            'claim_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'reason' =>
                'nullable|string',

            'claimed_amount' =>
                'required|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'submitted_by_party' =>
                'nullable|string|max:255',

            'submission_date' =>
                'nullable|date',

            'response_due_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'review_remarks' =>
                'nullable|string',

            'resolution_remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Claim Number
        |--------------------------------------------------------------------------
        */

        $claimNumber =
            $this->generateClaimNumber();


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['contract_management_contract_id'] =
            $contract->id;

        $validated['claim_number'] =
            $claimNumber;

        $validated['currency'] =
            $validated['currency']
            ??
            $contract->currency
            ??
            'INR';

        $validated['approved_amount'] =
            0;

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        ContractClaim::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.claims.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Claim created successfully.'
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
        ContractClaim $claim
    ): View {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateClaim(
            $contract,
            $claim
        );


        return view(
            'contract-management.claims.edit',
            compact(
                'project',
                'contract',
                'claim'
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
        ContractClaim $claim
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateClaim(
            $contract,
            $claim
        );


        $validated = $request->validate([

            'claim_date' =>
                'required|date',

            'claim_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'reason' =>
                'nullable|string',

            'claimed_amount' =>
                'required|numeric|min:0',

            'approved_amount' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',

            'submitted_by_party' =>
                'nullable|string|max:255',

            'submission_date' =>
                'nullable|date',

            'response_due_date' =>
                'nullable|date',

            'resolution_date' =>
                'nullable|date',

            'status' =>
                'required|string|max:50',

            'review_remarks' =>
                'nullable|string',

            'resolution_remarks' =>
                'nullable|string',
        ]);


        $validated['approved_amount'] =
            $validated['approved_amount']
            ??
            0;

        $validated['currency'] =
            $validated['currency']
            ??
            $contract->currency
            ??
            'INR';

        $validated['updated_by'] =
            Auth::id();


        $claim->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.claims.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Claim updated successfully.'
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
        ContractClaim $claim
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateClaim(
            $contract,
            $claim
        );


        $claim->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.claims.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Claim deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Claim Number
    |--------------------------------------------------------------------------
    */

    protected function generateClaimNumber(): string
    {
        $lastId =
            ContractClaim::max('id')
            ??
            0;

        return 'CLM-' .
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
    | Validate Claim
    |--------------------------------------------------------------------------
    */

    protected function validateClaim(
        ContractManagementContract $contract,
        ContractClaim $claim
    ): void {

        if (
            (int)
            $claim->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Claim does not belong to this contract.'
            );
        }
    }
}