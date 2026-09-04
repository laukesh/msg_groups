<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionOtherCost;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractPayment;
use App\Models\ProjectBudget;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use App\Models\ConstructionVariation;
class ConstructionCostControlController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Latest Approved Budget
        |--------------------------------------------------------------------------
        */

        $budget = ProjectBudget::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->orderByDesc(
                'version_number'
            )
            ->orderByDesc(
                'id'
            )
            ->first();


        $approvedBudget = $budget
            ? (float) $budget->total_budget
            : 0.0;


        /*
        |--------------------------------------------------------------------------
        | Project Contracts
        |--------------------------------------------------------------------------
        |
        | Project
        |   ↓
        | Procurement Package
        |   ↓
        | Tender
        |   ↓
        | Contract
        |
        */

        $contracts = ProcurementContract::query()
            ->with([
                'tender.package',
            ])
            ->whereHas(
                'tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );

                }
            )
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Committed Contract Amount
        |--------------------------------------------------------------------------
        |
        | Draft / Submitted contracts should not affect
        | financial commitment.
        |
        */

        $committedContracts = $contracts->filter(
            function ($contract) {

                return in_array(
                    $contract->status,
                    [
                        'Approved',
                        'Active',
                        'Completed',
                        'Closed',
                    ],
                    true
                );

            }
        );


        $committedAmount = (float) $committedContracts->sum(
            'contract_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Contract IDs
        |--------------------------------------------------------------------------
        */

        $contractIds = $contracts->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        |
        | Only financially valid invoices are included.
        |
        */

        $invoices = ProcurementContractInvoice::query()
            ->whereIn(
                'procurement_contract_id',
                $contractIds
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Partially Paid',
                    'Paid',
                ]
            )
            ->with([
                'contract',
                'milestone',
            ])
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get();


        $invoicedAmount = (float) $invoices->sum(
            'net_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Processed Payments
        |--------------------------------------------------------------------------
        |
        | Only Processed payments count as actual paid cost.
        |
        */

        $payments = ProcurementContractPayment::query()
            ->whereIn(
                'procurement_contract_id',
                $contractIds
            )
            ->where(
                'status',
                'Processed'
            )
            ->with([
                'contract',
                'invoice',
                'milestone',
            ])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();


        $paidAmount = (float) $payments->sum(
            'amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Outstanding Invoice Amount
        |--------------------------------------------------------------------------
        |
        | Outstanding = Invoiced - Processed Payments
        |
        */

        $outstandingAmount = max(
            0,
            $invoicedAmount - $paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | Other Approved Construction Costs
        |--------------------------------------------------------------------------
        */

        $otherCosts = ConstructionOtherCost::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'workOrder',
            ])
            ->orderByDesc(
                'cost_date'
            )
            ->orderByDesc(
                'id'
            )
            ->get();


        $otherCostAmount = (float) $otherCosts->sum(
            'amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Approved Construction Variations
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Only Approved variations affect financial commitment.
        |
        */

        $variations = ConstructionVariation::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'workOrder',
                'contract',
            ])
            ->orderByDesc(
                'variation_date'
            )
            ->orderByDesc(
                'id'
            )
            ->get();


        $approvedVariationAmount =
            (float) $variations->sum(
                'amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Total Construction Commitment
        |--------------------------------------------------------------------------
        |
        | Contracted Amount
        | + Approved Variations
        | + Approved Other Costs
        |
        */

        $totalCommittedCost =
            $committedAmount
            +
            $approvedVariationAmount
            +
            $otherCostAmount;


        /*
        |--------------------------------------------------------------------------
        | Revised Commitment
        |--------------------------------------------------------------------------
        */

        $revisedCommitment =
            $totalCommittedCost;


        /*
        |--------------------------------------------------------------------------
        | Remaining Budget
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Keep negative values.
        |
        | Example:
        |
        | Budget       = 10,00,000
        | Commitment   = 11,00,000
        |
        | Remaining    = -1,00,000
        |
        | This tells us there is an overrun.
        |
        */

        $remainingBudget =
            $approvedBudget
            -
            $revisedCommitment;


        /*
        |--------------------------------------------------------------------------
        | Forecast / Estimate At Completion
        |--------------------------------------------------------------------------
        |
        | Current forecast is based on approved commitments.
        |
        */

        $estimatedAtCompletion =
            $revisedCommitment;


        /*
        |--------------------------------------------------------------------------
        | Forecast Variance
        |--------------------------------------------------------------------------
        |
        | Positive = Under Budget
        | Negative = Over Budget
        | Zero     = On Budget
        |
        */

        $forecastVariance =
            $approvedBudget
            -
            $estimatedAtCompletion;


        /*
        |--------------------------------------------------------------------------
        | Forecast Status
        |--------------------------------------------------------------------------
        */

        if ($forecastVariance > 0) {

            $forecastStatus =
                'Under Budget';

        } elseif ($forecastVariance < 0) {

            $forecastStatus =
                'Over Budget';

        } else {

            $forecastStatus =
                'On Budget';
        }


        /*
        |--------------------------------------------------------------------------
        | Budget Utilization
        |--------------------------------------------------------------------------
        |
        | Revised Commitment / Approved Budget
        |
        */

        $budgetUtilization = 0.0;

        if ($approvedBudget > 0) {

            $budgetUtilization =
                (
                    $revisedCommitment
                    /
                    $approvedBudget
                ) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | Invoice Utilization
        |--------------------------------------------------------------------------
        |
        | Invoiced / Approved Budget
        |
        */

        $invoiceUtilization = 0.0;

        if ($approvedBudget > 0) {

            $invoiceUtilization =
                (
                    $invoicedAmount
                    /
                    $approvedBudget
                ) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Utilization
        |--------------------------------------------------------------------------
        |
        | Paid / Approved Budget
        |
        */

        $paymentUtilization = 0.0;

        if ($approvedBudget > 0) {

            $paymentUtilization =
                (
                    $paidAmount
                    /
                    $approvedBudget
                ) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Progress Against Invoices
        |--------------------------------------------------------------------------
        |
        | This is different from payment utilization.
        |
        | Example:
        |
        | Invoiced = 5,00,000
        | Paid     = 4,00,000
        |
        | Payment Progress = 80%
        |
        */

        $invoicePaymentProgress = 0.0;

        if ($invoicedAmount > 0) {

            $invoicePaymentProgress =
                (
                    $paidAmount
                    /
                    $invoicedAmount
                ) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | Remaining Budget Percentage
        |--------------------------------------------------------------------------
        |
        | Calculate this AFTER remainingBudget.
        |
        | If project is over budget, return 0 instead
        | of displaying a negative remaining percentage.
        |
        */

        $remainingBudgetPercentage = 0.0;

        if ($approvedBudget > 0) {

            $remainingBudgetPercentage =
                max(
                    0,
                    (
                        $remainingBudget
                        /
                        $approvedBudget
                    ) * 100
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Contract Summary
        |--------------------------------------------------------------------------
        */

        $contractSummary =
            $this->buildContractSummary(
                $contracts,
                $invoices,
                $payments
            );


        /*
        |--------------------------------------------------------------------------
        | Cost Type Summary
        |--------------------------------------------------------------------------
        */

        $otherCostSummary =
            $otherCosts
                ->groupBy(
                    'cost_type'
                )
                ->map(
                    function ($items) {

                        return [

                            'count' =>
                                $items->count(),

                            'amount' =>
                                (float) $items->sum(
                                    'amount'
                                ),

                        ];
                    }
                )
                ->sortByDesc(
                    'amount'
                );


        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            /*
            |----------------------------------------------------------------------
            | Budget
            |----------------------------------------------------------------------
            */

            'approved_budget' =>
                $approvedBudget,


            /*
            |----------------------------------------------------------------------
            | Contract
            |----------------------------------------------------------------------
            */

            'contracted' =>
                $committedAmount,


            /*
            |----------------------------------------------------------------------
            | Variations
            |----------------------------------------------------------------------
            */

            'variations' =>
                $approvedVariationAmount,


            /*
            |----------------------------------------------------------------------
            | Other Costs
            |----------------------------------------------------------------------
            */

            'other_costs' =>
                $otherCostAmount,


            /*
            |----------------------------------------------------------------------
            | Total / Revised Commitment
            |----------------------------------------------------------------------
            */

            'total_committed' =>
                $totalCommittedCost,

            'revised_commitment' =>
                $revisedCommitment,


            /*
            |----------------------------------------------------------------------
            | Invoice / Payment
            |----------------------------------------------------------------------
            */

            'invoiced' =>
                $invoicedAmount,

            'paid' =>
                $paidAmount,

            'outstanding' =>
                $outstandingAmount,


            /*
            |----------------------------------------------------------------------
            | Budget Position
            |----------------------------------------------------------------------
            */

            'remaining_budget' =>
                $remainingBudget,

            'budget_utilization' =>
                $budgetUtilization,

            'remaining_budget_percentage' =>
                $remainingBudgetPercentage,


            /*
            |----------------------------------------------------------------------
            | Invoice / Payment Utilization
            |----------------------------------------------------------------------
            */

            'invoice_utilization' =>
                $invoiceUtilization,

            'payment_utilization' =>
                $paymentUtilization,

            'invoice_payment_progress' =>
                $invoicePaymentProgress,


            /*
            |----------------------------------------------------------------------
            | Forecast
            |----------------------------------------------------------------------
            */

            'estimated_at_completion' =>
                $estimatedAtCompletion,

            'forecast_variance' =>
                $forecastVariance,

            'forecast_status' =>
                $forecastStatus,

        ];


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'construction.cost-control.index',
            compact(
                'project',
                'budget',
                'contracts',
                'invoices',
                'payments',
                'otherCosts',
                'contractSummary',
                'otherCostSummary',
                'summary',
                'variations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONTRACT SUMMARY
    |--------------------------------------------------------------------------
    */

    protected function buildContractSummary(
        Collection $contracts,
        Collection $invoices,
        Collection $payments
    ): Collection {

        $invoiceByContract =
            $invoices
                ->groupBy(
                    'procurement_contract_id'
                );


        $paymentByContract =
            $payments
                ->groupBy(
                    'procurement_contract_id'
                );


        return $contracts
            ->map(
                function ($contract) use (
                    $invoiceByContract,
                    $paymentByContract
                ) {

                    $contractInvoices =
                        $invoiceByContract->get(
                            $contract->id,
                            collect()
                        );


                    $contractPayments =
                        $paymentByContract->get(
                            $contract->id,
                            collect()
                        );


                    $committed =
                        in_array(
                            $contract->status,
                            [
                                'Approved',
                                'Active',
                                'Completed',
                                'Closed',
                            ],
                            true
                        )
                        ?
                        (float)
                        $contract->contract_amount
                        :
                        0;


                    $invoiced =
                        (float)
                        $contractInvoices->sum(
                            'net_amount'
                        );


                    $paid =
                        (float)
                        $contractPayments->sum(
                            'amount'
                        );


                    return [

                        'contract' =>
                            $contract,

                        'committed' =>
                            $committed,

                        'invoiced' =>
                            $invoiced,

                        'paid' =>
                            $paid,

                        'outstanding' =>
                            max(
                                0,
                                $invoiced
                                -
                                $paid
                            ),

                        'remaining_contract' =>
                            max(
                                0,
                                $committed
                                -
                                $invoiced
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'committed'
            )
            ->values();
    }

    public function contracts(Project $project): View
    {
        $contracts = ProcurementContract::query()
            ->with([
                'tender.package',
            ])
            ->whereHas(
                'tender.package',
                function ($query) use ($project) {
                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->orderByDesc('id')
            ->get();


        return view(
            'construction.cost-control.contracts',
            compact(
                'project',
                'contracts'
            )
        );
    }

    public function variations(Project $project): View
    {
        $variations = ConstructionVariation::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'contract',
                'workOrder',
            ])
            ->orderByDesc('variation_date')
            ->orderByDesc('id')
            ->get();


        return view(
            'construction.cost-control.variations',
            compact(
                'project',
                'variations'
            )
        );
    }

    public function invoices(Project $project): View
    {
        $invoices = ProcurementContractInvoice::query()
            ->whereHas(
                'contract.tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Partially Paid',
                    'Paid',
                ]
            )
            ->with([
                'contract',
                'milestone',
            ])
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get();


        return view(
            'construction.cost-control.invoices',
            compact(
                'project',
                'invoices'
            )
        );
    }

    public function payments(Project $project): View
    {
        $payments = ProcurementContractPayment::query()
            ->whereHas(
                'contract.tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->where(
                'status',
                'Processed'
            )
            ->with([
                'contract',
                'invoice',
                'milestone',
            ])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();


        return view(
            'construction.cost-control.payments',
            compact(
                'project',
                'payments'
            )
        );
    }

    public function otherCosts(Project $project): View
    {
        $otherCosts = ConstructionOtherCost::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'workOrder',
            ])
            ->orderByDesc('cost_date')
            ->orderByDesc('id')
            ->get();


        return view(
            'construction.cost-control.other-costs',
            compact(
                'project',
                'otherCosts'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT REPORT
    |--------------------------------------------------------------------------
    */

    public function report(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Approved Budget
        |--------------------------------------------------------------------------
        */

        $budget = ProjectBudget::query()
            ->where('project_id', $project->id)
            ->where(
                'status',
                'Approved'
            )
            ->orderByDesc('version_number')
            ->first();


        $approvedBudget =
            (float) (
                $budget?->total_budget
                ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | Contracts
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::query()
            ->whereHas(
                'tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->get();


        $contractedAmount =
            (float) $contracts->sum(
                'contract_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Approved Variations
        |--------------------------------------------------------------------------
        */

        $variations = ConstructionVariation::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->get();


        $variationAmount =
            (float) $variations->sum(
                'amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Approved Other Costs
        |--------------------------------------------------------------------------
        */

        $otherCosts = ConstructionOtherCost::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->get();


        $otherCostAmount =
            (float) $otherCosts->sum(
                'amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Revised Commitment
        |--------------------------------------------------------------------------
        */

        $revisedCommitment =
            $contractedAmount
            +
            $variationAmount
            +
            $otherCostAmount;


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = ProcurementContractInvoice::query()
            ->whereHas(
                'contract.tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->whereIn(
                'status',
                [
                    'Approved',
                    'Partially Paid',
                    'Paid',
                ]
            )
            ->get();


        $invoicedAmount =
            (float) $invoices->sum(
                'net_amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Processed Payments
        |--------------------------------------------------------------------------
        */

        $payments = ProcurementContractPayment::query()
            ->whereHas(
                'contract.tender.package',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->where(
                'status',
                'Processed'
            )
            ->get();


        $paidAmount =
            (float) $payments->sum(
                'amount'
            );


        /*
        |--------------------------------------------------------------------------
        | Outstanding
        |--------------------------------------------------------------------------
        */

        $outstandingAmount =
            max(
                0,
                $invoicedAmount
                -
                $paidAmount
            );


        /*
        |--------------------------------------------------------------------------
        | Remaining Budget
        |--------------------------------------------------------------------------
        */

        $remainingBudget =
            $approvedBudget
            -
            $revisedCommitment;


        /*
        |--------------------------------------------------------------------------
        | Budget Utilization
        |--------------------------------------------------------------------------
        */

        $budgetUtilization =
            $approvedBudget > 0
                ? (
                    $revisedCommitment
                    /
                    $approvedBudget
                ) * 100
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Forecast
        |--------------------------------------------------------------------------
        */

        $estimatedAtCompletion =
            $revisedCommitment;


        $forecastVariance =
            $approvedBudget
            -
            $estimatedAtCompletion;


        if ($forecastVariance > 0) {

            $forecastStatus =
                'Under Budget';

        } elseif ($forecastVariance < 0) {

            $forecastStatus =
                'Over Budget';

        } else {

            $forecastStatus =
                'On Budget';
        }


        /*
        |--------------------------------------------------------------------------
        | Report Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'approved_budget' =>
                $approvedBudget,

            'contracted' =>
                $contractedAmount,

            'variations' =>
                $variationAmount,

            'other_costs' =>
                $otherCostAmount,

            'revised_commitment' =>
                $revisedCommitment,

            'invoiced' =>
                $invoicedAmount,

            'paid' =>
                $paidAmount,

            'outstanding' =>
                $outstandingAmount,

            'remaining_budget' =>
                $remainingBudget,

            'budget_utilization' =>
                $budgetUtilization,

            'estimated_at_completion' =>
                $estimatedAtCompletion,

            'forecast_variance' =>
                $forecastVariance,

            'forecast_status' =>
                $forecastStatus,
        ];


        return view(
            'construction.cost-control.report',
            compact(
                'project',
                'budget',
                'summary',
                'contracts',
                'variations',
                'otherCosts',
                'invoices',
                'payments'
            )
        );
    }
}