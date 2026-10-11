<div class="sidebar-section-label">Asset Operations</div>

@can('occupancy.view')
    <a href="{{ route('admin.assets.operations.occupancy.index') }}" class="sidebar-sublink {{ $isOccupancyActive ? 'active' : '' }}">
        <i class="ri-user-shared-line"></i> <span>Occupancy</span>
    </a>
@endcan

@can('performance.view')
    <a href="{{ route('admin.assets.operations.performance.index') }}" class="sidebar-sublink {{ $isAssetPerformanceActive ? 'active' : '' }}">
        <i class="ri-bar-chart-box-line"></i> <span>Asset Performance</span>
    </a>
@endcan

@can('conditions.view')
    <a href="{{ route('admin.assets.operations.conditions.index') }}" class="sidebar-sublink {{ $isAssetConditionActive ? 'active' : '' }}">
        <i class="ri-health-book-line"></i> <span>Asset Condition</span>
    </a>
@endcan

@can('capex.view')
    <a href="{{ route('admin.assets.capex.index') }}" class="sidebar-sublink {{ $isCapexActive ? 'active' : '' }}">
        <i class="ri-money-dollar-box-line"></i> <span>CAPEX</span>
    </a>
@endcan

@can('lifecycle.view')
    <a href="{{ route('admin.assets.operations.lifecycle.index') }}" class="sidebar-sublink {{ $isLifecyclePlanningActive ? 'active' : '' }}">
        <i class="ri-refresh-line"></i><span>Lifecycle Planning</span>
    </a>
@endcan

{{-- Industry Operating Profiles --}}
<div class="sidebar-section-label">Industry Operating Profiles</div>

@can('industry_profiles.view')
    <a href="{{ route('admin.assets.operations.industry-profiles.index') }}" class="sidebar-sublink {{ $isIndustryOperatingProfilesActive ? 'active' : '' }}">
        <i class="ri-store-2-line"></i><span>Industry Operating Profiles</span>
    </a>
@endcan
