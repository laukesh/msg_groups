@extends('layouts.app')

@section('title', 'Development Management Overview')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Development Management</h4>
            <p class="text-muted mb-0">
                Consolidated project development dashboard
            </p>
        </div>

        <div>
            <a href="{{ route('admin.projects.index') }}"
               class="btn btn-primary">
                <i class="fas fa-project-diagram"></i>
                All Projects
            </a>
        </div>
    </div>


    {{-- =========================================================
         PRIMARY KPI CARDS
    ========================================================== --}}
    <div class="row">

        {{-- Total Projects --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Projects
                    </div>

                    <div class="h4 mb-0 font-weight-bold">
                        {{ number_format($totalProjects ?? 0) }}
                    </div>
                </div>
            </div>
        </div>


        {{-- Total Budget --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Total Budget
                    </div>

                    <div class="h4 mb-0 font-weight-bold">
                        ₹{{ number_format($budgetSummary->total_budget_amount ?? 0, 2) }}
                    </div>

                    <small class="text-muted">
                        {{ number_format($budgetSummary->total_budgets ?? 0) }}
                        budgets
                    </small>
                </div>
            </div>
        </div>


        {{-- Procurement Packages --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Procurement Packages
                    </div>

                    <div class="h4 mb-0 font-weight-bold">
                        {{ number_format($procurementSummary->total_packages ?? 0) }}
                    </div>

                    <small class="text-muted">
                        {{ number_format($totalTenders ?? 0) }}
                        tenders
                    </small>
                </div>
            </div>
        </div>


        {{-- Open Risks --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Open Risks
                    </div>

                    <div class="h4 mb-0 font-weight-bold">
                        {{ number_format($riskSummary->open_risks ?? 0) }}
                    </div>

                    <small class="text-danger">
                        Critical:
                        {{ number_format($riskSummary->critical_risks ?? 0) }}
                    </small>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         SECONDARY KPI CARDS
    ========================================================== --}}
    <div class="row">

        {{-- Master Schedules --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Master Schedules
                    </h6>

                    <h4>
                        {{ number_format($scheduleSummary->total_schedules ?? 0) }}
                    </h4>

                    <small>
                        Activities:
                        {{ number_format($totalScheduleActivities ?? 0) }}
                    </small>
                </div>
            </div>
        </div>


        {{-- Schedule Progress --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Average Schedule Progress
                    </h6>

                    <h4>
                        {{ number_format($scheduleSummary->average_actual_progress ?? 0, 2) }}%
                    </h4>

                    <small class="text-muted">
                        Planned:
                        {{ number_format($scheduleSummary->average_planned_progress ?? 0, 2) }}%
                    </small>
                </div>
            </div>
        </div>


        {{-- Construction Progress --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Construction Progress
                    </h6>

                    <h4>
                        {{ number_format($constructionProgressSummary->average_actual_progress ?? 0, 2) }}%
                    </h4>

                    <small>
                        {{ number_format($totalConstructionWorkOrders ?? 0) }}
                        work orders
                    </small>
                </div>
            </div>
        </div>


        {{-- Contracts --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Procurement Contracts
                    </h6>

                    <h4>
                        {{ number_format($totalProcurementContracts ?? 0) }}
                    </h4>

                    <small>
                        Contract Value:
                        ₹{{ number_format($totalContractValue ?? 0, 2) }}
                    </small>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         PROJECT STATUS AND STAGE
    ========================================================== --}}
    <div class="row">

        {{-- Project Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Projects by Status
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($projectStatusSummary ?? [] as $status)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $status->project_status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($status->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No project status data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>


        {{-- Project Stage --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Projects by Stage
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($projectStageSummary ?? [] as $stage)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $stage->project_stage ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($stage->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No project stage data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         BUDGET AND SCHEDULE STATUS
    ========================================================== --}}
    <div class="row">

        {{-- Budget Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        Budget Status Summary
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($budgetStatusSummary ?? [] as $budgetStatus)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $budgetStatus->status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($budgetStatus->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No budget status data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>


        {{-- Schedule Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        Schedule Status Summary
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($scheduleStatusSummary ?? [] as $scheduleStatus)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $scheduleStatus->status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($scheduleStatus->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No schedule status data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         PROCUREMENT AND RISK STATUS
    ========================================================== --}}
    <div class="row">

        {{-- Procurement Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Procurement Status Summary
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($procurementStatusSummary ?? [] as $procurementStatus)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $procurementStatus->status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($procurementStatus->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No procurement status data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>


        {{-- Risk Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        Risk Status Summary
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($riskStatusSummary ?? [] as $riskStatus)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $riskStatus->status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($riskStatus->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No risk status data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         RISK LEVEL AND APPROVAL CONFIGURATION
    ========================================================== --}}
    <div class="row">

        {{-- Risk Level Summary --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        Risk Level Summary
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($riskLevelSummary ?? [] as $risk)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $risk->risk_level ?? 'Unknown' }} Risk
                            </span>

                            <strong>
                                {{ number_format($risk->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No risk level data available.
                        </p>

                    @endforelse

                </div>
            </div>
        </div>


        {{-- Approval Matrix --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        Approval Configuration
                    </h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <strong>Total Approval Rules:</strong>
                        {{ number_format($totalApprovalRules ?? 0) }}
                    </div>

                    @forelse($approvalMatrixSummary ?? [] as $approval)

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $approval->status ?? 'Unknown' }}
                            </span>

                            <strong>
                                {{ number_format($approval->total ?? 0) }}
                            </strong>
                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No approval configuration available.
                        </p>

                    @endforelse

                    <small class="text-muted d-block mt-3">
                        These values represent approval matrix configurations,
                        not transactional approval requests.
                    </small>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         ADDITIONAL CONSTRUCTION INFORMATION
    ========================================================== --}}
    <div class="row">

        {{-- Construction Work Orders --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        Construction Information
                    </h6>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Total Work Orders</span>
                        <strong>
                            {{ number_format($totalConstructionWorkOrders ?? 0) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Progress Updates</span>
                        <strong>
                            {{ number_format($totalConstructionProgressUpdates ?? 0) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Average Actual Progress</span>
                        <strong>
                            {{ number_format($constructionProgressSummary->average_actual_progress ?? 0, 2) }}%
                        </strong>
                    </div>

                </div>
            </div>
        </div>


        {{-- Procurement Information --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        Procurement Information
                    </h6>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Total Packages</span>
                        <strong>
                            {{ number_format($procurementSummary->total_packages ?? 0) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Total Tenders</span>
                        <strong>
                            {{ number_format($totalTenders ?? 0) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Total Contracts</span>
                        <strong>
                            {{ number_format($totalProcurementContracts ?? 0) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>Total Contract Value</span>
                        <strong>
                            ₹{{ number_format($totalContractValue ?? 0, 2) }}
                        </strong>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         RECENT PROJECTS
    ========================================================== --}}
    <div class="card shadow mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Recent Projects
            </h6>

            <a href="{{ route('admin.projects.index') }}"
               class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>

        <div class="card-body">

            @if(isset($recentProjects) && $recentProjects->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Number</th>
                                <th>Project Name</th>
                                <th>Stage</th>
                                <th>Status</th>
                                <th>Planned Completion</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($recentProjects as $project)

                                <tr>
                                    <td>
                                        {{ $project->id }}
                                    </td>

                                    <td>
                                        {{ $project->project_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $project->project_name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge badge-info">
                                            {{ $project->project_stage ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $project->project_status ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(!empty($project->planned_completion_date))
                                            {{ \Carbon\Carbon::parse($project->planned_completion_date)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.projects.show', $project->id) }}"
                                           class="btn btn-sm btn-primary">
                                            View
                                        </a>

                                        <a href="{{ route('admin.projects.dashboard', $project->id) }}"
                                           class="btn btn-sm btn-info">
                                            Dashboard
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">
                    No projects found.
                </div>

            @endif

        </div>
    </div>

</div>

@endsection