<header class="app-topbar">

    {{-- LEFT --}}
    <div class="topbar-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle">
            <i class="ri-menu-line"></i>
        </button>

        <div class="page-heading">
            <h5>@yield('page_title', 'Dashboard')</h5>
            <span>Hargeisa Mall Management System</span>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="topbar-right">

        <div class="topbar-date">
            <i class="ri-calendar-line"></i>
            <span id="datechip"></span>
        </div>

        <button type="button" class="topbar-icon">
            <i class="ri-notification-3-line"></i>
            <span class="notification-dot"></span>
        </button>

        @include('layouts.partials.user-dropdown')

    </div>
</header>
