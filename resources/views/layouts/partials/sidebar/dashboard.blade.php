@can('dashboard.view')
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="ri-dashboard-line"></i>
        <span>Dashboard</span>
    </a>
@endcan
