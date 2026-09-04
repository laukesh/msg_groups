<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementPackage;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractPayment;
use Illuminate\View\View;

class ConstructionDashboardController extends Controller
{
    public function index(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Procurement Packages
        |--------------------------------------------------------------------------
        */

        $procurementPackages = ProcurementPackage::query()
            ->whereHas(
                'procurementPlan',
                function ($query) use ($project) {
                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Procurement Contracts
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::query()
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


        /*
        |--------------------------------------------------------------------------
        | Contract Statistics
        |--------------------------------------------------------------------------
        */

        $totalContracts =
            $contracts->count();


        $activeContracts =
            $contracts->filter(
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
            )->count();


        $completedContracts =
            $contracts->filter(
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
            )->count();


        /*
        |--------------------------------------------------------------------------
        | Contract Value
        |--------------------------------------------------------------------------
        */

        $totalContractValue =
            (float) $contracts->sum(
                'contract_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        */

        $invoiceQuery =
            ProcurementContractInvoice::query()
                ->whereHas(
                    'contract',
                    function ($query) use ($project) {

                        $query->whereHas(
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
                );


        $totalInvoiceAmount =
            (float) $invoiceQuery
                ->sum('net_amount');


        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $paymentQuery =
            ProcurementContractPayment::query()
                ->whereHas(
                    'contract',
                    function ($query) use ($project) {

                        $query->whereHas(
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
                );


        $totalPaidAmount =
            (float) $paymentQuery
                ->where(
                    'status',
                    'Processed'
                )
                ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Outstanding
        |--------------------------------------------------------------------------
        */

        $outstandingAmount =
            max(
                0,
                $totalInvoiceAmount
                -
                $totalPaidAmount
            );


        /*
        |--------------------------------------------------------------------------
        | Payment Statistics
        |--------------------------------------------------------------------------
        */

        $pendingPayments =
            (clone $paymentQuery)
                ->whereIn(
                    'status',
                    [
                        'Submitted',
                        'Approved',
                    ]
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Procurement Packages
        |--------------------------------------------------------------------------
        */

        $totalPackages =
            $procurementPackages->count();


        $activePackages =
            $procurementPackages
                ->whereNotIn(
                    'status',
                    [
                        'Completed',
                        'Closed',
                        'Cancelled',
                    ]
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Construction Progress
        |
        | Progress module will be connected later.
        |--------------------------------------------------------------------------
        */

        $overallProgress = 0;


        /*
        |--------------------------------------------------------------------------
        | Schedule
        |
        | Schedule module will be connected later.
        |--------------------------------------------------------------------------
        */

        $scheduleStatus = 'Not Started';


        /*
        |--------------------------------------------------------------------------
        | Dashboard Cards
        |--------------------------------------------------------------------------
        */

        $dashboard = [

            'total_packages' =>
                $totalPackages,

            'active_packages' =>
                $activePackages,

            'total_contracts' =>
                $totalContracts,

            'active_contracts' =>
                $activeContracts,

            'completed_contracts' =>
                $completedContracts,

            'total_contract_value' =>
                $totalContractValue,

            'total_invoice_amount' =>
                $totalInvoiceAmount,

            'total_paid_amount' =>
                $totalPaidAmount,

            'outstanding_amount' =>
                $outstandingAmount,

            'pending_payments' =>
                $pendingPayments,

            'overall_progress' =>
                $overallProgress,

            'schedule_status' =>
                $scheduleStatus,
        ];


        return view(
            'construction.dashboard.index',
            compact(
                'project',
                'dashboard',
                'contracts',
                'procurementPackages'
            )
        );
    }
}