<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementContract;
use App\Models\Project;
use App\Models\ProcurementContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractManagementContractController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contract Register
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $contracts = ContractManagementContract::query()

            ->where(
                'project_id',
                $project->id
            )

            ->with([
                'procurementContract.bidder',
                'consultant',
                'responsibleUser',
            ])

            ->orderBy(
                'contract_number'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_contracts' =>
                $contracts->count(),

            'active_contracts' =>
                $contracts
                    ->filter(
                        function ($contract) {

                            return in_array(
                                $contract->status,
                                [
                                    'Active',
                                    'Approved',
                                    'In Progress',
                                ],
                                true
                            );
                        }
                    )
                    ->count(),

            'completed_contracts' =>
                $contracts
                    ->filter(
                        function ($contract) {

                            return in_array(
                                $contract->status,
                                [
                                    'Completed',
                                    'Closed',
                                ],
                                true
                            );
                        }
                    )
                    ->count(),

            'total_value' =>
                (float)
                $contracts->sum(
                    'contract_value'
                ),
        ];


        return view(
            'contract-management.contracts.index',
            compact(
                'project',
                'contracts',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Contract Details
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ContractManagementContract $contract
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Contract Belongs To Project
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $contract->load([

    'procurementContract.bidder',

    'procurementContract.tender.package.procurementPlan',

    /*
    |--------------------------------------------------------------------------
    | Existing Procurement Contract Milestones
    |--------------------------------------------------------------------------
    */

    'procurementContract.milestones',

    /*
    |--------------------------------------------------------------------------
    | Existing Procurement Contract Financial Data
    |--------------------------------------------------------------------------
    */

    'procurementContract.invoices',

    'procurementContract.payments',

    'procurementContract.purchaseOrders',

    'procurementContract.variations',

    /*
    |--------------------------------------------------------------------------
    | Contract Management Relationships
    |--------------------------------------------------------------------------
    */

    'consultant',

    'responsibleUser',

    'creator',

    'updater',

    'claims',

    'extensionsOfTime',

    'insurances',

    'performanceSecurities',

    'retentions',

    'advancePayments',

    'documents',

    'correspondence',
]);


        /*
        |--------------------------------------------------------------------------
        | Related Procurement Contract
        |--------------------------------------------------------------------------
        */

        $procurementContract =
            $contract->procurementContract;


        /*
        |--------------------------------------------------------------------------
        | Related Consultant
        |--------------------------------------------------------------------------
        */

        $consultant =
            $contract->consultant;


        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | These are the existing Procurement Contract Milestones.
        | No duplicate Contract Management milestone records are created.
        |
        */

        $milestones =
            $procurementContract
                ? $procurementContract->milestones
                : collect();


        /*
        |--------------------------------------------------------------------------
        | Milestone Summary
        |--------------------------------------------------------------------------
        */

        $milestoneSummary = [

            'total' =>
                $milestones->count(),

            'pending' =>
                $milestones
                    ->where(
                        'status',
                        'Pending'
                    )
                    ->count(),

            'in_progress' =>
                $milestones
                    ->where(
                        'status',
                        'In Progress'
                    )
                    ->count(),

            'completed' =>
                $milestones
                    ->where(
                        'status',
                        'Completed'
                    )
                    ->count(),

            'delayed' =>
                $milestones
                    ->where(
                        'status',
                        'Delayed'
                    )
                    ->count(),

            'total_value' =>
                (float)
                $milestones->sum(
                    'milestone_amount'
                ),

            'average_progress' =>
                $milestones->count()
                    ? round(
                        (float)
                        $milestones->avg(
                            'progress_percentage'
                        ),
                        2
                    )
                    : 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        */

        $contractValue =
            (float)
            $contract->contract_value;


        $invoiceAmount = 0;

        $paidAmount = 0;

        $variationAmount = 0;


        if ($procurementContract) {

            $invoiceAmount =
                (float)
                $procurementContract
                    ->invoices
                    ->sum(
                        'net_amount'
                    );


            $paidAmount =
                (float)
                $procurementContract
                    ->payments
                    ->sum(
                        'amount'
                    );


            $variationAmount =
                (float)
                $procurementContract
                    ->variations
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->sum(
                        'amount'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Revised Contract Value
        |--------------------------------------------------------------------------
        */

        $revisedContractValue =
            $contractValue +
            $variationAmount;


        /*
        |--------------------------------------------------------------------------
        | Outstanding
        |--------------------------------------------------------------------------
        */

        $outstandingAmount =
            max(
                0,
                $invoiceAmount -
                $paidAmount
            );


        /*
        |--------------------------------------------------------------------------
        | Contract Time Progress
        |--------------------------------------------------------------------------
        */

        $daysTotal = null;

        $daysElapsed = null;

        $daysRemaining = null;

        $timeProgress = null;


        if (
            $contract->start_date &&
            $contract->completion_date
        ) {

            $start =
                $contract
                    ->start_date
                    ->startOfDay();


            $end =
                $contract
                    ->completion_date
                    ->startOfDay();


            $today =
                now()
                    ->startOfDay();


            $daysTotal =
                $start->diffInDays(
                    $end
                );


            $daysElapsed =
                $start->diffInDays(
                    min(
                        $today,
                        $end
                    )
                );


            $daysRemaining =
                $today->lt($end)
                    ? $today->diffInDays(
                        $end
                    )
                    : 0;


            if ($daysTotal > 0) {

                $timeProgress =
                    min(
                        100,
                        max(
                            0,
                            (
                                $daysElapsed /
                                $daysTotal
                            ) * 100
                        )
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Security Summary
        |--------------------------------------------------------------------------
        */

        $retentionAmount =
            $contract->retention_required

                ? (
                    $contractValue *
                    (
                        (float)
                        $contract
                            ->retention_percentage
                        / 100
                    )
                )

                : 0;


        /*
        |--------------------------------------------------------------------------
        | Payment Summary
        |--------------------------------------------------------------------------
        */

        $paymentPercentage =
            $contractValue > 0

                ? min(
                    100,
                    (
                        $paidAmount /
                        $contractValue
                    ) * 100
                )

                : 0;


        /*
        |--------------------------------------------------------------------------
        | Status Class
        |--------------------------------------------------------------------------
        */

        $statusClass = match (
            $contract->status
        ) {

            'Active',
            'Approved',
            'In Progress'
                => 'success',

            'Completed',
            'Closed'
                => 'secondary',

            'Pending',
            'Draft'
                => 'warning',

            'Cancelled',
            'Terminated'
                => 'danger',

            default
                => 'secondary',
        };


        /*
        |--------------------------------------------------------------------------
        | Return Detail View
        |--------------------------------------------------------------------------
        */

        return view(
            'contract-management.contracts.show',
            compact(

                'project',

                'contract',

                'procurementContract',

                'consultant',

                'milestones',

                'milestoneSummary',

                'contractValue',

                'invoiceAmount',

                'paidAmount',

                'variationAmount',

                'revisedContractValue',

                'outstandingAmount',

                'daysTotal',

                'daysElapsed',

                'daysRemaining',

                'timeProgress',

                'retentionAmount',

                'paymentPercentage',

                'statusClass'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sync Procurement Contracts
    |--------------------------------------------------------------------------
    |
    | Import existing Procurement Contracts into the shared
    | Contract Management register.
    |
    */

    public function syncProcurementContracts(
        Project $project
    ): RedirectResponse {

        $procurementContracts =
            ProcurementContract::query()

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
                )

                ->with([
                    'bidder',
                    'tender.package.procurementPlan',
                ])

                ->get();


        $created = 0;

        $existing = 0;


        foreach (
            $procurementContracts
            as $procurementContract
        ) {

            /*
            |--------------------------------------------------------------------------
            | Check Existing Link
            |--------------------------------------------------------------------------
            */

            $contract =
                ContractManagementContract::query()
                    ->where(
                        'procurement_contract_id',
                        $procurementContract->id
                    )
                    ->first();


            if ($contract) {

                $existing++;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Party Name
            |--------------------------------------------------------------------------
            */

            $partyName =
                $procurementContract
                    ->bidder
                    ?->company_name

                ??

                $procurementContract
                    ->bidder_name

                ??

                'Unknown Party';


            /*
            |--------------------------------------------------------------------------
            | Generate Contract Management Code
            |--------------------------------------------------------------------------
            */

            $contractCode =
                $this->generateContractCode();


            /*
            |--------------------------------------------------------------------------
            | Create Register Record
            |--------------------------------------------------------------------------
            */

            ContractManagementContract::create([

                'project_id' =>
                    $project->id,

                'contract_code' =>
                    $contractCode,

                'contract_source' =>
                    'Procurement',

                'procurement_contract_id' =>
                    $procurementContract->id,

                'project_consultant_id' =>
                    null,

                'party_type' =>
                    'Contractor',

                'party_name' =>
                    $partyName,

                'contract_number' =>
                    $procurementContract
                        ->contract_number,

                'contract_title' =>
                    $procurementContract
                        ->contract_title
                    ??
                    $procurementContract
                        ->contract_number
                    ??
                    'Procurement Contract',

                'contract_type' =>
                    $procurementContract
                        ->contract_type,

                'contract_value' =>
                    $procurementContract
                        ->contract_amount
                    ??
                    0,

                'currency' =>
                    $procurementContract
                        ->currency
                    ??
                    'INR',

                'start_date' =>
                    $procurementContract
                        ->contract_start_date,

                'completion_date' =>
                    $procurementContract
                        ->completion_date
                    ??
                    $procurementContract
                        ->contract_end_date,

                'signing_date' =>
                    $procurementContract
                        ->signing_date,

                'retention_required' =>
                    $procurementContract
                        ->retention_required
                    ??
                    false,

                'retention_percentage' =>
                    $procurementContract
                        ->retention_percentage
                    ??
                    0,

                'advance_payment_required' =>
                    false,

                'advance_payment_amount' =>
                    0,

                'performance_security_required' =>
                    $procurementContract
                        ->performance_security_required
                    ??
                    false,

                'performance_security_amount' =>
                    $procurementContract
                        ->performance_security_amount
                    ??
                    0,

                'status' =>
                    $procurementContract
                        ->status
                    ??
                    'Draft',

                'responsible_user_id' =>
                    $procurementContract
                        ->responsible_user_id,

                'scope_of_work' =>
                    $procurementContract
                        ->scope_of_work,

                'terms_and_conditions' =>
                    $procurementContract
                        ->terms_and_conditions,

                'special_conditions' =>
                    $procurementContract
                        ->special_conditions,

                'remarks' =>
                    $procurementContract
                        ->remarks,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);


            $created++;
        }


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.index',
                $project
            )
            ->with(
                'success',
                "{$created} procurement contract(s) added to Contract Management. {$existing} already existed."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Contract Management Code
    |--------------------------------------------------------------------------
    */

    protected function generateContractCode(): string
    {
        $lastId =
            ContractManagementContract::max('id')
            ??
            0;


        return 'CM-CON-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}