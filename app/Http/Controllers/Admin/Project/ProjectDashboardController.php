<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectRisk;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPackage;
use App\Models\ProcurementTender;
use App\Models\ProcurementAward;
use App\Models\ProcurementContract;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractPayment;
use App\Models\ProcurementContractMilestone;
use Illuminate\Support\Facades\DB;

class ProjectDashboardController extends Controller
{
    public function index(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Budget
        |--------------------------------------------------------------------------
        */

        $budget = ProjectBudget::where(
            'project_id',
            $project->id
        )
            ->where('status', 'Approved')
            ->orderByDesc('version_number')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Procurement Plans
        |--------------------------------------------------------------------------
        */

        $procurementPlans = ProcurementPlan::where(
            'project_id',
            $project->id
        )->get();

        $procurementPlanIds = $procurementPlans->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Procurement Packages
        |--------------------------------------------------------------------------
        |
        | Project
        |   → Procurement Plan
        |       → Procurement Package
        |
        */

        $procurementPackages = ProcurementPackage::whereIn(
            'procurement_plan_id',
            $procurementPlanIds
        )->get();

        $procurementPackageIds = $procurementPackages->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Tenders
        |--------------------------------------------------------------------------
        |
        | Procurement Package
        |   → Procurement Tender
        |
        */

        $tenders = ProcurementTender::whereIn(
            'procurement_package_id',
            $procurementPackageIds
        )->get();

        $tenderIds = $tenders->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Awards
        |--------------------------------------------------------------------------
        */

        $awards = ProcurementAward::whereIn(
            'procurement_tender_id',
            $tenderIds
        )->get();

        $awardIds = $awards->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Contracts
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::whereIn(
            'procurement_award_id',
            $awardIds
        )->get();

        $contractIds = $contracts->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Purchase Orders
        |--------------------------------------------------------------------------
        */

        $purchaseOrders = ProcurementPurchaseOrder::where(
            'project_id',
            $project->id
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        */

        $budgetAmount = $budget
            ? (float) $budget->total_budget
            : 0;

        $contractAmount = (float) $contracts->sum(
            'contract_amount'
        );

        $purchaseOrderAmount = (float) $purchaseOrders->sum(
            'total_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = ProcurementContractInvoice::whereIn(
            'procurement_contract_id',
            $contractIds
        )->get();

        $invoiceAmount = (float) $invoices->sum(
            'net_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $payments = ProcurementContractPayment::whereIn(
            'procurement_contract_id',
            $contractIds
        )->get();

        $paidAmount = (float) $payments
            ->where('status', 'Approved')
            ->sum('amount');


        $outstandingAmount = max(
            0,
            $invoiceAmount - $paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        */

        $milestones = ProcurementContractMilestone::whereIn(
            'procurement_contract_id',
            $contractIds
        )->get();

        $milestoneCount = $milestones->count();

        $completedMilestones = $milestones
            ->where('status', 'Completed')
            ->count();

        $pendingMilestones = $milestones
            ->where('status', 'Pending')
            ->count();

        $inProgressMilestones = $milestones
            ->whereIn('status', [
                'In Progress',
                'Ongoing',
                'Started',
            ])
            ->count();

        $overallProgress = $milestoneCount > 0
            ? round(
                (float) $milestones->avg('progress_percentage'),
                2
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Risks
        |--------------------------------------------------------------------------
        */

        $risks = ProjectRisk::where(
            'project_id',
            $project->id
        )->get();

        $openRisks = $risks
            ->where('status', 'Open')
            ->count();

        $monitoringRisks = $risks
            ->where('status', 'Monitoring')
            ->count();

        $highRisks = $risks
            ->where('risk_level', 'High')
            ->count();

        $criticalRisks = $risks
            ->where('risk_level', 'Critical')
            ->count();

        $overdueRisks = $risks
            ->filter(function ($risk) {

                return $risk->target_date
                    && $risk->target_date->isPast()
                    && !in_array($risk->status, [
                        'Mitigated',
                        'Closed',
                    ]);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Procurement Summary
        |--------------------------------------------------------------------------
        */

        $procurementSummary = [

            'plans' => $procurementPlans->count(),

            'packages' => $procurementPackages->count(),

            'tenders' => $tenders->count(),

            'awards' => $awards->count(),

            'contracts' => $contracts->count(),

            'purchase_orders' => $purchaseOrders->count(),

            'deliveries' => $purchaseOrders
                ->loadCount('deliveries')
                ->sum('deliveries_count'),
        ];


        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        */

        $financialSummary = [

            'budget' => $budgetAmount,

            'contracted' => $contractAmount,

            'purchase_orders' => $purchaseOrderAmount,

            'invoiced' => $invoiceAmount,

            'paid' => $paidAmount,

            'outstanding' => $outstandingAmount,
        ];


        /*
        |--------------------------------------------------------------------------
        | Milestone Summary
        |--------------------------------------------------------------------------
        */

        $milestoneSummary = [

            'total' => $milestoneCount,

            'completed' => $completedMilestones,

            'pending' => $pendingMilestones,

            'in_progress' => $inProgressMilestones,

            'progress' => $overallProgress,
        ];


        /*
        |--------------------------------------------------------------------------
        | Risk Summary
        |--------------------------------------------------------------------------
        */

        $riskSummary = [

            'total' => $risks->count(),

            'open' => $openRisks,

            'monitoring' => $monitoringRisks,

            'high' => $highRisks,

            'critical' => $criticalRisks,

            'overdue' => $overdueRisks,
        ];


        /*
        |--------------------------------------------------------------------------
        | Recent Data
        |--------------------------------------------------------------------------
        */

        $recentInvoices = ProcurementContractInvoice::whereIn(
            'procurement_contract_id',
            $contractIds
        )
            ->latest('id')
            ->limit(5)
            ->get();

        $recentPayments = ProcurementContractPayment::whereIn(
            'procurement_contract_id',
            $contractIds
        )
            ->latest('id')
            ->limit(5)
            ->get();

        $recentPurchaseOrders = ProcurementPurchaseOrder::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'projects.dashboard',
            compact(
                'project',
                'budget',
                'financialSummary',
                'procurementSummary',
                'milestoneSummary',
                'riskSummary',
                'recentInvoices',
                'recentPayments',
                'recentPurchaseOrders'
            )
        );
    }
}