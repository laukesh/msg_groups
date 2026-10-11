<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Mall Management System'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('public/assets/css/app.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/components.css') }}">
	<link rel="icon" type="image/png" href="{{ asset('public/assets/img/favicon.png') }}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  

    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>
	<link rel="stylesheet" href="{{ asset('public/assets/css/custom.css') }}">
</head>
<body>
    
<body>


<header class="app-topbar">

    {{-- LEFT --}}
    <div class="topbar-left">

        <button
            type="button"
            class="sidebar-toggle"
            id="sidebarToggle"
        >
            <i class="ri-menu-line"></i>
        </button>


        <div class="page-heading">

            <h5>
                @yield('page_title', 'Dashboard')
            </h5>

            <span>
                Hargeisa Mall Management System
            </span>

        </div>

    </div>


    {{-- RIGHT --}}
    <div class="topbar-right">


        {{-- DATE --}}
        <div class="topbar-date">

            <i class="ri-calendar-line"></i>

            <span id="datechip"></span>

        </div>


        {{-- NOTIFICATION --}}
        <button
            type="button"
            class="topbar-icon"
        >

            <i class="ri-notification-3-line"></i>

            <span class="notification-dot"></span>

        </button>


       {{-- =================================================
     USER DROPDOWN
================================================== --}}
<div class="dropdown topbar-user">

    {{-- User Toggle --}}
    <button
        type="button"
        class="topbar-user-toggle"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >

        {{-- Avatar --}}
        <div class="topbar-user-avatar">
            {{ strtoupper(
                substr(auth()->user()->name ?? 'U', 0, 1)
            ) }}
        </div>

        {{-- User Information --}}
        <div class="topbar-user-info">

            <strong>
                {{ auth()->user()->name ?? 'User' }}
            </strong>

            <small>
                {{ auth()->user()
                    ->getRoleNames()
                    ->implode(', ') ?: 'User' }}
            </small>

        </div>

        <i class="ri-arrow-down-s-line user-arrow"></i>

    </button>


    {{-- =================================================
         DROPDOWN MENU
    ================================================== --}}
    <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">

        {{-- User Header --}}
        


        <div class="dropdown-divider"></div>


        {{-- Dashboard --}}
        <!--a href="{{ route('profile.dashboard') }}"
           class="dropdown-item">

            <i class="ri-dashboard-3-line"></i>

            <span>Dashboard</span>

        </a-->


        {{-- Profile --}}
        @can('profile.view')

            <a href="{{ route('profile.show') }}"
               class="dropdown-item">

                <i class="ri-user-line"></i>

                <span>My Profile</span>

            </a>

        @endcan


        {{-- Edit Profile --}}
        @can('profile.view')

            <!--a href="{{ route('profile.edit') }}"
               class="dropdown-item">

                <i class="ri-edit-line"></i>

                <span>Edit Profile</span>

            </a-->

        @endcan


        {{-- Change Password --}}
        @can('profile.update')

            <a href="{{ route('profile.password') }}"
               class="dropdown-item">

                <i class="ri-lock-password-line"></i>

                <span>Change Password</span>

            </a>

        @endcan


        <div class="dropdown-divider"></div>


        {{-- Logout --}}
        <form action="{{ route('logout') }}"
              method="POST"
              class="m-0">

            @csrf

            <button type="submit"
                    class="dropdown-item logout-item">

                <i class="ri-logout-box-r-line"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</div>

    </div>

</header>

<style type="text/css">
    .sidebar-brand {
    height: 66px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    background: #fff;
}

.sidebar-logo {
    width: 100%;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

.sidebar-logo img {
    width: 78px;
    height: 62px;
    object-fit: contain;
    display: block;
}



</style>

{{-- ============================================================
     LEFT SIDEBAR
============================================================ --}}

<aside class="app-sidebar">

    {{-- BRAND --}}
    <!-- <div class="sidebar-brand">

        <div class="sidebar-logo">

            <i class="ri-building-4-line"></i>

        </div>

        <div class="sidebar-brand-text">

            <strong>Hargeisa Mall</strong>

            <small>Mall Management</small>

        </div>

    </div> -->
    <div class="sidebar-brand">
    <div class="sidebar-logo">
        <img
            src="{{ asset('public/assets/img/logo-color.png') }}"
            alt="Hargeisa Mall"
        >
    </div>
</div>


    {{-- SIDEBAR MENU --}}
    <div class="sidebar-menu">

        @auth

        @php

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */
            $isDashboardActive = request()->routeIs('admin.dashboard');


          /*
                |--------------------------------------------------------------------------
                | DEVELOPMENT MANAGEMENT
                |--------------------------------------------------------------------------
                */

                $isLandActive = request()->routeIs('admin.land.*');

                $isFeasibilityActive = request()->routeIs(
                    'admin.feasibility-investment.*'
                );

                $isProjectsActive = request()->routeIs(
                    'admin.projects.*'
                );

                $isProcurementActive = request()->routeIs(
                    'admin.procurement.*'
                );

                $isConstructionActive = request()->routeIs(
                    'admin.construction.*'
                );


                /*
                |--------------------------------------------------------------------------
                | DESIGN MANAGEMENT
                |--------------------------------------------------------------------------
                */

                $isDesignActive = request()->routeIs(
                    'admin.design-management.*'
                );


                /*
                |--------------------------------------------------------------------------
                | DEVELOPMENT MANAGEMENT - MAIN ACTIVE STATE
                |--------------------------------------------------------------------------
                */

                $isCommissioningActive = request()->routeIs(
                    'admin.commissioning.*'
                );

                $isHandoverActive = request()->routeIs(
                    'admin.handover.*'
                );

            $isDevelopmentActive =
                $isLandActive ||
                $isFeasibilityActive ||
                $isProjectsActive ||
                $isProcurementActive ||
                $isConstructionActive ||    
                $isDesignActive ||
                $isCommissioningActive||
                $isHandoverActive;


            /*
            |--------------------------------------------------------------------------
            | CONTRACT MANAGEMENT
            |--------------------------------------------------------------------------
            */
            $isContractActive = request()->routeIs(
                'admin.contract-management.*'
            );
            
          


            /*
            |--------------------------------------------------------------------------
            | ASSET MANAGEMENT
            |--------------------------------------------------------------------------
            */
            $isMallsActive = request()->routeIs(
                'admin.assets.malls.*'
            );

            $isBuildingsActive = request()->routeIs(
                'admin.assets.buildings.*'
            );

            $isFloorsActive = request()->routeIs(
                'admin.assets.floors.*'
            );

            $isZonesActive = request()->routeIs(
                'admin.assets.zones.*'
            );

            $isUnitTypesActive = request()->routeIs(
                'admin.assets.unit-types.*'
            );

            $isUnitsActive = request()->routeIs(
                'admin.assets.units.*'
            );

            $isAssetCategoriesActive = request()->routeIs(
                'admin.assets.asset-categories.*'
            );

            $isAssetsActive = request()->routeIs(
                'admin.assets.assets.*'
            );

            $isDepartmentsActive = request()->routeIs(
                'admin.assets.departments.*'
            );

            $isIncomesActive = request()->routeIs(
                'admin.assets.incomes.*'
            );

            $isExpensesActive = request()->routeIs(
                'admin.assets.expenses.*'
            );

            $isComplaintsActive = request()->routeIs(
                'admin.assets.complaints.*'
            );
            $isPerformanceActive = request()->routeIs(
                'admin.assets.performance.*'
            );
            // Asset Portfolio
    $isPropertyRegisterActive = request()->routeIs(
        'admin.assets.malls.*'
    );

    $isBuildingRegisterActive = request()->routeIs(
        'admin.assets.buildings.*'
    );

    $isFloorHierarchyActive = request()->routeIs(
        'admin.assets.floors.*'
    );

    $isSpaceUnitRegisterActive = request()->routeIs(
        'admin.assets.units.*'
    );

    $isSpaceZoneRegisterActive = request()->routeIs(
        'admin.assets.zones.*'
    );
    $isUnitTypeRegisterActive = request()->routeIs(
        'admin.assets.unit-types.*'
    );
    $isUnitRegisterActive=request()->routeIs('admin.assets.units.*');


    // Asset Classification
    $isAssetTypeActive = request()->routeIs(
        'admin.assets.types.*'
    );

    $isAssetPurposeActive = request()->routeIs(
        'admin.assets.purposes.*'
    );

    $isIndustryProfileActive = request()->routeIs(
        'admin.assets.industry-profiles.*'
    );

    $isOperatingModelActive = request()->routeIs(
        'admin.assets.operating-models.*'
    );

    $isOwnershipActive = request()->routeIs(
        'admin.assets.ownerships.*'
    );

    $isRevenueStatusActive = request()->routeIs(
        'admin.assets.revenue-statuses.*'
    );


    // Asset Operations
    $isOccupancyActive = request()->routeIs(
        'admin.assets.occupancy.*'
    );

    $isAssetPerformanceActive = request()->routeIs(
        'admin.assets.performance.*'
    );

    $isAssetConditionActive = request()->routeIs(
        'admin.assets.conditions.*'
    );

    $isCapexActive = request()->routeIs(
        'admin.assets.capex.*'
    );

    $isLifecyclePlanningActive = request()->routeIs(
        'admin.assets.lifecycle-planning.*'
    );


    // Industry Operating Profiles
    $isCommercialRetailActive = request()->routeIs(
        'admin.assets.industry-profiles.commercial-retail.*'
    );

    $isHospitalityActive = request()->routeIs(
        'admin.assets.industry-profiles.hospitality.*'
    );

    $isResidentialActive = request()->routeIs(
        'admin.assets.industry-profiles.residential.*'
    );

    $isIndustrialActive = request()->routeIs(
        'admin.assets.industry-profiles.industrial.*'
    );

    $isCommunityActive = request()->routeIs(
        'admin.assets.industry-profiles.community.*'
    );


    // Asset Reports & KPIs
    $isCoreKpisActive = request()->routeIs(
        'admin.assets.reports.core-kpis.*'
    );

    $isIndustryKpisActive = request()->routeIs(
        'admin.assets.reports.industry-kpis.*'
    );

            $isAssetManagementActive =
                $isMallsActive ||
                $isBuildingsActive ||
                $isFloorsActive ||
                $isZonesActive ||
                $isUnitTypesActive ||
                $isUnitsActive ||
                $isAssetCategoriesActive ||
                $isAssetsActive ||
                $isDepartmentsActive ||
                $isIncomesActive ||
                $isExpensesActive ||
                $isComplaintsActive ||
                $isPerformanceActive;
                $isAssetsPropertiesActive =
        $isPropertyRegisterActive ||
        $isBuildingRegisterActive ||
        $isFloorHierarchyActive ||
        $isSpaceUnitRegisterActive ||
         $isSpaceZoneRegisterActive||
         $isUnitTypeRegisterActive||
         $isUnitRegisterActive ||
        $isAssetTypeActive ||
        $isAssetPurposeActive ||
        $isIndustryProfileActive ||
        $isOperatingModelActive ||
        $isOwnershipActive ||
        $isRevenueStatusActive ||

        $isOccupancyActive ||
        $isAssetPerformanceActive ||
        $isAssetConditionActive ||
        $isCapexActive ||
        $isLifecyclePlanningActive ||

        $isCommercialRetailActive ||
        $isHospitalityActive ||
        $isResidentialActive ||
        $isIndustrialActive ||
        $isCommunityActive ||

        $isCoreKpisActive ||
        $isIndustryKpisActive;


            /*
            |--------------------------------------------------------------------------
            | LEASING
            |--------------------------------------------------------------------------
            */
            $isLeasingDashboardActive = request()->routeIs(
                'admin.leasing.dashboard'
            );

            $isLeasingActive = request()->routeIs(
                'admin.leasing.index'
            );

            $isLeaseProposalsActive = request()->routeIs(
                'admin.leasing.proposals.*'
            );

            $isLeaseAgreementsActive = request()->routeIs(
                'admin.leasing.agreements.*'
            );

            $isLeaseTermsActive = request()->routeIs(
                'admin.leasing.terms.*'
            );

            $isLeaseDocumentsActive = request()->routeIs(
                'admin.leasing.documents.*'
            );

            $isLeaseEscalationsActive = request()->routeIs(
                'admin.leasing.escalations.*'
            );

            $isLeaseRenewalsActive = request()->routeIs(
                'admin.leasing.renewals.*'
            );

            $isLeaseTerminationsActive = request()->routeIs(
                'admin.leasing.terminations.*'
            );

            $isLeaseHistoryActive = request()->routeIs(
                'admin.leasing.history.*'
            );

            $isLeasingGroupActive =
                request()->routeIs('admin.leasing.*');


            /*
            |--------------------------------------------------------------------------
            | TENANTS
            |--------------------------------------------------------------------------
            */
            $isTenantsDashboardActive =
                request()->is('admin/tenants/dashboard');

            $isTenantsIndexActive =
                request()->is('admin/tenants') &&
                !request()->has('status');

            $isActiveTenantsActive =
                request()->is('admin/tenants') &&
                request()->get('status') === 'Active';

            $isInactiveTenantsActive =
                request()->is('admin/tenants') &&
                request()->get('status') === 'Inactive';

            $isTenantLeasesActive =
                request()->is('admin/tenants/leases');

            $isLeaseExpiryActive =
                request()->is('admin/tenants/leases/expiry');

            $isTenantContactsActive =
                request()->is('admin/tenants/contacts');

            $isEmergencyContactsActive =
                request()->is('admin/tenants/emergency-contacts');

            $isTenantDocumentsActive =
                request()->is('admin/tenants/documents');

            $isTenantsGroupActive =
                request()->is('admin/tenants*');


            /*
            |--------------------------------------------------------------------------
            | REVENUE
            |--------------------------------------------------------------------------
            */
            $isRevenueDashboardActive =
                request()->is('admin/revenue/dashboard');

            $isRentSchedulesActive =
                request()->is('admin/revenue/rent-schedules*');

            $isInvoicesActive =
                request()->is('admin/revenue/invoices*');

            $isPaymentsActive =
                request()->is('admin/revenue/payments*');

            $isReconciliationActive =
                request()->is('admin/revenue/reconciliation*');

            $isOutstandingActive =
                request()->is('admin/revenue/outstanding') &&
                !request()->is('admin/revenue/outstanding/*');

            $isOverdueActive =
                request()->is('admin/revenue/outstanding/overdue*');

            $isTenantOutstandingActive =
                request()->is('admin/revenue/outstanding/tenants*');

            $isRevenueReportActive =
                request()->is('admin/revenue/reports/revenue*');

            $isCollectionReportActive =
                request()->is('admin/revenue/reports/collections*');

            $isTenantWiseRevenueActive =
                request()->is('admin/revenue/reports/tenant-wise*');

            $isAgingReportActive =
                request()->is('admin/revenue/reports/aging*');

            $isRevenueGroupActive =
                request()->is('admin/revenue*');


            /*
            |--------------------------------------------------------------------------
            | FIT-OUT
            |--------------------------------------------------------------------------
            */
            $isFitoutDashboardActive =
                request()->routeIs('admin.fitout.dashboard');

            $isFitoutRequestsActive =
                request()->routeIs('admin.fitout.requests.*');

            $isFitoutApprovalsActive =
                request()->routeIs('admin.fitout.approvals.*');

            $isFitoutContractorsActive =
                request()->routeIs('admin.fitout.contractors.*');

            $isFitoutInspectionsActive =
                request()->routeIs('admin.fitout.inspections.*');

            $isFitoutSnagsActive =
                request()->routeIs('admin.fitout.snags.*');

            $isFitoutDocumentsActive =
                request()->routeIs('admin.fitout.documents.*');

            $isFitoutHandoversActive =
                request()->routeIs('admin.fitout.handovers.*');

            $isFitoutGroupActive =
                request()->routeIs('admin.fitout.*');


            /*
            |--------------------------------------------------------------------------
            | ADMINISTRATION
            |--------------------------------------------------------------------------
            */
            $isUsersActive =
                request()->routeIs('admin.users.*');

            $isRolesActive =
                request()->routeIs('admin.roles.*');

            $isAuditActive =
                request()->routeIs('admin.users.audits');

            $isAdministrationActive =
                $isUsersActive ||
                $isRolesActive ||
                $isAuditActive;


            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */
            $isProfileActive =
                request()->routeIs('profile.*');
                $isActivityLogsActive = request()->routeIs('admin.activity-logs.*');

        @endphp

            <a href="{{ route('admin.home') }}"
               class="sidebar-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">

                <i class="ri-home-5-line"></i>

                <span>Home</span>

            </a>

          {{-- =================================================
                    DASHBOARD
                ================================================== --}}

                @can('dashboard.view')

                    <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ $isDashboardActive ? 'active' : '' }}">

                        <i class="ri-dashboard-line"></i>

                        <span>Dashboard</span>

                    </a>

                @endcan

            {{-- =================================================
     DEVELOPMENT MANAGEMENT
================================================== --}}

<details
    class="sidebar-group"
    {{ $isDevelopmentActive ? 'open' : '' }}
>

    <summary class="sidebar-link {{ $isDevelopmentActive ? 'active' : '' }}">

        <i class="ri-building-2-line"></i>

        <span>Development Management</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">

        {{-- Land Acquisition --}}
        <a href="{{ route('admin.land.lands.index') }}"
           class="sidebar-sublink {{ $isLandActive ? 'active' : '' }}">

            <i class="ri-landscape-line"></i>

            <span>Land Acquisition</span>

        </a>


        {{-- Feasibility --}}
        <a href="{{ route('admin.feasibility-investment.index') }}"
           class="sidebar-sublink {{ $isFeasibilityActive ? 'active' : '' }}">

            <i class="ri-line-chart-line"></i>

            <span>Feasibility & Investment</span>

        </a>


        {{-- Projects --}}
        <a href="{{ route('admin.projects.index') }}"
           class="sidebar-sublink {{ $isProjectsActive ? 'active' : '' }}">

            <i class="ri-building-line"></i>

            <span>Projects Management</span>

        </a>


        {{-- Procurement --}}
        <a href="{{ route('admin.procurement.index') }}"
           class="sidebar-sublink {{ $isProcurementActive ? 'active' : '' }}">

            <i class="ri-shopping-cart-line"></i>

            <span>Procurement</span>

        </a>


        {{-- Construction --}}
        <a href="{{ route('admin.construction.index') }}"
           class="sidebar-sublink {{ $isConstructionActive ? 'active' : '' }}">

            <i class="ri-building-4-line"></i>

            <span>Construction Management</span>

        </a>


        {{-- Future Modules --}}
       <!--  <a href="#"
           class="sidebar-sublink">

            <i class="ri-layout-4-line"></i>

            <span>Development Planning</span>

        </a> -->

        <a href="{{ route('admin.design-management.index') }}"
           class="sidebar-sublink {{ $isDesignActive ? 'active' : '' }}">

            <i class="ri-draft-line"></i>

            <span>Design Management</span>

        </a>
        <a href="{{ route('admin.commissioning.index') }}"
           class="sidebar-sublink">

            <i class="ri-settings-3-line"></i>

            <span>Commissioning</span>

        </a>

        <a href="{{ route('admin.handover.index') }}"
           class="sidebar-sublink ">

            <i class="ri-checkbox-circle-line"></i>

            <span>Handover & Closeout</span>

        </a>

    </div>

</details>


{{-- Portfolio & Investment --}}
<details
    class="sidebar-group"
>

    <summary class="sidebar-link">

        <i class="ri-briefcase-4-line"></i>

        <span>Portfolio & Investment</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">

        {{-- Portfolio Overview --}}
        <a href="{{ route('admin.portfolio-investment.overview') }}"
           class="sidebar-sublink">

            <i class="ri-dashboard-line"></i>

            <span>Portfolio Overview</span>

        </a>


        {{-- Land Bank --}}
        <a href="{{ route('admin.land.lands.index') }}"
           class="sidebar-sublink">

            <i class="ri-landscape-line"></i>

            <span>Land Bank</span>

        </a>


        {{-- Investment Opportunities --}}
        <a href="{{ route('admin.land.opportunities.index') }}"
           class="sidebar-sublink">

            <i class="ri-lightbulb-line"></i>

            <span>Investment Opportunities</span>

        </a>


        {{-- Acquisition Pipeline --}}
        <a href="{{ route('admin.portfolio-investment.acquisition-pipeline') }}"
           class="sidebar-sublink">

            <i class="ri-git-merge-line"></i>

            <span>Acquisition Pipeline</span>

        </a>


        {{-- Due Diligence --}}
        <a href="{{ route('admin.portfolio-investment.due-diligence') }}"
           class="sidebar-sublink">

            <i class="ri-search-eye-line"></i>

            <span>Due Diligence</span>

        </a>


        {{-- Feasibility Studies --}}
        <a href="{{ route('admin.portfolio-investment.feasibility-studies') }}"
           class="sidebar-sublink">

            <i class="ri-line-chart-line"></i>

            <span>Feasibility Studies</span>

        </a>


        {{-- Development Appraisal --}}
        <a href="{{ route('admin.portfolio-investment.development-appraisal') }}"
           class="sidebar-sublink">

            <i class="ri-building-line"></i>

            <span>Development Appraisal</span>

        </a>


        {{-- Scenario & Sensitivity Analysis --}}
        <a href="{{ route('admin.portfolio-investment.scenario-sensitivity') }}"
           class="sidebar-sublink">

            <i class="ri-sliders-2-line"></i>

            <span>Scenario & Sensitivity Analysis</span>

        </a>


        {{-- Investment Committee --}}
        <a href="{{ route('admin.portfolio-investment.investment-committee') }}"
           class="sidebar-sublink">

            <i class="ri-team-line"></i>

            <span>Investment Committee</span>

        </a>


        {{-- Capital Planning --}}
        <a href="{{ route('admin.portfolio-investment.capital-planning') }}"
           class="sidebar-sublink">

            <i class="ri-money-dollar-circle-line"></i>

            <span>Capital Planning</span>

        </a>


        {{-- Valuation --}}
        <a href="{{ route('admin.portfolio-investment.valuation') }}"
           class="sidebar-sublink">

            <i class="ri-calculator-line"></i>

            <span>Valuation</span>

        </a>


        {{-- Asset Strategy & Business Plans --}}
        <a href="{{ route('admin.portfolio-investment.asset-strategy') }}"
           class="sidebar-sublink">

            <i class="ri-bar-chart-grouped-line"></i>

            <span>Asset Strategy & Business Plans</span>

        </a>


        {{-- Acquisition & Disposal --}}
        <a href="{{ route('admin.portfolio-investment.acquisition-disposal') }}"
           class="sidebar-sublink">

            <i class="ri-arrow-left-right-line"></i>

            <span>Acquisition & Disposal</span>

        </a>


        {{-- Portfolio Performance --}}
        <a href="{{ route('admin.portfolio-investment.performance-monitoring') }}"
           class="sidebar-sublink">

            <i class="ri-line-chart-line"></i>

            <span>Portfolio Performance</span>

        </a>


        {{-- Investment Reports --}}
        <a href="{{ route('admin.portfolio-investment.investment-reports') }}"
           class="sidebar-sublink">

            <i class="ri-file-chart-line"></i>

            <span>Investment Reports</span>

        </a>


        {{-- Asset Purpose & Operating Model --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-settings-4-line"></i>

            <span>Asset Purpose & Operating Model</span>

        </a>


        {{-- Ownership / Use Strategy --}}
        <a href="{{ route('admin.portfolio-investment.ownership-use-strategy') }}"
           class="sidebar-sublink">

            <i class="ri-user-settings-line"></i>

            <span>Ownership / Use Strategy</span>

        </a>


        {{-- Revenue Model --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-money-rupee-circle-line"></i>

            <span>Revenue Model</span>

        </a>

    </div>

</details>

<details
    class="sidebar-group"
>

    <summary class="sidebar-link">

        <i class="ri-building-2-line"></i>

        <span>Development Management</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">

        {{-- Development Overview --}}
        <a href="{{ route('admin.development.overview') }}"
           class="sidebar-sublink">

            <i class="ri-dashboard-line"></i>

            <span>Development Overview</span>

        </a>


        {{-- Project Initiation --}}
        <a href="{{ route('admin.projects.index') }}"
           class="sidebar-sublink">

            <i class="ri-play-circle-line"></i>

            <span>Project Initiation</span>

        </a>


        {{-- Development Brief --}}
        <a href="{{ route('admin.development-briefs.index') }}"
           class="sidebar-sublink">

            <i class="ri-file-text-line"></i>

            <span>Development Brief</span>

        </a>


        {{-- Development Budget --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-money-dollar-circle-line"></i>

            <span>Development Budget</span>

        </a>


        {{-- Master Programme --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-calendar-schedule-line"></i>

            <span>Master Programme</span>

        </a>


        {{-- Development Strategy --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-road-map-line"></i>

            <span>Development Strategy</span>

        </a>


        {{-- Consultant Management --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-team-line"></i>

            <span>Consultant Management</span>

        </a>


        {{-- Design Management --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-draft-line"></i>

            <span>Design Management</span>

        </a>


        {{-- Authority Approvals & Permits --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-government-line"></i>

            <span>Authority Approvals &amp; Permits</span>

        </a>


        {{-- Stakeholder Management --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-user-settings-line"></i>

            <span>Stakeholder Management</span>

        </a>


        {{-- Development Risks & Decisions --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-alarm-warning-line"></i>

            <span>Development Risks &amp; Decisions</span>

        </a>


        {{-- Stage-Gate Approvals --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-git-merge-line"></i>

            <span>Stage-Gate Approvals</span>

        </a>


        {{-- Handover Planning --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-checkbox-circle-line"></i>

            <span>Handover Planning</span>

        </a>


        {{-- Development Reports --}}
        <a href="#"
           class="sidebar-sublink">

            <i class="ri-file-chart-line"></i>

            <span>Development Reports</span>

        </a>

    </div>

</details>


{{-- =================================================
     CONTRACT MANAGEMENT
================================================== --}}

<a href="{{ route('admin.contract-management.index') }}"
   class="sidebar-link {{ $isContractActive ? 'active' : '' }}">

    <i class="ri-file-list-3-line"></i>

    <span>Contract Management</span>

</a>

{{-- ================================================================
     ASSETS & PROPERTIES
================================================================ --}}

@php

    /*
    |--------------------------------------------------------------------------
    | Main Asset / Property Group
    |--------------------------------------------------------------------------
    */

    $isAssetsPropertiesActive =
        request()->routeIs('admin.assets.malls.*') ||
        request()->routeIs('admin.assets.buildings.*') ||
        request()->routeIs('admin.assets.floors.*') ||
        request()->routeIs('admin.assets.zones.*') ||
        request()->routeIs('admin.assets.unit-types.*') ||
        request()->routeIs('admin.assets.units.*') ||
        request()->routeIs('admin.assets.departments.*') ||
        request()->routeIs('admin.assets.unit-statuses.*') ||
        request()->routeIs('admin.assets.assets.*') ||
        request()->routeIs('admin.assets.asset-categories.*') ||
        request()->routeIs('admin.assets.unit-documents.*') ||
        request()->routeIs('admin.assets.purposes.*') ||
        request()->routeIs('admin.assets.industry-profiles.*') ||
        request()->routeIs('admin.assets.operating-models.*') ||
        request()->routeIs('admin.assets.ownerships.*') ||
        request()->routeIs('admin.assets.revenue-statuses.*') ||
        request()->routeIs('admin.assets.types.*') ||
        request()->routeIs('admin.assets.capex.*') ||
        request()->routeIs('admin.assets.operations.*') ||
        request()->routeIs('admin.assets.expenses.*') ||
        request()->routeIs('admin.assets.incomes.*') ||
        request()->routeIs('admin.assets.economic-dashboard') ||
        request()->routeIs('admin.assets.performance.*') ||
        request()->routeIs('admin.assets.reports.*');


    /*
    |--------------------------------------------------------------------------
    | Asset Portfolio
    |--------------------------------------------------------------------------
    */

    $isAssetPortfolioActive =
        request()->routeIs('admin.assets.malls.*') ||
        request()->routeIs('admin.assets.buildings.*') ||
        request()->routeIs('admin.assets.floors.*') ||
        request()->routeIs('admin.assets.units.*');


    $isPropertyRegisterActive =
        request()->routeIs('admin.assets.malls.*');

    $isBuildingRegisterActive =
        request()->routeIs('admin.assets.buildings.*');

    $isFloorHierarchyActive =
        request()->routeIs('admin.assets.floors.*');
        $isSpaceZoneRegisterActive=request()->routeIs('admin.assets.zones.*');
$isSpaceZoneRegisterActive =
        request()->routeIs('admin.assets.zones.*');
        $isUnitTypeRegisterActive=request()->routeIs('admin.assets.unit-types.*');
        $isUnitRegisterActive=request()->routeIs('admin.assets.units.*');
    $isSpaceUnitRegisterActive =
        request()->routeIs('admin.assets.units.*');


    /*
    |--------------------------------------------------------------------------
    | Asset Classification
    |--------------------------------------------------------------------------
    */

    $isAssetClassificationActive =
        request()->routeIs('admin.assets.types.*') ||
        request()->routeIs('admin.assets.purposes.*') ||
        request()->routeIs('admin.assets.industry-profiles.*') ||
        request()->routeIs('admin.assets.operating-models.*') ||
        request()->routeIs('admin.assets.ownerships.*') ||
        request()->routeIs('admin.assets.revenue-statuses.*');


    $isAssetTypeActive =
        request()->routeIs('admin.assets.types.*');

    $isAssetPurposeActive =
        request()->routeIs('admin.assets.purposes.*');

    $isIndustryProfileActive =
        request()->routeIs('admin.assets.industry-profiles.*');

    $isOperatingModelActive =
        request()->routeIs('admin.assets.operating-models.*');

    $isOwnershipActive =
        request()->routeIs('admin.assets.ownerships.*');

    $isRevenueStatusActive =
        request()->routeIs('admin.assets.revenue-statuses.*');


    /*
    |--------------------------------------------------------------------------
    | Asset Operations
    |--------------------------------------------------------------------------
    */

    $isAssetOperationsActive =
        request()->routeIs('admin.assets.operations.*') ||
        request()->routeIs('admin.assets.capex.*') ||
        request()->routeIs('admin.assets.performance.*');


    $isOccupancyActive =
        request()->routeIs('admin.assets.operations.occupancy.*');

    $isAssetConditionActive =
        request()->routeIs('admin.assets.operations.conditions.*');

    $isAssetPerformanceActive =
        request()->routeIs('admin.assets.operations.performance.*') ||
        request()->routeIs('admin.assets.performance.*');

    $isCapexActive =
        request()->routeIs('admin.assets.capex.*');

    $isLifecyclePlanningActive =
        request()->routeIs('admin.assets.operations.lifecycle.*');

    $isIndustryOperatingProfilesActive =
        request()->routeIs('admin.assets.operations.industry-profiles.*');


    /*
    |--------------------------------------------------------------------------
    | Reports & KPIs
    |--------------------------------------------------------------------------
    */

    $isAssetReportsActive =
        request()->routeIs('admin.assets.reports.*');


    $isCoreKpisActive =
        request()->routeIs('admin.assets.reports.core-kpis.*');

    $isIndustryKpisActive =
        request()->routeIs('admin.assets.reports.industry-kpis.*');


    /*
    |--------------------------------------------------------------------------
    | Financial / Economic
    |--------------------------------------------------------------------------
    */

    $isAssetFinancialActive =
        request()->routeIs('admin.assets.incomes.*') ||
        request()->routeIs('admin.assets.expenses.*') ||
        request()->routeIs('admin.assets.economic-dashboard');

@endphp


{{-- ================================================================
     MAIN ASSET GROUP
================================================================ --}}

@if(
    auth()->user()->can('malls.view') ||
    auth()->user()->can('buildings.view') ||
    auth()->user()->can('floors.view') ||
    auth()->user()->can('zones.view') ||
    auth()->user()->can('unit_types.view') ||
    auth()->user()->can('units.view') ||
    auth()->user()->can('departments.view') ||
    auth()->user()->can('unit_statuses.view') ||
    auth()->user()->can('assets.view') ||
    auth()->user()->can('asset_categories.view') ||
    auth()->user()->can('purposes.view') ||
    auth()->user()->can('types.view') ||
    auth()->user()->can('industry_profiles.view') ||
    auth()->user()->can('operating_models.view') ||
    auth()->user()->can('ownerships.view') ||
    auth()->user()->can('revenue_statuses.view') ||
    auth()->user()->can('occupancy.view') ||
    auth()->user()->can('conditions.view') ||
    auth()->user()->can('performance.view') ||
    auth()->user()->can('capex.view') ||
    auth()->user()->can('lifecycle.view') ||
    auth()->user()->can('asset_reports.view')
)

<details
    class="sidebar-group"
    {{ $isAssetsPropertiesActive ? 'open' : '' }}
>

    <summary
        class="sidebar-link {{ $isAssetsPropertiesActive ? 'active' : '' }}"
    >

        <i class="ri-building-4-line"></i>

        <span>Assets & Properties</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        {{-- =========================================================
             ASSET PORTFOLIO
        ========================================================== --}}

        <div class="sidebar-section-label">
            Asset Portfolio
        </div>


        {{-- Property Register --}}
        @can('malls.view')

            <a
                href="{{ route('admin.assets.malls.index') }}"
                class="sidebar-sublink {{ $isPropertyRegisterActive ? 'active' : '' }}"
            >

                <i class="ri-community-line"></i>

                <span>Property Register</span>

            </a>

        @endcan


        {{-- Building Register --}}
        @can('buildings.view')

            <a
                href="{{ route('admin.assets.buildings.index') }}"
                class="sidebar-sublink {{ $isBuildingRegisterActive ? 'active' : '' }}"
            >

                <i class="ri-building-2-line"></i>

                <span>Building Register</span>

            </a>

        @endcan



{{-- Floor & Space Hierarchy --}}
        @can('floors.view')

            <a
                href="{{ route('admin.assets.floors.index') }}"
                class="sidebar-sublink {{ $isFloorHierarchyActive ? 'active' : '' }}"
            >

                <i class="ri-stack-line"></i>

                <span>Floor & Space Hierarchy</span>

            </a>

        @endcan

        {{-- Space / Unit Register --}}
        @can('zones.view')

            <a
                href="{{ route('admin.assets.zones.index') }}"
                class="sidebar-sublink {{ $isSpaceZoneRegisterActive ? 'active' : '' }}"
            >

                <i class="ri-layout-grid-line"></i>

                <span>Space / Zone Register</span>

            </a>

        @endcan
        
@can('unit_types.view')

            <a
                href="{{ route('admin.assets.unit-types.index') }}"
                class="sidebar-sublink {{ $isUnitTypeRegisterActive ? 'active' : '' }}"
            >

                <i class="ri-layout-grid-line"></i>

                <span>Space / Unit Type Register</span>

            </a>

        @endcan

@can('units.view')

            <a
                href="{{ route('admin.assets.units.index') }}"
                class="sidebar-sublink {{ $isUnitRegisterActive ? 'active' : '' }}"
            >

                <i class="ri-layout-grid-line"></i>

                <span>Space / Unit  Register</span>

            </a>

        @endcan

        {{-- =========================================================
             ASSET REGISTER
        ========================================================== --}}

        <div class="sidebar-section-label">
            Asset Register
        </div>


        {{-- Assets --}}
        @can('assets.view')

            <a
                href="{{ route('admin.assets.assets.index') }}"
                class="sidebar-sublink {{ request()->routeIs('admin.assets.assets.*') ? 'active' : '' }}"
            >

                <i class="ri-archive-line"></i>

                <span>Asset Portfolio / Asset Register</span>

            </a>

        @endcan


        {{-- Asset Categories unit-documents -- }}
        @can('asset_categories.view')

            <a
                href="{{ route('admin.assets.asset-categories.index') }}"
                class="sidebar-sublink {{ request()->routeIs('admin.assets.asset-categories.*') ? 'active' : '' }}"
            >

                <i class="ri-folder-3-line"></i>

                <span>Asset Classification & Purpose</span>

            </a>

        @endcan

@can('unit_documents.view')

            <a
                href="{{ route('admin.assets.unit-documents.index') }}"
                class="sidebar-sublink {{ request()->routeIs('admin.assets.unit-documents.*') ? 'active' : '' }}"
            >

                <i class="ri-folder-3-line"></i>

                <span>Property Documents</span>

            </a>

        @endcan

        {{-- Departments --}}
        @can('departments.view')

            <a
                href="{{ route('admin.assets.departments.index') }}"
                class="sidebar-sublink {{ request()->routeIs('admin.assets.departments.*') ? 'active' : '' }}"
            >

                <i class="ri-team-line"></i>

                <span>Departments</span>

            </a>

        @endcan


        {{-- Unit Statuses --}}
        @can('unit_statuses.view')

            <a
                href="{{ route('admin.assets.unit-statuses.index') }}"
                class="sidebar-sublink {{ request()->routeIs('admin.assets.unit-statuses.*') ? 'active' : '' }}"
            >

                <i class="ri-checkbox-circle-line"></i>

                <span>Occupancy</span>

            </a>

        @endcan



        {{-- =========================================================
             ASSET CLASSIFICATION
        ========================================================== --}}

        <div class="sidebar-section-label">
            Asset Classification
        </div>


        {{-- Asset Type --}}
        @can('types.view')

            <a
                href="{{ route('admin.assets.types.index') }}"
                class="sidebar-sublink {{ $isAssetTypeActive ? 'active' : '' }}"
            >

                <i class="ri-price-tag-3-line"></i>

                <span>Asset Type</span>

            </a>

        @endcan


        {{-- Asset Purpose --}}
        @can('purposes.view')

            <a
                href="{{ route('admin.assets.purposes.index') }}"
                class="sidebar-sublink {{ $isAssetPurposeActive ? 'active' : '' }}"
            >

                <i class="ri-focus-3-line"></i>

                <span>Asset Purpose</span>

            </a>

        @endcan


        {{-- Industry Profile --}}
        @can('industry_profiles.view')

            <a
                href="{{ route('admin.assets.industry-profiles.index') }}"
                class="sidebar-sublink {{ $isIndustryProfileActive ? 'active' : '' }}"
            >

                <i class="ri-store-3-line"></i>

                <span>Industry Profile</span>

            </a>

        @endcan


        {{-- Operating Model --}}
        @can('operating_models.view')

            <a
                href="{{ route('admin.assets.operating-models.index') }}"
                class="sidebar-sublink {{ $isOperatingModelActive ? 'active' : '' }}"
            >

                <i class="ri-settings-4-line"></i>

                <span>Operating Model</span>

            </a>

        @endcan


        {{-- Ownership --}}
        @can('ownerships.view')

            <a
                href="{{ route('admin.assets.ownerships.index') }}"
                class="sidebar-sublink {{ $isOwnershipActive ? 'active' : '' }}"
            >

                <i class="ri-user-settings-line"></i>

                <span>Ownership</span>

            </a>

        @endcan


        {{-- Revenue Status --}}
        @can('revenue_statuses.view')

            <a
                href="{{ route('admin.assets.revenue-statuses.index') }}"
                class="sidebar-sublink {{ $isRevenueStatusActive ? 'active' : '' }}"
            >

                <i class="ri-money-dollar-circle-line"></i>

                <span>Revenue / Non-Revenue Status</span>

            </a>

        @endcan



        {{-- =========================================================
             ASSET OPERATIONS
        ========================================================== --}}

        <div class="sidebar-section-label">
            Asset Operations
        </div>


        {{-- Occupancy --}}
        @can('occupancy.view')

            <a
                href="{{ route('admin.assets.operations.occupancy.index') }}"
                class="sidebar-sublink {{ $isOccupancyActive ? 'active' : '' }}"
            >

                <i class="ri-user-shared-line"></i>

                <span>Occupancy</span>

            </a>

        @endcan


        {{-- Asset Performance --}}
        @can('performance.view')

            <a
                href="{{ route('admin.assets.operations.performance.index') }}"
                class="sidebar-sublink {{ $isAssetPerformanceActive ? 'active' : '' }}"
            >

                <i class="ri-bar-chart-box-line"></i>

                <span>Asset Performance</span>

            </a>

        @endcan


        {{-- Asset Condition --}}
        @can('conditions.view')

            <a
                href="{{ route('admin.assets.operations.conditions.index') }}"
                class="sidebar-sublink {{ $isAssetConditionActive ? 'active' : '' }}"
            >

                <i class="ri-health-book-line"></i>

                <span>Asset Condition</span>

            </a>

        @endcan


        {{-- CAPEX --}}
        @can('capex.view')

            <a
                href="{{ route('admin.assets.capex.index') }}"
                class="sidebar-sublink {{ $isCapexActive ? 'active' : '' }}"
            >

                <i class="ri-money-dollar-box-line"></i>

                <span>CAPEX</span>

            </a>

        @endcan


        {{-- Lifecycle Planning --}}
        @can('lifecycle.view')

            <a
                href="{{ route('admin.assets.operations.lifecycle.index') }}"
                class="sidebar-sublink {{ $isLifecyclePlanningActive ? 'active' : '' }}"
            >

                <i class="ri-refresh-line"></i>

                <span>Lifecycle Planning</span>

            </a>

        @endcan



        {{-- =========================================================
             INDUSTRY OPERATING PROFILES
        ========================================================== --}}

        <div class="sidebar-section-label">
            Industry Operating Profiles
        </div>


        @can('industry_profiles.view')

            <a
                href="{{ route('admin.assets.operations.industry-profiles.index') }}"
                class="sidebar-sublink {{ $isIndustryOperatingProfilesActive ? 'active' : '' }}"
            >

                <i class="ri-store-2-line"></i>

                <span>Industry Operating Profiles</span>

            </a>

        @endcan



        {{-- =========================================================
             FINANCIAL & ECONOMIC
        ========================================================== --}}

        @if(
            auth()->user()->can('incomes.view') ||
            auth()->user()->can('expenses.view') ||
            auth()->user()->can('economic_dashboard.view')
        )

            <div class="sidebar-section-label">
                Financial & Economic
            </div>


            {{-- Income --}}
            @can('incomes.view')

                <a
                    href="{{ route('admin.assets.incomes.index', ['asset' => 1]) }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.assets.incomes.*') ? 'active' : '' }}"
                >

                    <i class="ri-money-dollar-circle-line"></i>

                    <span>Asset Income</span>

                </a>

            @endcan


            {{-- Expenses --}}
            @can('expenses.view')

                <a
                    href="{{ route('admin.assets.expenses.index', ['asset' => 1]) }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.assets.expenses.*') ? 'active' : '' }}"
                >

                    <i class="ri-wallet-3-line"></i>

                    <span>Asset Expenses</span>

                </a>

            @endcan


            {{-- Economic Dashboard --}}
            @can('economic_dashboard.view')

                <a
                    href="{{ route('admin.assets.economic-dashboard') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.assets.economic-dashboard') ? 'active' : '' }}"
                >

                    <i class="ri-line-chart-line"></i>

                    <span>Economic Dashboard</span>

                </a>

            @endcan

        @endif



        {{-- =========================================================
             ASSET REPORTS & KPIs
        ========================================================== --}}

        @can('asset_reports.view')

            <div class="sidebar-section-label">
                Asset Reports & KPIs
            </div>


            {{-- Core Asset KPIs --}}
            <a
                href="{{ route('admin.assets.reports.core-kpis.index') }}"
                class="sidebar-sublink {{ $isCoreKpisActive ? 'active' : '' }}"
            >

                <i class="ri-dashboard-3-line"></i>

                <span>Core Asset KPIs</span>

            </a>


            {{-- Industry-Specific KPIs --}}
            <a
                href="{{ route('admin.assets.reports.industry-kpis.index') }}"
                class="sidebar-sublink {{ $isIndustryKpisActive ? 'active' : '' }}"
            >

                <i class="ri-bar-chart-grouped-line"></i>

                <span>Industry-Specific KPIs</span>

            </a>

        @endcan


    </div>

</details>

@endif
         {{-- =================================================
     LEASING
================================================== --}}
<details
    class="sidebar-group"
    {{ $isLeasingGroupActive ? 'open' : '' }}
>

    <summary class="sidebar-link {{ $isLeasingGroupActive ? 'active' : '' }}">

        <i class="ri-file-text-line"></i>

        <span>Leasing</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        <a href="{{ route('admin.leasing.dashboard') }}"
           class="sidebar-sublink {{ $isLeasingDashboardActive ? 'active' : '' }}">

            <i class="ri-dashboard-line"></i>

            <span>Dashboard</span>

        </a>


        <a href="{{ route('admin.leasing.index') }}"
           class="sidebar-sublink {{ $isLeasingActive ? 'active' : '' }}">

            <i class="ri-list-check"></i>

            <span>All Leasing</span>

        </a>


        <a href="{{ route('admin.leasing.proposals.index') }}"
           class="sidebar-sublink {{ $isLeaseProposalsActive ? 'active' : '' }}">

            <i class="ri-draft-line"></i>

            <span>Lease Proposals</span>

        </a>


        <a href="{{ route('admin.leasing.agreements.index') }}"
           class="sidebar-sublink {{ $isLeaseAgreementsActive ? 'active' : '' }}">

            <i class="ri-file-check-line"></i>

            <span>Lease Agreements</span>

        </a>


        <a href="{{ route('admin.leasing.terms.index') }}"
           class="sidebar-sublink {{ $isLeaseTermsActive ? 'active' : '' }}">

            <i class="ri-file-settings-line"></i>

            <span>Lease Terms</span>

        </a>


        <a href="{{ route('admin.leasing.documents.index') }}"
           class="sidebar-sublink {{ $isLeaseDocumentsActive ? 'active' : '' }}">

            <i class="ri-file-copy-line"></i>

            <span>Documents</span>

        </a>


        <a href="{{ route('admin.leasing.escalations.index') }}"
           class="sidebar-sublink {{ $isLeaseEscalationsActive ? 'active' : '' }}">

            <i class="ri-arrow-up-circle-line"></i>

            <span>Escalations</span>

        </a>


        <a href="{{ route('admin.leasing.renewals.index') }}"
           class="sidebar-sublink {{ $isLeaseRenewalsActive ? 'active' : '' }}">

            <i class="ri-refresh-line"></i>

            <span>Renewals</span>

        </a>


        <a href="{{ route('admin.leasing.terminations.index') }}"
           class="sidebar-sublink {{ $isLeaseTerminationsActive ? 'active' : '' }}">

            <i class="ri-close-circle-line"></i>

            <span>Terminations</span>

        </a>


        <a href="{{ route('admin.leasing.history.index') }}"
           class="sidebar-sublink {{ $isLeaseHistoryActive ? 'active' : '' }}">

            <i class="ri-history-line"></i>

            <span>History</span>

        </a>


    </div>

</details>


           {{-- =================================================
     TENANTS
================================================== --}}

<details
    class="sidebar-group"
    {{ $isTenantsGroupActive ? 'open' : '' }}
>

    <summary class="sidebar-link {{ $isTenantsGroupActive ? 'active' : '' }}">

        <i class="ri-group-line"></i>

        <span>Tenants</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        <a href="{{ url('/admin/tenants/dashboard') }}"
           class="sidebar-sublink {{ $isTenantsDashboardActive ? 'active' : '' }}">

            <i class="ri-dashboard-line"></i>

            <span>Dashboard</span>

        </a>


        <a href="{{ url('/admin/tenants') }}"
           class="sidebar-sublink {{ $isTenantsIndexActive ? 'active' : '' }}">

            <i class="ri-group-line"></i>

            <span>All Tenants</span>

        </a>


        <a href="{{ url('/admin/tenants?status=Active') }}"
           class="sidebar-sublink {{ $isActiveTenantsActive ? 'active' : '' }}">

            <i class="ri-user-follow-line"></i>

            <span>Active Tenants</span>

        </a>


        <a href="{{ url('/admin/tenants?status=Inactive') }}"
           class="sidebar-sublink {{ $isInactiveTenantsActive ? 'active' : '' }}">

            <i class="ri-user-unfollow-line"></i>

            <span>Inactive Tenants</span>

        </a>


        <a href="{{ url('/admin/tenants/leases') }}"
           class="sidebar-sublink {{ $isTenantLeasesActive ? 'active' : '' }}">

            <i class="ri-file-text-line"></i>

            <span>Tenant Leases</span>

        </a>


        <a href="{{ url('/admin/tenants/leases/expiry') }}"
           class="sidebar-sublink {{ $isLeaseExpiryActive ? 'active' : '' }}">

            <i class="ri-calendar-close-line"></i>

            <span>Lease Expiry</span>

        </a>


        <a href="{{ url('/admin/tenants/contacts') }}"
           class="sidebar-sublink {{ $isTenantContactsActive ? 'active' : '' }}">

            <i class="ri-contacts-line"></i>

            <span>Contacts</span>

        </a>


        <a href="{{ url('/admin/tenants/emergency-contacts') }}"
           class="sidebar-sublink {{ $isEmergencyContactsActive ? 'active' : '' }}">

            <i class="ri-phone-line"></i>

            <span>Emergency Contacts</span>

        </a>


        <a href="{{ url('/admin/tenants/documents') }}"
           class="sidebar-sublink {{ $isTenantDocumentsActive ? 'active' : '' }}">

            <i class="ri-file-copy-line"></i>

            <span>Documents</span>

        </a>


    </div>

</details>



        {{-- =================================================
     REVENUE
================================================== --}}

<details
    class="sidebar-group"
    {{ $isRevenueGroupActive ? 'open' : '' }}
>

    <summary class="sidebar-link {{ $isRevenueGroupActive ? 'active' : '' }}">

        <i class="ri-money-rupee-circle-line"></i>

        <span>Revenue</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        {{-- Dashboard --}}
        <a href="{{ url('/admin/revenue/dashboard') }}"
           class="sidebar-sublink {{ $isRevenueDashboardActive ? 'active' : '' }}">

            <i class="ri-dashboard-line"></i>

            <span>Dashboard</span>

        </a>


        {{-- BILLING --}}
        <div class="sidebar-section-label">
            Billing
        </div>


        <a href="{{ url('/admin/revenue/rent-schedules') }}"
           class="sidebar-sublink {{ $isRentSchedulesActive ? 'active' : '' }}">

            <i class="ri-calendar-schedule-line"></i>

            <span>Rent Schedules</span>

        </a>


        <a href="{{ url('/admin/revenue/invoices') }}"
           class="sidebar-sublink {{ $isInvoicesActive ? 'active' : '' }}">

            <i class="ri-file-list-3-line"></i>

            <span>Invoices</span>

        </a>


        {{-- COLLECTIONS --}}
        <div class="sidebar-section-label">
            Collections
        </div>


        <a href="{{ url('/admin/revenue/payments') }}"
           class="sidebar-sublink {{ $isPaymentsActive ? 'active' : '' }}">

            <i class="ri-bank-card-line"></i>

            <span>Payments</span>

        </a>


        <a href="{{ url('/admin/revenue/reconciliation') }}"
           class="sidebar-sublink {{ $isReconciliationActive ? 'active' : '' }}">

            <i class="ri-exchange-funds-line"></i>

            <span>Reconciliation</span>

        </a>


        {{-- OUTSTANDING --}}
        <div class="sidebar-section-label">
            Outstanding
        </div>


        <a href="{{ url('/admin/revenue/outstanding') }}"
           class="sidebar-sublink {{ $isOutstandingActive ? 'active' : '' }}">

            <i class="ri-money-dollar-circle-line"></i>

            <span>Outstanding</span>

        </a>


        <a href="{{ url('/admin/revenue/outstanding/overdue') }}"
           class="sidebar-sublink {{ $isOverdueActive ? 'active' : '' }}">

            <i class="ri-alarm-warning-line"></i>

            <span>Overdue</span>

        </a>


        <a href="{{ url('/admin/revenue/outstanding/tenants') }}"
           class="sidebar-sublink {{ $isTenantOutstandingActive ? 'active' : '' }}">

            <i class="ri-user-search-line"></i>

            <span>Tenant Outstanding</span>

        </a>


        {{-- REPORTS --}}
        <div class="sidebar-section-label">
            Reports
        </div>


        <a href="{{ url('/admin/revenue/reports/revenue') }}"
           class="sidebar-sublink {{ $isRevenueReportActive ? 'active' : '' }}">

            <i class="ri-bar-chart-line"></i>

            <span>Revenue Report</span>

        </a>


        <a href="{{ url('/admin/revenue/reports/collections') }}"
           class="sidebar-sublink {{ $isCollectionReportActive ? 'active' : '' }}">

            <i class="ri-file-chart-line"></i>

            <span>Collection Report</span>

        </a>


        <a href="{{ url('/admin/revenue/reports/tenant-wise') }}"
           class="sidebar-sublink {{ $isTenantWiseRevenueActive ? 'active' : '' }}">

            <i class="ri-user-chart-line"></i>

            <span>Tenant-wise Revenue</span>

        </a>


        <a href="{{ url('/admin/revenue/reports/aging') }}"
           class="sidebar-sublink {{ $isAgingReportActive ? 'active' : '' }}">

            <i class="ri-time-line"></i>

            <span>Aging Report</span>

        </a>


    </div>

</details>


{{-- =================================================
     FIT-OUT
================================================== --}}

<details
    class="sidebar-group"
    {{ $isFitoutGroupActive ? 'open' : '' }}
>

    <summary class="sidebar-link {{ $isFitoutGroupActive ? 'active' : '' }}">

        <i class="ri-hammer-line"></i>

        <span>Fit-Out</span>

        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

    </summary>


    <div class="sidebar-submenu">


        <a href="{{ route('admin.fitout.dashboard') }}"
           class="sidebar-sublink {{ $isFitoutDashboardActive ? 'active' : '' }}">

            <i class="ri-dashboard-line"></i>

            <span>Dashboard</span>

        </a>


        <a href="{{ route('admin.fitout.requests.index') }}"
           class="sidebar-sublink {{ $isFitoutRequestsActive ? 'active' : '' }}">

            <i class="ri-file-edit-line"></i>

            <span>Fit-Out Requests</span>

        </a>


        <a href="{{ route('admin.fitout.approvals.index') }}"
           class="sidebar-sublink {{ $isFitoutApprovalsActive ? 'active' : '' }}">

            <i class="ri-checkbox-circle-line"></i>

            <span>Approvals</span>

        </a>


        <a href="{{ route('admin.fitout.contractors.index') }}"
           class="sidebar-sublink {{ $isFitoutContractorsActive ? 'active' : '' }}">

            <i class="ri-team-line"></i>

            <span>Contractors</span>

        </a>


        <a href="{{ route('admin.fitout.inspections.index') }}"
           class="sidebar-sublink {{ $isFitoutInspectionsActive ? 'active' : '' }}">

            <i class="ri-search-eye-line"></i>

            <span>Inspections</span>

        </a>


        <a href="{{ route('admin.fitout.snags.index') }}"
           class="sidebar-sublink {{ $isFitoutSnagsActive ? 'active' : '' }}">

            <i class="ri-error-warning-line"></i>

            <span>Snags</span>

        </a>


        <a href="{{ route('admin.fitout.documents.index') }}"
           class="sidebar-sublink {{ $isFitoutDocumentsActive ? 'active' : '' }}">

            <i class="ri-file-copy-line"></i>

            <span>Documents</span>

        </a>


        <a href="{{ route('admin.fitout.handovers.index') }}"
           class="sidebar-sublink {{ $isFitoutHandoversActive ? 'active' : '' }}">

            <i class="ri-hand-coin-line"></i>

            <span>Handovers</span>

        </a>


    </div>

</details>



           {{-- =================================================
     PERFORMANCE
================================================== --}}

<a href="#"
   class="sidebar-link">

    <i class="ri-bar-chart-box-line"></i>

    <span>Performance</span>

</a>


{{-- =================================================
     ADMINISTRATION
================================================== --}}

@if(
    auth()->user()->can('users.view') ||
    auth()->user()->can('roles.view') ||
    auth()->user()->can('audit.view')
)

    <details
        class="sidebar-group"
        {{ $isAdministrationActive ? 'open' : '' }}
    >

        <summary class="sidebar-link {{ $isAdministrationActive ? 'active' : '' }}">

            <i class="ri-settings-3-line"></i>

            <span>Administration</span>

            <i class="ri-arrow-right-s-line sidebar-arrow"></i>

        </summary>


        <div class="sidebar-submenu">


            @can('users.view')

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-sublink {{ $isUsersActive ? 'active' : '' }}">

                    <i class="ri-user-line"></i>

                    <span>Users</span>

                </a>

            @endcan


            @can('roles.view')

                <a href="{{ route('admin.roles.index') }}"
                   class="sidebar-sublink {{ $isRolesActive ? 'active' : '' }}">

                    <i class="ri-shield-user-line"></i>

                    <span>Roles & Permissions</span>

                </a>

            @endcan


            @can('audit.view')

                <a href="{{ route('admin.users.audits', auth()->id()) }}"
                   class="sidebar-sublink {{ $isAuditActive ? 'active' : '' }}">

                    <i class="ri-file-list-3-line"></i>

                    <span>Audit Trail</span>

                </a>

            @endcan


        </div>

    </details>

@endif


        {{-- =================================================
     PROFILE
================================================== --}}

<!--a href="{{ route('profile.show') }}"
   class="sidebar-link {{ $isProfileActive ? 'active' : '' }}">

    <i class="ri-user-line"></i>

    <span>Profile</span>

</a-->

        {{-- =================================================
     PROFILE
================================================== --}}
 @can('audit.view')
<a href="{{ route('admin.activity-logs.index') }}"
   class="sidebar-link {{ $isActivityLogsActive ? 'active' : '' }}">

    <i class="ri-user-line"></i>

    <span>Activity Logs</span>

</a>

 @endcan

        @endauth

    </div>


    {{-- SIDEBAR FOOTER --}}

    <div class="sidebar-footer">

        <small>
            Hargeisa Mall Tracker
        </small>

    </div>

</aside>

<main class="app-main">

    <div class="app-content">

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="alert alert-warning">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        @yield('content')

    </div>

</main>

<footer id="footer">Hargeisa Mall Tracker built by Thewebtechi.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById("datechip").textContent = new Date().toLocaleDateString("en-GB", {
    day: "numeric",
    month: "long",
    year: "numeric"
  });
</script>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.app-sidebar');

    const toggle = document.getElementById('sidebarToggle');


    if (toggle && sidebar) {

        toggle.addEventListener('click', function () {

            sidebar.classList.toggle('show');

        });

    }


    const dateChip = document.getElementById('datechip');

    if (dateChip) {

        dateChip.textContent =
            new Date().toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });

    }

});

</script>

</body>
</html>