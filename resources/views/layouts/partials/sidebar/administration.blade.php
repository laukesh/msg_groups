@canany(['users.view', 'roles.view', 'audit.view'])

@php
    $isUsersActive = request()->routeIs('admin.users.*');
    $isRolesActive = request()->routeIs('admin.roles.*');
    $isAuditActive = request()->routeIs('admin.users.audits');

    $isAdministrationActive = $isUsersActive || $isRolesActive || $isAuditActive;
@endphp

<details class="sidebar-group" {{ $isAdministrationActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isAdministrationActive ? 'active' : '' }}">
        <i class="ri-settings-3-line"></i>
        <span>Administration</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        @can('users.view')
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-sublink {{ $isUsersActive ? 'active' : '' }}">
                <i class="ri-user-line"></i><span>Users</span>
            </a>
        @endcan

        @can('roles.view')
            <a href="{{ route('admin.roles.index') }}"
               class="sidebar-sublink {{ $isRolesActive ? 'active' : '' }}">
                <i class="ri-shield-user-line"></i><span>Roles & Permissions</span>
            </a>
        @endcan

        @can('audit.view')
            <a href="{{ route('admin.users.audits', auth()->id()) }}"
               class="sidebar-sublink {{ $isAuditActive ? 'active' : '' }}">
                <i class="ri-file-list-3-line"></i><span>Audit Trail</span>
            </a>
        @endcan

    </div>
</details>

@endcanany
