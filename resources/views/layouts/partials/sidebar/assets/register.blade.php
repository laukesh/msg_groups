
<div class="sidebar-section-label">Asset Register</div>

@can('assets.view')
    <a href="{{ route('admin.assets.assets.index') }}" class="sidebar-sublink {{ $isAssetRegisterActive ? 'active' : '' }}">
        <i class="ri-archive-line"></i> <span>Asset Portfolio / Asset Register</span>
    </a>
@endcan

@can('asset_categories.view')
    <a href="{{ route('admin.assets.asset-categories.index') }}" class="sidebar-sublink {{ $isAssetCategoriesActive ? 'active' : '' }}">
        <i class="ri-folder-3-line"></i> <span>Asset Classification & Purpose</span>
    </a>
@endcan

@can('unit_documents.view')
    <a href="{{ route('admin.assets.unit-documents.index') }}" class="sidebar-sublink {{ $isUnitDocumentsActive ? 'active' : '' }}">
        <i class="ri-folder-3-line"></i> <span>Property Documents</span>
    </a>
@endcan

@can('departments.view')
    <a href="{{ route('admin.assets.departments.index') }}" class="sidebar-sublink {{ $isDepartmentsActive ? 'active' : '' }}">
        <i class="ri-team-line"></i> <span>Departments</span>
    </a>
@endcan

@can('unit_statuses.view')
    <a href="{{ route('admin.assets.unit-statuses.index') }}" class="sidebar-sublink {{ $isUnitStatusesActive ? 'active' : '' }}">
        <i class="ri-checkbox-circle-line"></i> <span>Occupancy</span>
    </a>
@endcan
