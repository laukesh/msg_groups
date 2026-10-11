@canany([
    'malls.view', 'buildings.view', 'floors.view', 'zones.view', 'unit_types.view',
    'units.view', 'departments.view', 'unit_statuses.view', 'assets.view',
    'asset_categories.view', 'purposes.view', 'types.view', 'industry_profiles.view',
    'operating_models.view', 'ownerships.view', 'revenue_statuses.view', 'occupancy.view',
    'conditions.view', 'performance.view', 'capex.view', 'lifecycle.view', 'asset_reports.view','admin.assets.areas.*'
    ,'admin.assets.areas.reports.*','admin.assets.areas.types.*' ,'admin.assets.reports.*','admin.assets.revisions.*'
])

@php
    // Portfolio
    $isPropertyRegisterActive  = request()->routeIs('admin.assets.malls.*');
    $isBuildingRegisterActive  = request()->routeIs('admin.assets.buildings.*');
    $isFloorHierarchyActive    = request()->routeIs('admin.assets.floors.*');
    $isSpaceZoneRegisterActive = request()->routeIs('admin.assets.zones.*');
    $isUnitTypeRegisterActive  = request()->routeIs('admin.assets.unit-types.*');
    $isUnitRegisterActive      = request()->routeIs('admin.assets.units.*');

    // Register
    $isAssetRegisterActive   = request()->routeIs('admin.assets.assets.*');
    $isAssetCategoriesActive = request()->routeIs('admin.assets.asset-categories.*');
    $isUnitDocumentsActive   = request()->routeIs('admin.assets.unit-documents.*');
    $isDepartmentsActive     = request()->routeIs('admin.assets.departments.*');
    $isUnitStatusesActive    = request()->routeIs('admin.assets.unit-statuses.*');

    // Classification
    $isAssetTypeActive       = request()->routeIs('admin.assets.types.*');
    $isAssetPurposeActive    = request()->routeIs('admin.assets.purposes.*');
    $isIndustryProfileActive = request()->routeIs('admin.assets.industry-profiles.*');
    $isOperatingModelActive  = request()->routeIs('admin.assets.operating-models.*');
    $isOwnershipActive       = request()->routeIs('admin.assets.ownerships.*');
    $isRevenueStatusActive   = request()->routeIs('admin.assets.revenue-statuses.*');

    // Operations
    $isOccupancyActive        = request()->routeIs('admin.assets.operations.occupancy.*');
    $isAssetConditionActive   = request()->routeIs('admin.assets.operations.conditions.*');
    $isAssetPerformanceActive = request()->routeIs('admin.assets.operations.performance.*')
                             || request()->routeIs('admin.assets.performance.*');
    $isCapexActive            = request()->routeIs('admin.assets.capex.*');
    $isLifecyclePlanningActive = request()->routeIs('admin.assets.operations.lifecycle.*');
    $isIndustryOperatingProfilesActive = request()->routeIs('admin.assets.operations.industry-profiles.*');

    // Financial
    $isIncomesActive   = request()->routeIs('admin.assets.incomes.*');
    $isExpensesActive  = request()->routeIs('admin.assets.expenses.*');
    $isEconomicActive  = request()->routeIs('admin.assets.economic-dashboard');

    // Reports
    $isCoreKpisActive     = request()->routeIs('admin.assets.reports.core-kpis.*');
    $isIndustryKpisActive = request()->routeIs('admin.assets.reports.industry-kpis.*');
    $isGLAReportActive    = request()->routeIs('admin.assets.areas.reports.gla'); 
    $isNLAReportActive    = request()->routeIs('admin.assets.areas.reports.nla');
    $isAreaSummaryActive  = request()->routeIs('admin.assets.areas.reports.summary');
    $isAreaManagementActive = request()->routeIs('admin.assets.areas.*');
    

    // Whole group
    $isAssetsPropertiesActive = request()->routeIs([
        'admin.assets.malls.*', 'admin.assets.buildings.*', 'admin.assets.floors.*',
        'admin.assets.zones.*', 'admin.assets.unit-types.*', 'admin.assets.units.*',
        'admin.assets.departments.*', 'admin.assets.unit-statuses.*', 'admin.assets.assets.*',
        'admin.assets.asset-categories.*', 'admin.assets.unit-documents.*',
        'admin.assets.purposes.*', 'admin.assets.industry-profiles.*',
        'admin.assets.operating-models.*', 'admin.assets.ownerships.*',
        'admin.assets.revenue-statuses.*', 'admin.assets.types.*', 'admin.assets.capex.*',
        'admin.assets.operations.*', 'admin.assets.expenses.*', 'admin.assets.incomes.*',
        'admin.assets.economic-dashboard', 'admin.assets.performance.*', 'admin.assets.reports.*',
        'admin.assets.areas.*','admin.assets.areas.reports.*','admin.assets.areas.types.*',
        'admin.assets.areas.measurements.*','admin.assets.revisions.*','admin.assets.areas.approvals.*'
    ]);
@endphp

<details class="sidebar-group" {{ $isAssetsPropertiesActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isAssetsPropertiesActive ? 'active' : '' }}">
        <i class="ri-building-4-line"></i>
        <span>Assets & Properties</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">
        @include('layouts.partials.sidebar.assets.portfolio')
        @include('layouts.partials.sidebar.assets.register')
        @include('layouts.partials.sidebar.assets.classification')
        @include('layouts.partials.sidebar.assets.operations')
        @include('layouts.partials.sidebar.assets.financial')
        @include('layouts.partials.sidebar.assets.reports')
        @include('layouts.partials.sidebar.assets.gla')
    </div>
</details>

@endcanany
