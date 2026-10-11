@php
    $isRevenueGroupActive      = request()->is('admin/revenue*');
    $isRevenueDashboardActive  = request()->is('admin/revenue/dashboard');
    $isRentSchedulesActive     = request()->is('admin/revenue/rent-schedules*');
    $isInvoicesActive          = request()->is('admin/revenue/invoices*');
    $isPaymentsActive          = request()->is('admin/revenue/payments*');
    $isReconciliationActive    = request()->is('admin/revenue/reconciliation*');
    $isOutstandingActive       = request()->is('admin/revenue/outstanding') && !request()->is('admin/revenue/outstanding/*');
    $isOverdueActive           = request()->is('admin/revenue/outstanding/overdue*');
    $isTenantOutstandingActive = request()->is('admin/revenue/outstanding/tenants*');
    $isRevenueReportActive     = request()->is('admin/revenue/reports/revenue*');
    $isCollectionReportActive  = request()->is('admin/revenue/reports/collections*');
    $isTenantWiseRevenueActive = request()->is('admin/revenue/reports/tenant-wise*');
    $isAgingReportActive       = request()->is('admin/revenue/reports/aging*');
@endphp

<details class="sidebar-group" {{ $isRevenueGroupActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isRevenueGroupActive ? 'active' : '' }}">
        <i class="ri-money-rupee-circle-line"></i>
        <span>Revenue</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        <a href="{{ url('/admin/revenue/dashboard') }}" class="sidebar-sublink {{ $isRevenueDashboardActive ? 'active' : '' }}">
            <i class="ri-dashboard-line"></i><span>Dashboard</span>
        </a>

        <div class="sidebar-section-label">Billing</div>

        <a href="{{ url('/admin/revenue/rent-schedules') }}" class="sidebar-sublink {{ $isRentSchedulesActive ? 'active' : '' }}">
            <i class="ri-calendar-schedule-line"></i><span>Rent Schedules</span>
        </a>

        <a href="{{ url('/admin/revenue/invoices') }}" class="sidebar-sublink {{ $isInvoicesActive ? 'active' : '' }}">
            <i class="ri-file-list-3-line"></i><span>Invoices</span>
        </a>

        <div class="sidebar-section-label">Collections</div>

        <a href="{{ url('/admin/revenue/payments') }}" class="sidebar-sublink {{ $isPaymentsActive ? 'active' : '' }}">
            <i class="ri-bank-card-line"></i><span>Payments</span>
        </a>

        <a href="{{ url('/admin/revenue/reconciliation') }}" class="sidebar-sublink {{ $isReconciliationActive ? 'active' : '' }}">
            <i class="ri-exchange-funds-line"></i><span>Reconciliation</span>
        </a>

        <div class="sidebar-section-label">Outstanding</div>

        <a href="{{ url('/admin/revenue/outstanding') }}" class="sidebar-sublink {{ $isOutstandingActive ? 'active' : '' }}">
            <i class="ri-money-dollar-circle-line"></i><span>Outstanding</span>
        </a>

        <a href="{{ url('/admin/revenue/outstanding/overdue') }}" class="sidebar-sublink {{ $isOverdueActive ? 'active' : '' }}">
            <i class="ri-alarm-warning-line"></i><span>Overdue</span>
        </a>

        <a href="{{ url('/admin/revenue/outstanding/tenants') }}" class="sidebar-sublink {{ $isTenantOutstandingActive ? 'active' : '' }}">
            <i class="ri-user-search-line"></i><span>Tenant Outstanding</span>
        </a>

        <div class="sidebar-section-label">Reports</div>

        <a href="{{ url('/admin/revenue/reports/revenue') }}" class="sidebar-sublink {{ $isRevenueReportActive ? 'active' : '' }}">
            <i class="ri-bar-chart-line"></i><span>Revenue Report</span>
        </a>

        <a href="{{ url('/admin/revenue/reports/collections') }}" class="sidebar-sublink {{ $isCollectionReportActive ? 'active' : '' }}">
            <i class="ri-file-chart-line"></i><span>Collection Report</span>
        </a>

        <a href="{{ url('/admin/revenue/reports/tenant-wise') }}" class="sidebar-sublink {{ $isTenantWiseRevenueActive ? 'active' : '' }}">
            <i class="ri-user-chart-line"></i><span>Tenant-wise Revenue</span>
        </a>

        <a href="{{ url('/admin/revenue/reports/aging') }}" class="sidebar-sublink {{ $isAgingReportActive ? 'active' : '' }}">
            <i class="ri-time-line"></i><span>Aging Report</span>
        </a>

    </div>
</details>
