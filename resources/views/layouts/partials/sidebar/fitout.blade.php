@php
    $isFitoutGroupActive       = request()->routeIs('admin.fitout.*');
    $isFitoutDashboardActive   = request()->routeIs('admin.fitout.dashboard');
    $isFitoutRequestsActive    = request()->routeIs('admin.fitout.requests.*');
    $isFitoutApprovalsActive   = request()->routeIs('admin.fitout.approvals.*');
    $isFitoutContractorsActive = request()->routeIs('admin.fitout.contractors.*');
    $isFitoutInspectionsActive = request()->routeIs('admin.fitout.inspections.*');
    $isFitoutSnagsActive       = request()->routeIs('admin.fitout.snags.*');
    $isFitoutDocumentsActive   = request()->routeIs('admin.fitout.documents.*');
    $isFitoutHandoversActive   = request()->routeIs('admin.fitout.handovers.*');
@endphp

<details class="sidebar-group" {{ $isFitoutGroupActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isFitoutGroupActive ? 'active' : '' }}">
        <i class="ri-hammer-line"></i>
        <span>Fit-Out</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        <a href="{{ route('admin.fitout.dashboard') }}" class="sidebar-sublink {{ $isFitoutDashboardActive ? 'active' : '' }}">
            <i class="ri-dashboard-line"></i><span>Dashboard</span>
        </a>

        <a href="{{ route('admin.fitout.requests.index') }}" class="sidebar-sublink {{ $isFitoutRequestsActive ? 'active' : '' }}">
            <i class="ri-file-edit-line"></i><span>Fit-Out Requests</span>
        </a>

        <a href="{{ route('admin.fitout.approvals.index') }}" class="sidebar-sublink {{ $isFitoutApprovalsActive ? 'active' : '' }}">
            <i class="ri-checkbox-circle-line"></i><span>Approvals</span>
        </a>

        <a href="{{ route('admin.fitout.contractors.index') }}" class="sidebar-sublink {{ $isFitoutContractorsActive ? 'active' : '' }}">
            <i class="ri-team-line"></i><span>Contractors</span>
        </a>

        <a href="{{ route('admin.fitout.inspections.index') }}" class="sidebar-sublink {{ $isFitoutInspectionsActive ? 'active' : '' }}">
            <i class="ri-search-eye-line"></i><span>Inspections</span>
        </a>

        <a href="{{ route('admin.fitout.snags.index') }}" class="sidebar-sublink {{ $isFitoutSnagsActive ? 'active' : '' }}">
            <i class="ri-error-warning-line"></i><span>Snags</span>
        </a>

        <a href="{{ route('admin.fitout.documents.index') }}" class="sidebar-sublink {{ $isFitoutDocumentsActive ? 'active' : '' }}">
            <i class="ri-file-copy-line"></i><span>Documents</span>
        </a>

        <a href="{{ route('admin.fitout.handovers.index') }}" class="sidebar-sublink {{ $isFitoutHandoversActive ? 'active' : '' }}">
            <i class="ri-hand-coin-line"></i><span>Handovers</span>
        </a>

    </div>
</details>
