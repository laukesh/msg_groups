<div class="sidebar-section-label">Asset Classification</div>

@can('types.view')
    <a href="{{ route('admin.assets.types.index') }}" class="sidebar-sublink {{ $isAssetTypeActive ? 'active' : '' }}">
        <i class="ri-price-tag-3-line"> </i><span>Asset Type</span>
    </a>
@endcan

@can('purposes.view')
    <a href="{{ route('admin.assets.purposes.index') }}" class="sidebar-sublink {{ $isAssetPurposeActive ? 'active' : '' }}">
        <i class="ri-focus-3-line"></i> <span>Asset Purpose</span>
    </a>
@endcan

@can('industry_profiles.view')
    <a href="{{ route('admin.assets.industry-profiles.index') }}" class="sidebar-sublink {{ $isIndustryProfileActive ? 'active' : '' }}">
        <i class="ri-store-3-line"></i> <span>Industry Profile</span>
    </a>
@endcan

@can('operating_models.view')
    <a href="{{ route('admin.assets.operating-models.index') }}" class="sidebar-sublink {{ $isOperatingModelActive ? 'active' : '' }}">
        <i class="ri-settings-4-line"></i> <span>Operating Model</span>
    </a>
@endcan

@can('ownerships.view')
    <a href="{{ route('admin.assets.ownerships.index') }}" class="sidebar-sublink {{ $isOwnershipActive ? 'active' : '' }}">
        <i class="ri-user-settings-line"></i> <span>Ownership</span>
    </a>
@endcan

@can('revenue_statuses.view')
    <a href="{{ route('admin.assets.revenue-statuses.index') }}" class="sidebar-sublink {{ $isRevenueStatusActive ? 'active' : '' }}">
        <i class="ri-money-dollar-circle-line"></i> <span>Revenue / Non-Revenue Status</span>
    </a>
@endcan
