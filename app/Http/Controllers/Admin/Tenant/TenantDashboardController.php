<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\LeaseAgreement;
use App\Models\TenantDocument;
use App\Models\TenantHistory;
use Carbon\Carbon;

class TenantDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Tenant Statistics
        |--------------------------------------------------------------------------
        */

        $tenantTotal = Tenant::count();

        $tenantActive = Tenant::where(
            'status',
            'Active'
        )->count();

        $tenantInactive = Tenant::where(
            'status',
            'Inactive'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Lease Statistics
        |--------------------------------------------------------------------------
        */

        $agreementTotal = LeaseAgreement::count();

        $agreementActive = LeaseAgreement::where(
            'agreement_status',
            'Active'
        )->count();

        $agreementExpired = LeaseAgreement::where(
            'agreement_status',
            'Expired'
        )->count();

        $agreementTerminated = LeaseAgreement::where(
            'agreement_status',
            'Terminated'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Document Statistics
        |--------------------------------------------------------------------------
        */

        $documentTotal = TenantDocument::count();

        $documentPending = TenantDocument::where(
            'verification_status',
            'Pending'
        )->count();

        $documentVerified = TenantDocument::where(
            'verification_status',
            'Verified'
        )->count();

        $documentRejected = TenantDocument::where(
            'verification_status',
            'Rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Expiring Agreements
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $expiryDate = Carbon::today()->addDays(90);

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
                $expiryDate
            )
            ->orderBy(
                'lease_end_date',
                'asc'
            )
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Tenant History
        |--------------------------------------------------------------------------
        */

        $recentHistory = TenantHistory::with([
            'tenant',
            'performer'
        ])
            ->orderByDesc('activity_date')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.tenants.dashboard.dashboard',
            compact(

                'tenantTotal',
                'tenantActive',
                'tenantInactive',

                'agreementTotal',
                'agreementActive',
                'agreementExpired',
                'agreementTerminated',

                'documentTotal',
                'documentPending',
                'documentVerified',
                'documentRejected',

                'expiringAgreements',

                'recentHistory'
            )
        );
    }
}