@php

    /*
    |--------------------------------------------------------------------------
    | Leasing Group
    |--------------------------------------------------------------------------
    */

    $isLeasingGroupActive =
        request()->routeIs('admin.leasing.*');


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    $isLeasingDashboardActive =
        request()->routeIs('admin.leasing.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Leasing
    |--------------------------------------------------------------------------
    */

    $isLeasingActive =
        request()->routeIs('admin.leasing.index');


    /*
    |--------------------------------------------------------------------------
    | Space Availability
    |--------------------------------------------------------------------------
    */

    $isSpaceAvailabilityActive =
        request()->routeIs('admin.leasing.space-availability.*');


    /*
    |--------------------------------------------------------------------------
    | Leasing Pipeline
    |--------------------------------------------------------------------------
    */

    $isLeasingPipelineActive =
        request()->routeIs('admin.leasing.pipeline.*');


    /*
    |--------------------------------------------------------------------------
    | Leads & Prospects
    |--------------------------------------------------------------------------
    */

    $isLeadsProspectsActive =
        request()->routeIs('admin.leasing.leads.*');


    /*
    |--------------------------------------------------------------------------
    | Site Visits
    |--------------------------------------------------------------------------
    */

    $isSiteVisitsActive =
        request()->routeIs('admin.leasing.site-visits.*');


    /*
    |--------------------------------------------------------------------------
    | Offers & Proposals
    |--------------------------------------------------------------------------
    */

    $isOffersProposalsActive =
        request()->routeIs('admin.leasing.offers.*');


    /*
    |--------------------------------------------------------------------------
    | Negotiation
    |--------------------------------------------------------------------------
    */

    $isNegotiationActive =
        request()->routeIs('admin.leasing.negotiations.*');


    /*
    |--------------------------------------------------------------------------
    | LOI
    |--------------------------------------------------------------------------
    */

    $isLoiActive =
        request()->routeIs('admin.leasing.loi.*');


    /*
    |--------------------------------------------------------------------------
    | Lease Approval
    |--------------------------------------------------------------------------
    */

    $isLeaseApprovalActive =
        request()->routeIs('admin.leasing.approvals.*');


    /*
    |--------------------------------------------------------------------------
    | Lease Agreements
    |--------------------------------------------------------------------------
    */

    $isLeaseAgreementsActive =
        request()->routeIs('admin.leasing.agreements.*');


    /*
    |--------------------------------------------------------------------------
    | Tenant Directory
    |--------------------------------------------------------------------------
    */

    $isTenantDirectoryActive =
        request()->routeIs('admin.leasing.tenant-directory.*');


    /*
    |--------------------------------------------------------------------------
    | Tenant Profile
    |--------------------------------------------------------------------------
    */

    $isTenantProfileActive =
        request()->routeIs('admin.leasing.tenant-profiles.*');


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Coordination
    |--------------------------------------------------------------------------
    */

    $isFitoutCoordinationActive =
        request()->routeIs('admin.leasing.fitout.*');


    /*
    |--------------------------------------------------------------------------
    | Tenant Opening / Handover
    |--------------------------------------------------------------------------
    */

    $isTenantHandoverActive =
        request()->routeIs('admin.leasing.handover.*');


    /*
    |--------------------------------------------------------------------------
    | Rent & Charges
    |--------------------------------------------------------------------------
    */

    $isRentChargesActive =
        request()->routeIs('admin.leasing.rent-charges.*');


    /*
    |--------------------------------------------------------------------------
    | Turnover Rent
    |--------------------------------------------------------------------------
    */

    $isTurnoverRentActive =
        request()->routeIs('admin.leasing.turnover-rent.*');


    /*
    |--------------------------------------------------------------------------
    | Deposits & Guarantees
    |--------------------------------------------------------------------------
    */

    $isDepositsGuaranteesActive =
        request()->routeIs('admin.leasing.deposits.*');


    /*
    |--------------------------------------------------------------------------
    | Renewals
    |--------------------------------------------------------------------------
    */

    $isRenewalsActive =
        request()->routeIs('admin.leasing.renewals.*');


    /*
    |--------------------------------------------------------------------------
    | Expiry & Terminations
    |--------------------------------------------------------------------------
    */

    $isExpiryTerminationsActive =
        request()->routeIs('admin.leasing.terminations.*')
        || request()->routeIs('admin.leasing.expiries.*');


    /*
    |--------------------------------------------------------------------------
    | Tenant Sales & Performance
    |--------------------------------------------------------------------------
    */

    $isTenantPerformanceActive =
        request()->routeIs('admin.leasing.tenant-performance.*');


    /*
    |--------------------------------------------------------------------------
    | Retail Mix & Category Management
    |--------------------------------------------------------------------------
    */

    $isRetailMixActive =
        request()->routeIs('admin.leasing.retail-mix.*');


    /*
    |--------------------------------------------------------------------------
    | Leasing Reports
    |--------------------------------------------------------------------------
    */

    $isLeasingReportsActive =
        request()->routeIs('admin.leasing.reports.*');

@endphp


{{-- =========================================================
     LEASING
========================================================= --}}

<details
    class="sidebar-group"
    {{ $isLeasingGroupActive ? 'open' : '' }}
>

    <summary
        class="sidebar-link {{ $isLeasingGroupActive ? 'active' : '' }}"
    >

        <i class="ri-file-text-line"></i>

        <span>
            Leasing
        </span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        {{-- =================================================
             DASHBOARD
        ================================================== --}}

        @can('dashboard.view')

            <a
                href="{{ route('admin.leasing.dashboard') }}"
                class="sidebar-sublink {{ $isLeasingDashboardActive ? 'active' : '' }}"
            >

                <i class="ri-dashboard-line"></i>

                <span>
                    Leasing Dashboard
                </span>

            </a>

        @endcan



        {{-- =================================================
             SPACE AVAILABILITY
        ================================================== --}}

        @can('space-availability.view')

            <a
                href="{{ route('admin.leasing.space-availability.index') }}"
                class="sidebar-sublink {{ $isSpaceAvailabilityActive ? 'active' : '' }}"
            >

                <i class="ri-layout-grid-line"></i>

                <span>
                    Space Availability
                </span>

            </a>

        @endcan



        {{-- =================================================
             LEASING PIPELINE
        ================================================== --}}

        @can('pipeline.view')

            <a
                href="{{ route('admin.leasing.pipeline.index') }}"
                class="sidebar-sublink {{ $isLeasingPipelineActive ? 'active' : '' }}"
            >

                <i class="ri-flow-chart"></i>

                <span>
                    Leasing Pipeline
                </span>

            </a>

        @endcan



        {{-- =================================================
             LEADS & PROSPECTS
        ================================================== --}}

        @can('leads.view')

            <a
                href="{{ route('admin.leasing.leads.index') }}"
                class="sidebar-sublink {{ $isLeadsProspectsActive ? 'active' : '' }}"
            >

                <i class="ri-user-search-line"></i>

                <span>
                    Leads 
                </span>

            </a>

        @endcan
           @can('prospects.view')

            <a
                href="{{ route('admin.leasing.prospects.index') }}"
                class="sidebar-sublink {{ $isLeadsProspectsActive ? 'active' : '' }}"
            >

                <i class="ri-user-search-line"></i>

                <span>
                    Prospects 
                </span>

            </a>

        @endcan



        {{-- =================================================
             SITE VISITS
        ================================================== --}}

        @can('site-visits.view')

            <a
                href="{{ route('admin.leasing.site-visits.index') }}"
                class="sidebar-sublink {{ $isSiteVisitsActive ? 'active' : '' }}"
            >

                <i class="ri-map-pin-user-line"></i>

                <span>
                    Site Visits
                </span>

            </a>

        @endcan



        {{-- =================================================
             OFFERS & PROPOSALS
        ================================================== --}}

        @can('leasing.offers.view')

            <a
                href="{{ route('admin.leasing.proposals.index') }}"
                class="sidebar-sublink {{ $isOffersProposalsActive ? 'active' : '' }}"
            >

                <i class="ri-draft-line"></i>

                <span>
                    Offers & Proposals
                </span>

            </a>

        @endcan



        {{-- =================================================
             NEGOTIATION
        ================================================== --}}

      



        {{-- =================================================
             LOI
        ================================================== --}}

        @can('leasing.loi.view')

            <a
                href="{{ route('admin.leasing.loi.index') }}"
                class="sidebar-sublink {{ $isLoiActive ? 'active' : '' }}"
            >

                <i class="ri-file-edit-line"></i>

                <span>
                    LOI
                </span>

            </a>

        @endcan



        {{-- =================================================
             LEASE APPROVAL
        ================================================== --}}

        @can('leasing.approvals.view')

            <a
                href="{{ route('admin.leasing.approvals.index') }}"
                class="sidebar-sublink {{ $isLeaseApprovalActive ? 'active' : '' }}"
            >

                <i class="ri-checkbox-circle-line"></i>

                <span>
                    Lease Approval
                </span>

            </a>

        @endcan



        {{-- =================================================
             LEASE AGREEMENTS
        ================================================== --}}

        @can('leasing.agreements.view')

            <a
                href="{{ route('admin.leasing.agreements.index') }}"
                class="sidebar-sublink {{ $isLeaseAgreementsActive ? 'active' : '' }}"
            >

                <i class="ri-file-check-line"></i>

                <span>
                    Lease Agreements
                </span>

            </a>

        @endcan



        {{-- =================================================
             TENANT DIRECTORY
        ================================================== --}}

        @can('leasing.tenant-directory.view')

            <a
                href="{{ route('admin.leasing.tenant-directory.index') }}"
                class="sidebar-sublink {{ $isTenantDirectoryActive ? 'active' : '' }}"
            >

                <i class="ri-contacts-line"></i>

                <span>
                    Tenant Directory
                </span>

            </a>

        @endcan



        {{-- =================================================
             TENANT PROFILE
        ================================================== --}}

        @can('leasing.tenant-profiles.view')

            <a
                href="{{ route('admin.leasing.tenant-profiles.index') }}"
                class="sidebar-sublink {{ $isTenantProfileActive ? 'active' : '' }}"
            >

                <i class="ri-user-3-line"></i>

                <span>
                    Tenant Profile
                </span>

            </a>

        @endcan



        {{-- =================================================
             FIT-OUT COORDINATION
        ================================================== --}}

        @can('leasing.fitout.view')

            <a
                href="{{ route('admin.leasing.fitout.index') }}"
                class="sidebar-sublink {{ $isFitoutCoordinationActive ? 'active' : '' }}"
            >

                <i class="ri-tools-line"></i>

                <span>
                    Fit-Out Coordination
                </span>

            </a>

        @endcan



        {{-- =================================================
             TENANT OPENING / HANDOVER
        ================================================== --}}

        @can('leasing.handover.view')

            <a
                href="{{ route('admin.leasing.handover.index') }}"
                class="sidebar-sublink {{ $isTenantHandoverActive ? 'active' : '' }}"
            >

                <i class="ri-key-2-line"></i>

                <span>
                    Tenant Opening / Handover
                </span>

            </a>

        @endcan



        {{-- =================================================
             RENT & CHARGES
        ================================================== --}}

        @can('leasing.rent-charges.view')

            <a
                href="{{ route('admin.leasing.rent-charges.index') }}"
                class="sidebar-sublink {{ $isRentChargesActive ? 'active' : '' }}"
            >

                <i class="ri-money-dollar-circle-line"></i>

                <span>
                    Rent & Charges
                </span>

            </a>

        @endcan



        {{-- =================================================
             TURNOVER RENT
        ================================================== --}}

        @can('leasing.turnover-rent.view')

            <a
                href="{{ route('admin.leasing.turnover-rent.index') }}"
                class="sidebar-sublink {{ $isTurnoverRentActive ? 'active' : '' }}"
            >

                <i class="ri-line-chart-line"></i>

                <span>
                    Turnover Rent
                </span>

            </a>

        @endcan



        {{-- =================================================
             DEPOSITS & GUARANTEES
        ================================================== --}}

        @can('leasing.deposits.view')

            <a
                href="{{ route('admin.leasing.deposits.index') }}"
                class="sidebar-sublink {{ $isDepositsGuaranteesActive ? 'active' : '' }}"
            >

                <i class="ri-bank-card-line"></i>

                <span>
                    Deposits & Guarantees
                </span>

            </a>

        @endcan



        {{-- =================================================
             RENEWALS
        ================================================== --}}

        @can('leasing.renewals.view')

            <a
                href="{{ route('admin.leasing.renewals.index') }}"
                class="sidebar-sublink {{ $isRenewalsActive ? 'active' : '' }}"
            >

                <i class="ri-refresh-line"></i>

                <span>
                    Renewals
                </span>

            </a>

        @endcan



        {{-- =================================================
             EXPIRY & TERMINATIONS
        ================================================== --}}

        @can('leasing.terminations.view')

            <a
                href="{{ route('admin.leasing.terminations.index') }}"
                class="sidebar-sublink {{ $isExpiryTerminationsActive ? 'active' : '' }}"
            >

                <i class="ri-close-circle-line"></i>

                <span>
                    Expiry & Terminations
                </span>

            </a>

        @endcan



        {{-- =================================================
             TENANT SALES & PERFORMANCE
        ================================================== --}}

        @can('leasing.tenant-performance.view')

            <a
                href="{{ route('admin.leasing.tenant-performance.index') }}"
                class="sidebar-sublink {{ $isTenantPerformanceActive ? 'active' : '' }}"
            >

                <i class="ri-bar-chart-grouped-line"></i>

                <span>
                    Tenant Sales & Performance
                </span>

            </a>

        @endcan



        {{-- =================================================
             RETAIL MIX & CATEGORY MANAGEMENT
        ================================================== --}}

        @can('leasing.retail-mix.view')

            <a
                href="{{ route('admin.leasing.retail-mix.index') }}"
                class="sidebar-sublink {{ $isRetailMixActive ? 'active' : '' }}"
            >

                <i class="ri-pie-chart-2-line"></i>

                <span>
                    Retail Mix & Category Management
                </span>

            </a>

        @endcan



        {{-- =================================================
             LEASING REPORTS
        ================================================== --}}

        @can('leasing.reports.view')

            <a
                href="{{ route('admin.leasing.reports.index') }}"
                class="sidebar-sublink {{ $isLeasingReportsActive ? 'active' : '' }}"
            >

                <i class="ri-file-chart-line"></i>

                <span>
                    Leasing Reports
                </span>

            </a>

        @endcan


    </div>

</details>