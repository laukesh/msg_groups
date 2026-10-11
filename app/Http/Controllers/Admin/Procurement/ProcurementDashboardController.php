<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProcurementDashboardController extends Controller
{
    /**
     * Global Procurement Management page.
     *
     * URL:
     * /admin/procurement
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->input('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $status = $request->input('status');


        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Initially we show ALL projects.
        |
        | We are not filtering projects merely because they have
        | procurement records.
        |
        */

        $projectsQuery = Project::query()
            ->with('land');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $projectsQuery->where(
                function ($query) use ($search) {

                    $query
                        ->where(
                            'project_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'project_code',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'project_name',
                            'like',
                            '%' . $search . '%'
                        );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Project Status
        |--------------------------------------------------------------------------
        */

        if (
            $status !== null &&
            $status !== ''
        ) {

            $projectsQuery->where(
                'project_status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $projects = $projectsQuery
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalProjects = Project::count();

        $activeProjects = Project::where(
            'project_status',
            'Active'
        )->count();

        $completedProjects = Project::where(
            'project_status',
            'Completed'
        )->count();


        return view(
            'procurement.index',
            compact(
                'projects',
                'totalProjects',
                'activeProjects',
                'completedProjects',
                'search',
                'status'
            )
        );
    }


    /**
     * Project-specific Procurement Dashboard.
     *
     * URL:
     * /admin/projects/{project}/procurement
     */
    /**
     * Project-specific Procurement Dashboard.
     *
     * URL:
     * /admin/projects/{project}/procurement
     */
    public function project(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT PLANS
        |--------------------------------------------------------------------------
        */

        $procurementPlanCount = \App\Models\ProcurementPlan::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT PACKAGES
        |--------------------------------------------------------------------------
        */

        $procurementPackageCount = \App\Models\ProcurementPackage::query()
            ->whereHas('procurementPlan', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TENDERS
        |--------------------------------------------------------------------------
        */

        $tenderCount = \App\Models\ProcurementTender::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | CONTRACTS
        |--------------------------------------------------------------------------
        */

        $contractCount = \App\Models\ProcurementContract::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | BIDDERS
        |--------------------------------------------------------------------------
        |
        | Count participating bidders in tenders belonging to this project.
        |
        */

        $bidderCount = \App\Models\ProcurementTenderBidder::query()
            ->where('project_id', $project->id)
            ->distinct('procurement_bidder_id')
            ->count('procurement_bidder_id');


        /*
        |--------------------------------------------------------------------------
        | TENDER SUBMISSIONS
        |--------------------------------------------------------------------------
        */

        $submissionCount = \App\Models\ProcurementTenderSubmission::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TECHNICAL EVALUATIONS
        |--------------------------------------------------------------------------
        */

        $technicalEvaluationCount = \App\Models\ProcurementTechnicalEvaluation::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | COMMERCIAL EVALUATIONS
        |--------------------------------------------------------------------------
        */

        $commercialEvaluationCount = \App\Models\ProcurementCommercialEvaluation::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | BID COMPARISONS
        |--------------------------------------------------------------------------
        */

        $bidComparisonCount = \App\Models\ProcurementBidComparison::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | NEGOTIATIONS
        |--------------------------------------------------------------------------
        */

        $negotiationCount = \App\Models\ProcurementNegotiation::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | AWARDS
        |--------------------------------------------------------------------------
        */

        $awardCount = \App\Models\ProcurementAward::query()
            ->whereHas('tender', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PURCHASE ORDERS
        |--------------------------------------------------------------------------
        */

        $purchaseOrderCount = \App\Models\ProcurementPurchaseOrder::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DELIVERIES
        |--------------------------------------------------------------------------
        */

        $deliveryCount = \App\Models\ProcurementDelivery::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MATERIAL TRACKING
        |--------------------------------------------------------------------------
        */

        $materialTrackingCount = \App\Models\ProcurementMaterialTracking::query()
            ->where('project_id', $project->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | INVOICES
        |--------------------------------------------------------------------------
        */

        $invoiceQuery = \App\Models\ProcurementContractInvoice::query()
            ->where('project_id', $project->id);

        $invoiceCount = (clone $invoiceQuery)->count();

        $totalInvoiceValue = (float) (clone $invoiceQuery)
            ->sum('net_amount');


        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        $paymentQuery = \App\Models\ProcurementContractPayment::query()
            ->where('project_id', $project->id);

        $paymentCount = (clone $paymentQuery)->count();

        $paidAmount = (float) (clone $paymentQuery)
            ->whereIn('status', [
                'Approved',
                'Processed',
                'Paid',
            ])
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | REMAINING PAYMENT
        |--------------------------------------------------------------------------
        */

        $remainingToPay = max(
            0,
            $totalInvoiceValue - $paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT VALUES
        |--------------------------------------------------------------------------
        */

        $contractValue = (float) \App\Models\ProcurementContract::query()
            ->where('project_id', $project->id)
            ->sum('contract_amount');

        $purchaseOrderValue = (float) \App\Models\ProcurementPurchaseOrder::query()
            ->where('project_id', $project->id)
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'procurement.project',
            compact(

                'project',

                // Main counts
                'procurementPlanCount',
                'procurementPackageCount',
                'tenderCount',
                'contractCount',

                // Tender process
                'bidderCount',
                'submissionCount',
                'technicalEvaluationCount',
                'commercialEvaluationCount',
                'bidComparisonCount',
                'negotiationCount',
                'awardCount',

                // Execution
                'purchaseOrderCount',
                'deliveryCount',
                'materialTrackingCount',

                // Finance
                'invoiceCount',
                'paymentCount',
                'totalInvoiceValue',
                'paidAmount',
                'remainingToPay',

                // Values
                'contractValue',
                'purchaseOrderValue'
            )
        );
    }
}