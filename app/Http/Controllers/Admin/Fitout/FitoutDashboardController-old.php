<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutRequest;
use App\Models\FitoutStage;
use App\Models\FitoutDocument;
use App\Models\FitoutApproval;
use App\Models\Inspection;
use App\Models\SnagList;
use App\Models\Handover;
use Illuminate\Http\Request;

class FitoutDashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Date
        |--------------------------------------------------------------------------
        */

        $today = now()->toDateString();


        /*
        |--------------------------------------------------------------------------
        | Fit-Out Request KPIs
        |--------------------------------------------------------------------------
        */

        $totalFitouts = FitoutRequest::count();

        $draftFitouts = FitoutRequest::where(
            'fitout_status',
            'Draft'
        )->count();

        $underReviewFitouts = FitoutRequest::where(
            'fitout_status',
            'Under Review'
        )->count();

        $approvedFitouts = FitoutRequest::where(
            'fitout_status',
            'Approved'
        )->count();

        $inProgressFitouts = FitoutRequest::where(
            'fitout_status',
            'In Progress'
        )->count();

        $completedFitouts = FitoutRequest::where(
            'fitout_status',
            'Completed'
        )->count();

        $closedFitouts = FitoutRequest::where(
            'fitout_status',
            'Closed'
        )->count();

        $rejectedFitouts = FitoutRequest::where(
            'fitout_status',
            'Rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Delayed Fit-Outs
        |--------------------------------------------------------------------------
        |
        | Proposed end date has passed and request is not completed/closed.
        |
        */

        $delayedFitouts = FitoutRequest::whereDate(
            'proposed_end_date',
            '<',
            $today
        )
            ->whereNotIn(
                'fitout_status',
                [
                    'Completed',
                    'Closed',
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Stage KPIs
        |--------------------------------------------------------------------------
        */

        $totalStages = FitoutStage::count();

        $pendingStages = FitoutStage::where(
            'stage_status',
            'Pending'
        )->count();

        $inProgressStages = FitoutStage::where(
            'stage_status',
            'In Progress'
        )->count();

        $completedStages = FitoutStage::where(
            'stage_status',
            'Completed'
        )->count();

        $onHoldStages = FitoutStage::where(
            'stage_status',
            'On Hold'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Document KPIs
        |--------------------------------------------------------------------------
        */

        $pendingDocuments = FitoutDocument::where(
            'approval_status',
            'Pending'
        )->count();

        $underReviewDocuments = FitoutDocument::where(
            'approval_status',
            'Under Review'
        )->count();

        $approvedDocuments = FitoutDocument::where(
            'approval_status',
            'Approved'
        )->count();

        $rejectedDocuments = FitoutDocument::where(
            'approval_status',
            'Rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Approval KPIs
        |--------------------------------------------------------------------------
        */

        $pendingApprovals = FitoutApproval::where(
            'approval_status',
            'Pending'
        )->count();

        $approvedApprovals = FitoutApproval::where(
            'approval_status',
            'Approved'
        )->count();

        $rejectedApprovals = FitoutApproval::where(
            'approval_status',
            'Rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Inspection KPIs
        |--------------------------------------------------------------------------
        */

        $scheduledInspections = Inspection::where(
            'status',
            'Scheduled'
        )->count();

        $inProgressInspections = Inspection::where(
            'status',
            'In Progress'
        )->count();

        $completedInspections = Inspection::where(
            'status',
            'Completed'
        )->count();

        $failedInspections = Inspection::where(
            'result',
            'Failed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Snag KPIs
        |--------------------------------------------------------------------------
        */

        $openSnags = SnagList::whereIn(
            'status',
            [
                'Open',
                'Assigned',
                'In Progress',
                'Reopened',
            ]
        )->count();

        $criticalSnags = SnagList::where(
            'priority',
            'Critical'
        )
            ->whereNotIn(
                'status',
                [
                    'Closed',
                ]
            )
            ->count();

        $highSnags = SnagList::where(
            'priority',
            'High'
        )
            ->whereNotIn(
                'status',
                [
                    'Closed',
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Handover KPIs
        |--------------------------------------------------------------------------
        */

        $pendingHandovers = Handover::where(
            'status',
            'Pending'
        )->count();

        $scheduledHandovers = Handover::where(
            'status',
            'Scheduled'
        )->count();

        $inProgressHandovers = Handover::where(
            'status',
            'In Progress'
        )->count();

        $acceptedHandovers = Handover::where(
            'status',
            'Accepted'
        )->count();

        $completedHandovers = Handover::where(
            'status',
            'Completed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Current Fit-Out List
        |--------------------------------------------------------------------------
        */

        $fitouts = FitoutRequest::with([
            'unit',
            'tenant',
            'contractor',
        ])
            ->withCount([
                'stages',
                'documents',
                'inspections',
                'snags',
                'handovers',
            ])
            ->latest('id')
            ->paginate(20);


        /*
        |--------------------------------------------------------------------------
        | Recent Stages
        |--------------------------------------------------------------------------
        */

        $recentStages = FitoutStage::with([
            'fitoutRequest',
            'contractor',
        ])
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Upcoming Inspections
        |--------------------------------------------------------------------------
        */

        $upcomingInspections = Inspection::with([
            'fitoutRequest',
            'fitoutStage',
        ])
            ->whereDate(
                'scheduled_date',
                '>=',
                $today
            )
            ->whereNotIn(
                'status',
                [
                    'Completed',
                    'Cancelled',
                ]
            )
            ->orderBy(
                'scheduled_date'
            )
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Open Snags
        |--------------------------------------------------------------------------
        */

        $recentSnags = SnagList::with([
            'fitoutRequest',
            'fitoutStage',
        ])
            ->whereNotIn(
                'status',
                [
                    'Closed',
                ]
            )
            ->orderByRaw("
                CASE priority
                    WHEN 'Critical' THEN 1
                    WHEN 'High' THEN 2
                    WHEN 'Medium' THEN 3
                    WHEN 'Low' THEN 4
                    ELSE 5
                END
            ")
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Pending Approvals
        |--------------------------------------------------------------------------
        */

        $approvalQueue = FitoutApproval::with([
            'fitoutRequest',
        ])
            ->where(
                'approval_status',
                'Pending'
            )
            ->orderBy(
                'approval_level'
            )
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Handovers
        |--------------------------------------------------------------------------
        */

        $recentHandovers = Handover::with([
            'fitoutRequest',
            'unit',
            'tenant',
        ])
            ->latest('id')
            ->limit(10)
            ->get();


        return view(
            'admin.fitout.dashboard',
            compact(
                'totalFitouts',
                'draftFitouts',
                'underReviewFitouts',
                'approvedFitouts',
                'inProgressFitouts',
                'completedFitouts',
                'closedFitouts',
                'rejectedFitouts',
                'delayedFitouts',

                'totalStages',
                'pendingStages',
                'inProgressStages',
                'completedStages',
                'onHoldStages',

                'pendingDocuments',
                'underReviewDocuments',
                'approvedDocuments',
                'rejectedDocuments',

                'pendingApprovals',
                'approvedApprovals',
                'rejectedApprovals',

                'scheduledInspections',
                'inProgressInspections',
                'completedInspections',
                'failedInspections',

                'openSnags',
                'criticalSnags',
                'highSnags',

                'pendingHandovers',
                'scheduledHandovers',
                'inProgressHandovers',
                'acceptedHandovers',
                'completedHandovers',

                'fitouts',
                'recentStages',
                'upcomingInspections',
                'recentSnags',
                'approvalQueue',
                'recentHandovers'
            )
        );
    }
}