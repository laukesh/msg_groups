@can('audit.view')
    <a href="{{ route('admin.activity-logs.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
        <i class="ri-user-line"></i>
        <span>Activity Logs</span>
    </a>
@endcan
