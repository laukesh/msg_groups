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


        {{-- USER --}}
        <div class="topbar-user">

            <div class="topbar-user-avatar">

                {{ strtoupper(
                    substr(auth()->user()->name ?? 'U', 0, 1)
                ) }}

            </div>


            <div class="topbar-user-info">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>

                    {{ auth()->user()
                        ->getRoleNames()
                        ->implode(', ') }}

                </small>

            </div>


            <i class="ri-arrow-down-s-line user-arrow"></i>

        </div>

    </div>

</header>

{{-- ============================================================
     LEFT SIDEBAR
============================================================ --}}

<aside class="app-sidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">

        <div class="sidebar-logo">

            <i class="ri-building-4-line"></i>

        </div>

        <div class="sidebar-brand-text">

            <strong>Hargeisa Mall</strong>

            <small>Mall Management</small>

        </div>

    </div>


    {{-- SIDEBAR MENU --}}
    <div class="sidebar-menu">

        @auth


            {{-- =================================================
                 DASHBOARD
            ================================================== --}}

            @can('dashboard.view')

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link
                   {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                    <i class="ri-dashboard-line"></i>

                    <span>Dashboard</span>

                </a>

            @endcan

            {{-- =========================================================
                 DEVELOPMENT MANAGEMENT
            ========================================================= --}}

            <details
                class="sidebar-group"
                {{ request()->is('admin/land/*')
                    || request()->is('admin/projects*')
                    || request()->is('admin/procurement*')
                    || request()->is('admin/feasibility-investment*')
                    || request()->is('admin/construction*')
                    ? 'open'
                    : ''
                }}
            >

                <summary class="sidebar-link">

                    <i class="ri-building-2-line"></i>

                    <span>Development Management</span>

                    <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                </summary>


                <div class="sidebar-submenu">


                    {{-- =====================================================
                         LAND ACQUISITION
                    ====================================================== --}}

                    <a
                        href="{{ route('admin.land.lands.index') }}"
                        class="sidebar-sublink"
                    >
                        <i class="ri-landscape-line"></i>
                        <span>Land Acquisition</span>
                    </a>


                    {{-- =====================================================
                         FEASIBILITY & INVESTMENT

                         Feasibility requires a LAND, therefore it should
                         be opened from Land Details rather than directly
                         from this global sidebar.
                    ====================================================== --}}

                    <a
                        href="{{ route('admin.feasibility-investment.index') }}"
                        class="sidebar-sublink"
                    >
                        <i class="ri-line-chart-line"></i>
                        <span>Feasibility & Investment</span>
                    </a>


                    {{-- =====================================================
                         PROJECTS MANAGEMENT
                    ====================================================== --}}

                    <a
                        href="{{ route('admin.projects.index') }}"
                        class="sidebar-sublink"
                    >
                        <i class="ri-building-line"></i>
                        <span>Projects Management</span>
                    </a>


                    {{-- =====================================================
                         PROCUREMENT
                    ====================================================== --}}

                    <a
                        href="{{ route('admin.procurement.plans.index') }}"
                        class="sidebar-sublink"
                    >
                        <i class="ri-shopping-cart-line"></i>
                        <span>Procurement</span>
                    </a>


                    {{-- =====================================================
                         CONSTRUCTION MANAGEMENT

                         Construction requires a PROJECT, so it should
                         normally be accessed from Project Dashboard.
                    ====================================================== --}}

                    <a
                        href="{{ route('admin.construction.index') }}"
                        class="sidebar-sublink"
                    >
                        <i class="ri-building-4-line"></i>
                        <span>Construction Management</span>
                    </a>


                    {{-- =====================================================
                         FUTURE MODULES
                    ====================================================== --}}

                    <a
                        href="#"
                        class="sidebar-sublink"
                    >
                        <i class="ri-layout-4-line"></i>
                        <span>Development Planning</span>
                    </a>


                    <a
                        href="#"
                        class="sidebar-sublink"
                    >
                        <i class="ri-draft-line"></i>
                        <span>Design Management</span>
                    </a>


                    <a
                        href="#"
                        class="sidebar-sublink"
                    >
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Handover & Closeout</span>
                    </a>


                </div>

            </details>

            {{-- =================================================
                 CONTRACT MANAGEMENT
            ================================================== --}}

            <a
                href="{{ route('admin.contract-management.index') }}"
                class="sidebar-link
                {{ request()->routeIs('admin.contract-management.*')
                    ? 'active'
                    : '' }}"
            >

                <i class="ri-file-list-3-line"></i>

                <span>Contract Management</span>

            </a>



            {{-- =================================================
                 ASSET OPERATIONS
            ================================================== --}}

            @if(
                auth()->user()->can('malls.view') ||
                auth()->user()->can('buildings.view') ||
                auth()->user()->can('floors.view') ||
                auth()->user()->can('zones.view') ||
                auth()->user()->can('unit_types.view') ||
                auth()->user()->can('units.view') ||            
                auth()->user()->can('departments.view') ||  
                auth()->user()->can('unit_statuses.view')||
                auth()->user()->can('assets.view')||
                auth()->user()->can('asset_categories.view')||
                auth()->user()->can('complaints.view')
            )

                <details class="sidebar-group"
                    {{ request()->routeIs('admin.assets.*') ? 'open' : '' }}>

                    <summary class="sidebar-link">

                        <i class="ri-building-line"></i>

                        <span>Assets</span>

                        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                    </summary>


                    <div class="sidebar-submenu">

                        @can('malls.view')

                            <a href="{{ route('admin.assets.malls.index') }}"
                               class="sidebar-sublink">

                                Malls

                            </a>

                        @endcan


                        @can('buildings.view')

                            <a href="{{ route('admin.assets.buildings.index') }}"
                               class="sidebar-sublink">

                                Buildings

                            </a>

                        @endcan


                        @can('floors.view')

                            <a href="{{ route('admin.assets.floors.index') }}"
                               class="sidebar-sublink">

                                Floors

                            </a>

                        @endcan


                        @can('zones.view')

                            <a href="{{ route('admin.assets.zones.index') }}"
                               class="sidebar-sublink">

                                Zones

                            </a>

                        @endcan


                        @can('unit_types.view')

                            <a href="{{ route('admin.assets.unit-types.index') }}"
                               class="sidebar-sublink">

                                Unit Types

                            </a>

                        @endcan


                        @can('units.view')

                            <a href="{{ route('admin.assets.units.index') }}"
                               class="sidebar-sublink">

                                Units

                            </a>

                        @endcan
                        @can('asset_categories.view')

                            <a href="{{ route('admin.assets.asset-categories.index') }}"
                               class="sidebar-sublink">

                                Asset Categories


                            </a>

                        @endcan
                        @can('assets.view')

                            <a href="{{ route('admin.assets.assets.index') }}"
                               class="sidebar-sublink">

                                Assets

                            </a>

                        @endcan
                        
                        @can('departments.view')

                            <a href="{{ route('admin.assets.departments.index') }}"
                               class="sidebar-sublink">

                                Departments

                            </a>    
                        @endcan

                    </div>

                </details>

            @endif



            {{-- =================================================
                 LEASING
            ================================================== --}}

            <details class="sidebar-group"
                {{ request()->routeIs('admin.leasing.*') ? 'open' : '' }}>

                <summary class="sidebar-link">

                    <i class="ri-file-text-line"></i>

                    <span>Leasing</span>

                    <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                </summary>


                <div class="sidebar-submenu">


                    <a href="{{ route('admin.leasing.dashboard') }}"
                       class="sidebar-sublink">

                        Dashboard

                    </a>


                    <a href="{{ route('admin.leasing.index') }}"
                       class="sidebar-sublink">

                        All Leasing

                    </a>


                    <a href="{{ route('admin.leasing.proposals.index') }}"
                       class="sidebar-sublink">

                        Lease Proposals

                    </a>


                    <a href="{{ route('admin.leasing.agreements.index') }}"
                       class="sidebar-sublink">

                        Lease Agreements

                    </a>


                    <a href="{{ route('admin.leasing.terms.index') }}"
                       class="sidebar-sublink">

                        Lease Terms

                    </a>


                    <a href="{{ route('admin.leasing.documents.index') }}"
                       class="sidebar-sublink">

                        Documents

                    </a>


                    <a href="{{ route('admin.leasing.escalations.index') }}"
                       class="sidebar-sublink">

                        Escalations

                    </a>


                    <a href="{{ route('admin.leasing.renewals.index') }}"
                       class="sidebar-sublink">

                        Renewals

                    </a>


                    <a href="{{ route('admin.leasing.terminations.index') }}"
                       class="sidebar-sublink">

                        Terminations

                    </a>


                    <a href="{{ route('admin.leasing.history.index') }}"
                       class="sidebar-sublink">

                        History

                    </a>

                </div>

            </details>



            {{-- =================================================
                 TENANTS
            ================================================== --}}

            <details class="sidebar-group"
                {{ request()->is('admin/tenants*') ? 'open' : '' }}>

                <summary class="sidebar-link">

                    <i class="ri-group-line"></i>

                    <span>Tenants</span>

                    <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                </summary>


                <div class="sidebar-submenu">

                    <a href="{{ url('/admin/tenants/dashboard') }}"
                       class="sidebar-sublink">

                        Dashboard

                    </a>


                    <a href="{{ url('/admin/tenants') }}"
                       class="sidebar-sublink">

                        All Tenants

                    </a>


                    <a href="{{ url('/admin/tenants?status=Active') }}"
                       class="sidebar-sublink">

                        Active Tenants

                    </a>


                    <a href="{{ url('/admin/tenants?status=Inactive') }}"
                       class="sidebar-sublink">

                        Inactive Tenants

                    </a>


                    <a href="{{ url('/admin/tenants/leases') }}"
                       class="sidebar-sublink">

                        Tenant Leases

                    </a>


                    <a href="{{ url('/admin/tenants/leases/expiry') }}"
                       class="sidebar-sublink">

                        Lease Expiry

                    </a>


                    <a href="{{ url('/admin/tenants/contacts') }}"
                       class="sidebar-sublink">

                        Contacts

                    </a>


                    <a href="{{ url('/admin/tenants/emergency-contacts') }}"
                       class="sidebar-sublink">

                        Emergency Contacts

                    </a>


                    <a href="{{ url('/admin/tenants/documents') }}"
                       class="sidebar-sublink">

                        Documents

                    </a>

                </div>

            </details>



            {{-- =================================================
                 REVENUE
            ================================================== --}}

            <details class="sidebar-group"
                {{ request()->is('admin/revenue*') ? 'open' : '' }}>

                <summary class="sidebar-link">

                    <i class="ri-money-rupee-circle-line"></i>

                    <span>Revenue</span>

                    <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                </summary>


                <div class="sidebar-submenu">


                    <a href="{{ url('/admin/revenue/dashboard') }}"
                       class="sidebar-sublink">

                        Dashboard

                    </a>


                    <div class="sidebar-section-label">
                        Billing
                    </div>


                    <a href="{{ url('/admin/revenue/rent-schedules') }}"
                       class="sidebar-sublink">

                        Rent Schedules

                    </a>


                    <a href="{{ url('/admin/revenue/invoices') }}"
                       class="sidebar-sublink">

                        Invoices

                    </a>


                    <div class="sidebar-section-label">
                        Collections
                    </div>


                    <a href="{{ url('/admin/revenue/payments') }}"
                       class="sidebar-sublink">

                        Payments

                    </a>


                    <a href="{{ url('/admin/revenue/reconciliation') }}"
                       class="sidebar-sublink">

                        Reconciliation

                    </a>


                    <div class="sidebar-section-label">
                        Outstanding
                    </div>


                    <a href="{{ url('/admin/revenue/outstanding') }}"
                       class="sidebar-sublink">

                        Outstanding

                    </a>


                    <a href="{{ url('/admin/revenue/outstanding/overdue') }}"
                       class="sidebar-sublink">

                        Overdue

                    </a>


                    <a href="{{ url('/admin/revenue/outstanding/tenants') }}"
                       class="sidebar-sublink">

                        Tenant Outstanding

                    </a>


                    <div class="sidebar-section-label">
                        Reports
                    </div>


                    <a href="{{ url('/admin/revenue/reports/revenue') }}"
                       class="sidebar-sublink">

                        Revenue Report

                    </a>


                    <a href="{{ url('/admin/revenue/reports/collections') }}"
                       class="sidebar-sublink">

                        Collection Report

                    </a>


                    <a href="{{ url('/admin/revenue/reports/tenant-wise') }}"
                       class="sidebar-sublink">

                        Tenant-wise Revenue

                    </a>


                    <a href="{{ url('/admin/revenue/reports/aging') }}"
                       class="sidebar-sublink">

                        Aging Report

                    </a>

                </div>

            </details>



            {{-- =================================================
                 FIT-OUT
            ================================================== --}}

            <details class="sidebar-group"
                {{ request()->routeIs('admin.fitout.*') ? 'open' : '' }}>

                <summary class="sidebar-link">

                    <i class="ri-hammer-line"></i>

                    <span>Fit-Out</span>

                    <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                </summary>


                <div class="sidebar-submenu">


                    <a href="{{ route('admin.fitout.dashboard') }}"
                       class="sidebar-sublink">

                        Dashboard

                    </a>


                    <a href="{{ route('admin.fitout.requests.index') }}"
                       class="sidebar-sublink">

                        Fit-Out Requests

                    </a>


                    <a href="{{ route('admin.fitout.approvals.index') }}"
                       class="sidebar-sublink">

                        Approvals

                    </a>


                    <a href="{{ route('admin.fitout.contractors.index') }}"
                       class="sidebar-sublink">

                        Contractors

                    </a>


                    <a href="{{ route('admin.fitout.inspections.index') }}"
                       class="sidebar-sublink">

                        Inspections

                    </a>


                    <a href="{{ route('admin.fitout.snags.index') }}"
                       class="sidebar-sublink">

                        Snags

                    </a>


                    <a href="{{ route('admin.fitout.documents.index') }}"
                       class="sidebar-sublink">

                        Documents

                    </a>


                    <a href="{{ route('admin.fitout.handovers.index') }}"
                       class="sidebar-sublink">

                        Handovers

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

                <details class="sidebar-group">

                    <summary class="sidebar-link">

                        <i class="ri-settings-3-line"></i>

                        <span>Administration</span>

                        <i class="ri-arrow-right-s-line sidebar-arrow"></i>

                    </summary>


                    <div class="sidebar-submenu">


                        @can('users.view')

                            <a href="{{ route('admin.users.index') }}"
                               class="sidebar-sublink">

                                Users

                            </a>

                        @endcan


                        @can('roles.view')

                            <a href="{{ route('admin.roles.index') }}"
                               class="sidebar-sublink">

                                Roles & Permissions

                            </a>

                        @endcan


                        @can('audit.view')

                            <a href="{{ route(
                                'admin.users.audits',
                                auth()->id()
                            ) }}"
                               class="sidebar-sublink">

                                Audit Trail

                            </a>

                        @endcan

                    </div>

                </details>

            @endif



            {{-- =================================================
                 PROFILE
            ================================================== --}}

            <a href="{{ route('profile.show') }}"
               class="sidebar-link">

                <i class="ri-user-line"></i>

                <span>Profile</span>

            </a>


            {{-- =================================================
                 LOGOUT
            ================================================== --}}

            <form action="{{ route('logout') }}"
                  method="POST">

                @csrf

                <button type="submit"
                        class="sidebar-link sidebar-logout">

                    <i class="ri-logout-box-line"></i>

                    <span>Logout</span>

                </button>

            </form>


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