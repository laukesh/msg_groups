@php
    $isLandActive         = request()->routeIs('admin.land.*');
    $isFeasibilityActive  = request()->routeIs('admin.feasibility-investment.*');
    $isProjectsActive     = request()->routeIs('admin.projects.*');
    $isProcurementActive  = request()->routeIs('admin.procurement.*');
    $isConstructionActive = request()->routeIs('admin.construction.*');
    $isDesignActive       = request()->routeIs('admin.design-management.*');
    $isCommissioningActive = request()->routeIs('admin.commissioning.*');
    $isHandoverActive     = request()->routeIs('admin.handover.*');

    $isDevelopmentActive = $isLandActive || $isFeasibilityActive || $isProjectsActive
        || $isProcurementActive || $isConstructionActive || $isDesignActive
        || $isCommissioningActive || $isHandoverActive;
@endphp

<details class="sidebar-group" {{ $isDevelopmentActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isDevelopmentActive ? 'active' : '' }}">
        <i class="ri-building-2-line"></i>
        <span>Development Management</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        <a href="{{ route('admin.land.lands.index') }}"
           class="sidebar-sublink {{ $isLandActive ? 'active' : '' }}">
            <i class="ri-landscape-line"></i><span>Land Acquisition</span>
        </a>

        <a href="{{ route('admin.feasibility-investment.index') }}"
           class="sidebar-sublink {{ $isFeasibilityActive ? 'active' : '' }}">
            <i class="ri-line-chart-line"></i><span>Feasibility & Investment</span>
        </a>

        <a href="{{ route('admin.projects.index') }}"
           class="sidebar-sublink {{ $isProjectsActive ? 'active' : '' }}">
            <i class="ri-building-line"></i><span>Projects Management</span>
        </a>

        <a href="{{ route('admin.procurement.index') }}"
           class="sidebar-sublink {{ $isProcurementActive ? 'active' : '' }}">
            <i class="ri-shopping-cart-line"></i><span>Procurement</span>
        </a>

        <a href="{{ route('admin.construction.index') }}"
           class="sidebar-sublink {{ $isConstructionActive ? 'active' : '' }}">
            <i class="ri-building-4-line"></i><span>Construction Management</span>
        </a>

        <a href="{{ route('admin.design-management.index') }}"
           class="sidebar-sublink {{ $isDesignActive ? 'active' : '' }}">
            <i class="ri-draft-line"></i><span>Design Management</span>
        </a>

        <a href="{{ route('admin.commissioning.index') }}"
           class="sidebar-sublink {{ $isCommissioningActive ? 'active' : '' }}">
            <i class="ri-settings-3-line"></i><span>Commissioning</span>
        </a>

        <a href="{{ route('admin.handover.index') }}"
           class="sidebar-sublink {{ $isHandoverActive ? 'active' : '' }}">
            <i class="ri-checkbox-circle-line"></i><span>Handover & Closeout</span>
        </a>

    </div>
</details>
