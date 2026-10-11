<div class="sidebar-section-label">Asset Portfolio</div>

@can('malls.view')
    <a href="{{ route('admin.assets.malls.index') }}" class="sidebar-sublink {{ $isPropertyRegisterActive ? 'active' : '' }}">
        <i class="ri-community-line"></i> <span>Property Register</span>
    </a>
@endcan

@can('buildings.view')
    <a href="{{ route('admin.assets.buildings.index') }}" class="sidebar-sublink {{ $isBuildingRegisterActive ? 'active' : '' }}">
        <i class="ri-building-2-line"></i> <span>Building Register</span>
    </a>
@endcan

@can('floors.view')
    <a href="{{ route('admin.assets.floors.index') }}" class="sidebar-sublink {{ $isFloorHierarchyActive ? 'active' : '' }}">
        <i class="ri-stack-line"></i> <span>Floor & Space Hierarchy</span>
    </a>
@endcan

@can('zones.view')
    <a href="{{ route('admin.assets.zones.index') }}" class="sidebar-sublink {{ $isSpaceZoneRegisterActive ? 'active' : '' }}">
        <i class="ri-layout-grid-line"></i> <span>Space / Zone Register</span>
    </a>
@endcan

@can('unit_types.view')
    <a href="{{ route('admin.assets.unit-types.index') }}" class="sidebar-sublink {{ $isUnitTypeRegisterActive ? 'active' : '' }}">
        <i class="ri-layout-grid-line"></i> <span>Space / Unit Type Register</span>
    </a>
@endcan

@can('units.view')
    <a href="{{ route('admin.assets.units.index') }}" class="sidebar-sublink {{ $isUnitRegisterActive ? 'active' : '' }}">
        <i class="ri-layout-grid-line"></i> <span>Space / Unit Register</span>
    </a>
@endcan
