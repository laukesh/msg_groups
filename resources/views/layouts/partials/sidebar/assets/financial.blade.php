@canany(['incomes.view', 'expenses.view', 'economic_dashboard.view'])

    <div class="sidebar-section-label">Financial & Economic</div>

    <!-- @can('incomes.view')
        <a href="{{ route('admin.assets.incomes.index', ['asset' => 1]) }}" class="sidebar-sublink {{ $isIncomesActive ? 'active' : '' }}">
            <i class="ri-money-dollar-circle-line"></i> <span>Asset Income</span>
        </a>
    @endcan

    @can('expenses.view')
        <a href="{{ route('admin.assets.expenses.index', ['asset' => 1]) }}" class="sidebar-sublink {{ $isExpensesActive ? 'active' : '' }}">
            <i class="ri-wallet-3-line"></i> <span>Asset Expenses</span>
        </a>
    @endcan -->

    @can('economic_dashboard.view')
        <a href="{{ route('admin.assets.economic-dashboard') }}" class="sidebar-sublink {{ $isEconomicActive ? 'active' : '' }}">
            <i class="ri-line-chart-line"></i> <span>Economic Dashboard</span>
        </a>
    @endcan

@endcanany
