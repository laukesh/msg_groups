<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPackage;
use App\Models\ProcurementTender;
use App\Models\ProcurementAward;
use App\Models\ProcurementContract;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementMilestoneProgress;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractPayment;
use App\Models\ProcurementDelivery;
use App\Models\ProcurementMaterialTracking;

class ProcurementPerformanceController extends Controller
{
    /**
     * Procurement Performance Dashboard
     */
    public function index(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Procurement Plans
        |--------------------------------------------------------------------------
        */

        $plans = ProcurementPlan::where(
            'project_id',
            $project->id
        );

        $totalPlans = (clone $plans)->count();

        $approvedPlans = (clone $plans)
            ->where('status', 'Approved')
            ->count();

        $pendingPlans = (clone $plans)
            ->whereIn('status', [
                'Draft',
                'Submitted',
                'Under Review',
                'Pending Approval',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Procurement Packages
        |--------------------------------------------------------------------------
        */

        $packages = ProcurementPackage::whereHas(
            'procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalPackages = (clone $packages)->count();

        $packageValue = (clone $packages)->sum(
            'estimated_value'
        );


        /*
        |--------------------------------------------------------------------------
        | Tenders
        |--------------------------------------------------------------------------
        */

        $tenders = ProcurementTender::whereHas(
            'package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalTenders = (clone $tenders)->count();

        $draftTenders = (clone $tenders)
            ->where('status', 'Draft')
            ->count();

        $activeTenders = (clone $tenders)
            ->whereIn('status', [
                'Published',
                'Open',
                'Under Evaluation',
                'Technical Evaluation',
                'Commercial Evaluation',
            ])
            ->count();

        $awardedTenders = (clone $tenders)
            ->whereIn('status', [
                'Awarded',
                'Completed',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Awards
        |--------------------------------------------------------------------------
        */

        $awards = ProcurementAward::whereHas(
            'tender.package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalAwards = (clone $awards)->count();

        $totalAwardValue = (clone $awards)->sum('awarded_amount');


        /*
        |--------------------------------------------------------------------------
        | Contracts
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::whereHas(
            'tender.package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalContracts = (clone $contracts)->count();

        $activeContracts = (clone $contracts)
            ->whereIn('status', [
                'Active',
                'Activated',
                'In Progress',
            ])
            ->count();

        $completedContracts = (clone $contracts)
            ->whereIn('status', [
                'Completed',
                'Closed',
            ])
            ->count();

        $contractValue = (clone $contracts)->sum(
            'contract_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Purchase Orders
        |--------------------------------------------------------------------------
        */

        $purchaseOrders = ProcurementPurchaseOrder::where(
            'project_id',
            $project->id
        );

        $totalPurchaseOrders = (clone $purchaseOrders)
            ->count();

        $purchaseOrderValue = (clone $purchaseOrders)
            ->sum('total_amount');

        $issuedPurchaseOrders = (clone $purchaseOrders)
            ->where('status', 'Issued')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        */

        $milestones = ProcurementContractMilestone::whereHas(
            'contract.tender.package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalMilestones = (clone $milestones)->count();

        $completedMilestones = (clone $milestones)
            ->whereIn('status', [
                'Completed',
                'Complete',
            ])
            ->count();

        $pendingMilestones = (clone $milestones)
            ->whereIn('status', [
                'Pending',
                'In Progress',
            ])
            ->count();

        $milestoneProgress = (clone $milestones)
            ->avg('progress_percentage');

        $milestoneProgress = round(
            (float) ($milestoneProgress ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = ProcurementContractInvoice::whereHas(
            'contract.tender.package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalInvoices = (clone $invoices)->count();

        $invoicedAmount = (clone $invoices)
            ->sum('net_amount');


        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */

        $payments = ProcurementContractPayment::whereHas(
            'contract.tender.package.procurementPlan',
            function ($query) use ($project) {
                $query->where('project_id', $project->id);
            }
        );

        $totalPayments = (clone $payments)->count();

        $paidAmount = (clone $payments)
            ->whereIn('status', [
                'Approved',
                'Processed',
                'Paid',
            ])
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Outstanding Amount
        |--------------------------------------------------------------------------
        */

        $outstandingAmount = max(
            0,
            (float) $invoicedAmount -
            (float) $paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | Deliveries
        |--------------------------------------------------------------------------
        */

        $deliveries = ProcurementDelivery::whereHas(
            'purchaseOrder',
            function ($query) use ($project) {
                $query->where(
                    'project_id',
                    $project->id
                );
            }
        );

        $totalDeliveries = (clone $deliveries)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Material Tracking
        |--------------------------------------------------------------------------
        */

        $materialTrackings = ProcurementMaterialTracking::where(
            'project_id',
            $project->id
        );

        $totalMaterialTrackings = (clone $materialTrackings)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Procurement Efficiency
        |--------------------------------------------------------------------------
        */

        $tenderAwardRate = $totalTenders > 0
            ? round(
                ($awardedTenders / $totalTenders) * 100,
                2
            )
            : 0;

        $contractCompletionRate = $totalContracts > 0
            ? round(
                ($completedContracts / $totalContracts) * 100,
                2
            )
            : 0;

        $milestoneCompletionRate = $totalMilestones > 0
            ? round(
                ($completedMilestones / $totalMilestones) * 100,
                2
            )
            : 0;

        $paymentRate = $invoicedAmount > 0
            ? round(
                ($paidAmount / $invoicedAmount) * 100,
                2
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Recent Contracts
        |--------------------------------------------------------------------------
        */

        $recentContracts = (clone $contracts)
            ->latest('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Purchase Orders
        |--------------------------------------------------------------------------
        */

        $recentPurchaseOrders = (clone $purchaseOrders)
            ->latest('created_at')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'procurement.performance.index',
            compact(

                'project',

                'totalPlans',
                'approvedPlans',
                'pendingPlans',

                'totalPackages',
                'packageValue',

                'totalTenders',
                'draftTenders',
                'activeTenders',
                'awardedTenders',

                'totalAwards',
                'totalAwardValue',

                'totalContracts',
                'activeContracts',
                'completedContracts',
                'contractValue',

                'totalPurchaseOrders',
                'purchaseOrderValue',
                'issuedPurchaseOrders',

                'totalMilestones',
                'completedMilestones',
                'pendingMilestones',
                'milestoneProgress',

                'totalInvoices',
                'invoicedAmount',

                'totalPayments',
                'paidAmount',
                'outstandingAmount',

                'totalDeliveries',
                'totalMaterialTrackings',

                'tenderAwardRate',
                'contractCompletionRate',
                'milestoneCompletionRate',
                'paymentRate',

                'recentContracts',
                'recentPurchaseOrders'
            )
        );
    }
}