@can('asset_reports.view')

    <div class="sidebar-section-label"> Asset Reports & KPIs</div>

    <a href="{{ route('admin.assets.reports.core-kpis.index') }}" class="sidebar-sublink {{ $isCoreKpisActive ? 'active' : '' }}">
        <i class="ri-dashboard-3-line"></i> <span> Core Asset KPIs</span>
    </a>

    <a href="{{ route('admin.assets.reports.industry-kpis.index') }}" class="sidebar-sublink {{ $isIndustryKpisActive ? 'active' : '' }}">
        <i class="ri-bar-chart-grouped-line"></i> <span> Industry-Specific KPIs</span>
    </a>

@endcan
