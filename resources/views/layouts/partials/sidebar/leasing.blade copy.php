@php
    $isLeasingGroupActive      = request()->routeIs('admin.leasing.*');
    $isLeasingDashboardActive  = request()->routeIs('admin.leasing.dashboard');
    $isLeasingActive           = request()->routeIs('admin.leasing.index');
    $isLeaseProposalsActive    = request()->routeIs('admin.leasing.proposals.*');
    $isLeaseAgreementsActive   = request()->routeIs('admin.leasing.agreements.*');
    $isLeaseTermsActive        = request()->routeIs('admin.leasing.terms.*');
    $isLeaseDocumentsActive    = request()->routeIs('admin.leasing.documents.*');
    $isLeaseEscalationsActive  = request()->routeIs('admin.leasing.escalations.*');
    $isLeaseRenewalsActive     = request()->routeIs('admin.leasing.renewals.*');
    $isLeaseTerminationsActive = request()->routeIs('admin.leasing.terminations.*');
    $isLeaseHistoryActive      = request()->routeIs('admin.leasing.history.*');
@endphp

<details class="sidebar-group" {{ $isLeasingGroupActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isLeasingGroupActive ? 'active' : '' }}">
        <i class="ri-file-text-line"></i>
        <span>Leasing</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        <a href="{{ route('admin.leasing.dashboard') }}" class="sidebar-sublink {{ $isLeasingDashboardActive ? 'active' : '' }}">
            <i class="ri-dashboard-line"></i><span>Dashboard</span>
        </a>

        <a href="{{ route('admin.leasing.index') }}" class="sidebar-sublink {{ $isLeasingActive ? 'active' : '' }}">
            <i class="ri-list-check"></i><span>All Leasing</span>
        </a>

        <a href="{{ route('admin.leasing.proposals.index') }}" class="sidebar-sublink {{ $isLeaseProposalsActive ? 'active' : '' }}">
            <i class="ri-draft-line"></i><span>Lease Proposals</span>
        </a>

        <a href="{{ route('admin.leasing.agreements.index') }}" class="sidebar-sublink {{ $isLeaseAgreementsActive ? 'active' : '' }}">
            <i class="ri-file-check-line"></i><span>Lease Agreements</span>
        </a>

        <a href="{{ route('admin.leasing.terms.index') }}" class="sidebar-sublink {{ $isLeaseTermsActive ? 'active' : '' }}">
            <i class="ri-file-settings-line"></i><span>Lease Terms</span>
        </a>

        <a href="{{ route('admin.leasing.documents.index') }}" class="sidebar-sublink {{ $isLeaseDocumentsActive ? 'active' : '' }}">
            <i class="ri-file-copy-line"></i><span>Documents</span>
        </a>

        <a href="{{ route('admin.leasing.escalations.index') }}" class="sidebar-sublink {{ $isLeaseEscalationsActive ? 'active' : '' }}">
            <i class="ri-arrow-up-circle-line"></i><span>Escalations</span>
        </a>

        <a href="{{ route('admin.leasing.renewals.index') }}" class="sidebar-sublink {{ $isLeaseRenewalsActive ? 'active' : '' }}">
            <i class="ri-refresh-line"></i><span>Renewals</span>
        </a>

        <a href="{{ route('admin.leasing.terminations.index') }}" class="sidebar-sublink {{ $isLeaseTerminationsActive ? 'active' : '' }}">
            <i class="ri-close-circle-line"></i><span>Terminations</span>
        </a>

        <a href="{{ route('admin.leasing.history.index') }}" class="sidebar-sublink {{ $isLeaseHistoryActive ? 'active' : '' }}">
            <i class="ri-history-line"></i><span>History</span>
        </a>

    </div>
</details>
