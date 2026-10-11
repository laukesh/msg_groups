@php
    $isPortfolioActive = request()->routeIs('admin.portfolio-investment.*');
@endphp

<details class="sidebar-group" {{ $isPortfolioActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isPortfolioActive ? 'active' : '' }}">
        <i class="ri-briefcase-4-line"></i>
        <span>Portfolio & Investment</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        <a href="{{ route('admin.portfolio-investment.overview') }}" class="sidebar-sublink">
            <i class="ri-dashboard-line"></i><span>Portfolio Overview</span>
        </a>

        <a href="{{ route('admin.land.lands.index') }}" class="sidebar-sublink">
            <i class="ri-landscape-line"></i><span>Land Bank</span>
        </a>

        <a href="{{ route('admin.land.opportunities.index') }}" class="sidebar-sublink">
            <i class="ri-lightbulb-line"></i><span>Investment Opportunities</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.acquisition-pipeline') }}" class="sidebar-sublink">
            <i class="ri-git-merge-line"></i><span>Acquisition Pipeline</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.due-diligence') }}" class="sidebar-sublink">
            <i class="ri-search-eye-line"></i><span>Due Diligence</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.feasibility-studies') }}" class="sidebar-sublink">
            <i class="ri-line-chart-line"></i><span>Feasibility Studies</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.development-appraisal') }}" class="sidebar-sublink">
            <i class="ri-building-line"></i><span>Development Appraisal</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.scenario-sensitivity') }}" class="sidebar-sublink">
            <i class="ri-sliders-2-line"></i><span>Scenario & Sensitivity Analysis</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.investment-committee') }}" class="sidebar-sublink">
            <i class="ri-team-line"></i><span>Investment Committee</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.capital-planning') }}" class="sidebar-sublink">
            <i class="ri-money-dollar-circle-line"></i><span>Capital Planning</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.valuation') }}" class="sidebar-sublink">
            <i class="ri-calculator-line"></i><span>Valuation</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.asset-strategy') }}" class="sidebar-sublink">
            <i class="ri-bar-chart-grouped-line"></i><span>Asset Strategy & Business Plans</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.acquisition-disposal') }}" class="sidebar-sublink">
            <i class="ri-arrow-left-right-line"></i><span>Acquisition & Disposal</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.performance-monitoring') }}" class="sidebar-sublink">
            <i class="ri-line-chart-line"></i><span>Portfolio Performance</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.investment-reports') }}" class="sidebar-sublink">
            <i class="ri-file-chart-line"></i><span>Investment Reports</span>
        </a>

        <a href="#" class="sidebar-sublink">
            <i class="ri-settings-4-line"></i><span>Asset Purpose & Operating Model</span>
        </a>

        <a href="{{ route('admin.portfolio-investment.ownership-use-strategy') }}" class="sidebar-sublink">
            <i class="ri-user-settings-line"></i><span>Ownership / Use Strategy</span>
        </a>

        <a href="#" class="sidebar-sublink">
            <i class="ri-money-rupee-circle-line"></i><span>Revenue Model</span>
        </a>

    </div>
</details>
