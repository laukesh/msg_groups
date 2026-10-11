<div class="dropdown topbar-user">

    <button type="button" class="topbar-user-toggle" data-bs-toggle="dropdown" aria-expanded="false">

        <div class="topbar-user-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>

        <div class="topbar-user-info">
            <strong>{{ auth()->user()->name ?? 'User' }}</strong>
            <small>{{ auth()->user()->getRoleNames()->implode(', ') ?: 'User' }}</small>
        </div>

        <i class="ri-arrow-down-s-line user-arrow"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">

        @can('profile.view')
            <a href="{{ route('profile.show') }}" class="dropdown-item">
                <i class="ri-user-line"></i><span>My Profile</span>
            </a>
        @endcan

        @can('profile.update')
            <a href="{{ route('profile.password') }}" class="dropdown-item">
                <i class="ri-lock-password-line"></i><span>Change Password</span>
            </a>
        @endcan

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="dropdown-item logout-item">
                <i class="ri-logout-box-r-line"></i><span>Logout</span>
            </button>
        </form>

    </div>
</div>
