<aside class="app-sidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <img src="{{ asset('public/assets/img/logo-color.png') }}" alt="Hargeisa Mall">
        </div>
    </div>

    {{-- MENU (one partial per module) --}}
    <div class="sidebar-menu">
        @auth
            @include('layouts.partials.sidebar.home')
            @include('layouts.partials.sidebar.dashboard')
            @include('layouts.partials.sidebar.development-management')
            @include('layouts.partials.sidebar.portfolio-investment')
            @include('layouts.partials.sidebar.development-overview')
            @include('layouts.partials.sidebar.contract-management')
            @include('layouts.partials.sidebar.assets.index')
            @include('layouts.partials.sidebar.leasing')
            @include('layouts.partials.sidebar.tenants')
            @include('layouts.partials.sidebar.revenue')
            @include('layouts.partials.sidebar.fitout')
            @include('layouts.partials.sidebar.performance')
            @include('layouts.partials.sidebar.administration')
            @include('layouts.partials.sidebar.activity-logs')
        @endauth
    </div>

    {{-- FOOTER --}}
    <div class="sidebar-footer">
        <small>Hargeisa Mall Tracker</small>
    </div>

</aside>
