<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseProposal;
use App\Models\LeaseAgreement;
use App\Models\LeaseRenewal;
use App\Models\LeaseEscalation;
use App\Models\LeaseTermination;
use App\Models\LeaseHistory;
use Carbon\Carbon;

class LeaseDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Proposals
        |--------------------------------------------------------------------------
        */

        $totalProposals = LeaseProposal::count();

        $pendingProposals = LeaseProposal::where(
            'proposal_status',
            'Pending Approval'
        )->count();

        $approvedProposals = LeaseProposal::where(
            'proposal_status',
            'Approved'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Agreements
        |--------------------------------------------------------------------------
        */

        $totalAgreements = LeaseAgreement::count();

        $activeAgreements = LeaseAgreement::where(
            'agreement_status',
            'Active'
        )->count();

        $terminatedAgreements = LeaseAgreement::where(
            'agreement_status',
            'Terminated'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Expiring Agreements
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $ninetyDays = Carbon::today()->addDays(90);

        $expiringAgreements = LeaseAgreement::with('tenant')
            ->where(
                'agreement_status',
                'Active'
            )
            ->whereDate(
                'lease_end_date',
                '>=',
                $today
            )
            ->whereDate(
                'lease_end_date',
                '<=',
                $ninetyDays
            )
            ->orderBy('lease_end_date')
            ->limit(10)
            ->get();


        $expiringCount = LeaseAgreement::where(
            'agreement_status',
            'Active'
        )
        ->whereDate(
            'lease_end_date',
            '>=',
            $today
        )
        ->whereDate(
            'lease_end_date',
            '<=',
            $ninetyDays
        )
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Renewals
        |--------------------------------------------------------------------------
        */

        $pendingRenewals = LeaseRenewal::where(
            'approval_status',
            'Pending'
        )->count();

        $approvedRenewals = LeaseRenewal::where(
            'approval_status',
            'Approved'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Escalations
        |--------------------------------------------------------------------------
        */

        $pendingEscalations = LeaseEscalation::where(
            'status',
            'Pending'
        )->count();

        $appliedEscalations = LeaseEscalation::where(
            'status',
            'Applied'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Terminations
        |--------------------------------------------------------------------------
        */

        $pendingTerminations = LeaseTermination::where(
            'termination_status',
            'Pending Approval'
        )->count();

        $approvedTerminations = LeaseTermination::where(
            'termination_status',
            'Approved'
        )->count();

        $completedTerminations = LeaseTermination::where(
            'termination_status',
            'Completed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Activities
        |--------------------------------------------------------------------------
        */

        $recentActivities = LeaseHistory::with([
            'agreement',
            'performer'
        ])
        ->orderByDesc('activity_date')
        ->limit(10)
        ->get();


        return view(
            'admin.leasing.dashboard.dashboard',
            compact(
                'totalProposals',
                'pendingProposals',
                'approvedProposals',

                'totalAgreements',
                'activeAgreements',
                'terminatedAgreements',

                'expiringCount',
                'expiringAgreements',

                'pendingRenewals',
                'approvedRenewals',

                'pendingEscalations',
                'appliedEscalations',

                'pendingTerminations',
                'approvedTerminations',
                'completedTerminations',

                'recentActivities'
            )
        );
    }
}